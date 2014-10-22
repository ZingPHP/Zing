<?php

namespace Modules\Database;

use Exception;
use Modules\DBO;
use Modules\ModuleShare;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * @method array getItemsBy_*() getItemsBy*(mixed $value) Gets items from the table by column name
 */
class DBOTable extends DBO{

    private $table_primary_keys = array();
    protected $table;
    private $joins              = array();
    private $columns            = array();
    private $internalQuery      = array(
        "select" => "",
        "order"  => "",
        "where"  => "",
        "group"  => "",
        "params" => array()
    );

    public function __construct($table_name, $config){
        $this->setConnectionParams($config);
        if(!$this->_validName($table_name)){
            throw new Exception("Invalid Table Name '$table_name'.");
        }
        $this->table = $table_name;
        parent::__construct($config);
    }

    /**
     *
     * @param string $name The name of the method
     * @param array $arguments The list of arguments
     * @return type
     */
    public function __call($name, $arguments){
        $matches = array();
        if(preg_match("/^getItemsBy_(.+)/", $name, $matches)){
            $this->_getItemsByColumn($matches[1], $arguments[0], $arguments[1]);
        }
        return $this;
    }

    /**
     * creates a multirow insert query
     * @param array $columns  Array of columns to use
     * @param array $params   Multilevel array of values
     * @param string $ignore  Adds an 'ignore' to the insert query
     * @param string $after   A final statment such as 'on duplicate key...'
     * @return boolean
     * @throws Exception
     */
    public function insertMultiRow(array $columns, array $params, $ignore = false, $after = ""){
        $ncols = count($columns);
        $table = $this->table;
        if((bool)$ignore && strlen($after) > 0){
            throw new Exception("Can't do an 'ignore' and 'duplicate key update' in the same query.");
        }

        $ign = (bool)$ignore ? "ignore" : "";

        $sql  = "insert $ign into $table";
        $sql .= " (" . implode(",", $columns) . ") ";
        $sql .= " values ";
        $data = array();
        foreach($params as $p){
            $this->_validMultiInsertValue($p, $ncols);
            $data[] = "(" . implode(",", array_pad(array(), $ncols, "?")) . ")";
        }
        $sql .= implode(",", $data);
        $sql .= " $after";
        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($params));
        $p  = iterator_to_array($it, false);
        $this->beginTransaction();
        try{
            $result = $this->query($sql, $p);
            $this->commitTransaction();
            return $result;
        }catch(Exception $e){
            $this->rollBackTransaction();
        }
        return $this;
    }

    /**
     * Inserts data into a table using key => value pairs
     * @param array $data A column => value array to insert
     * @param callable $onComplete A function to call when the insert finishes. The insert id will be passed as a parameter.
     * @return DBOTable
     * @throws Exception
     */
    public function insert(array $data, callable $onComplete = null){
        $keys   = array_keys($data);
        $values = array_values($data);
        $this->_testColumns($keys);

        $q = array_pad(array(), count($data), "?");
        $this->query("insert into `$this->table` (`" . implode("`,`", $keys) . "`) values (" . implode(",", $q) . ")", $values);

        if($onComplete !== null && is_callable($onComplete)){
            $id = $this->getInsertID();
            call_user_func_array($onComplete, array($id));
        }
        return $this;
    }

    /**
     * Deleates data from a table using key => value pairs
     * @param array $data A column => value array to insert
     * @param callable $onComplete A function to call when the insert finishes.
     * @return DBOTable
     * @throws Exception
     */
    public function delete(array $data, callable $onComplete = null){
        $keys   = array_keys($data);
        $values = array_values($data);
        $this->_testColumns($keys);
        $cols   = $this->_formatColumns($cols);

        $where = implode(" = ? and ", $cols) . " = ?";
        $where = $this->_buildWhere($where, $values);

        $this->query("delete from `$this->table` where " . $where, $values);

        if($onComplete !== null && is_callable($onComplete)){
            call_user_func_array($onComplete, array());
        }
        return $this;
    }

    /**
     * Gets all rows from a table (Use with care)
     * @return DBOTable
     */
    public function getAllRows(array $params = array()){
        $table                         = $this->_buildTableSyntax();
        $this->internalQuery["select"] = "select * from $table";
        $this->go($params);
        return $this;
    }

    /**
     * Orders the rows in the simple qurery builder
     * @param type $column
     * @param type $direction
     * @return DBOTable
     * @throws Exception
     */
    public function orderRows($column, $direction = "asc"){
        if(!$this->_validName($column)){
            throw new Exception("Invalid order by column name '$column'");
        }
        $direction                    = !in_array($direction, array("asc", "desc")) ? "asc" : $direction;
        $this->internalQuery["order"] = "order by $column $direction";
        return $this;
    }

    /**
     * Filter the rows in the simple query builder
     * @param type $filter
     * @return DBOTable
     */
    public function filterRows($filter){
        $this->internalQuery["where"] = "where " . $filter;
        return $this;
    }

    /**
     * Filter the rows using an array in the simple query builder
     * @param array $columns
     * @return DBOTable
     */
    public function arrayFilterRows(array $columns){
        $cols = array_keys($columns);
        $this->_testColumns($cols);
        $cols = $this->_formatColumns($cols);

        $where = implode(" = ? and ", $cols) . " = ?";
        $where = $this->_buildWhere($where, $columns);

        $this->internalQuery["where"]  = "where " . $where;
        $this->internalQuery["params"] = array_values($columns);
        return $this;
    }

    /**
     * Executes the simple query builder
     * @return DBOTable
     */
    private function go(){
        $select = $this->internalQuery["select"];
        $where  = $this->internalQuery["where"];
        $group  = $this->internalQuery["group"];
        $order  = $this->internalQuery["order"];
        $this->getAll("$select $where $group $order", $this->internalQuery["params"]);
        return $this;
    }

    /**
     * Tests a table to see if a row exists using a filter.
     * @param string $filter Where clause
     * @param array $params
     * @return boolean
     * @throws Exception
     */
    public function rowExists($filter, array $params = array()){
        $table = $this->_buildTableSyntax();
        $has   = (bool)$this->getOne("select 1 from $table where $filter limit 1", $params);

        $this->joins = array();
        return $has;
    }

    /**
     * Tests a table to see if a row exists using an array.
     * @param array $columns
     * @return boolean
     * @throws Exception
     */
    public function has(array $columns){
        $cols  = array_keys($columns);
        $vals  = array_values($columns);
        $this->_testColumns($cols);
        $cols  = $this->_formatColumns($cols);
        $table = $this->_buildTableSyntax();

        $where = implode(" = ? and ", $cols) . " = ?";
        $where = $this->_buildWhere($where, $vals);

        $has = (bool)$this->getOne("select 1 from $table where " . $where . " limit 1", $vals);

        $this->joins = array();
        return $has;
    }

    /**
     * Executes a user callback if the table contains a match
     * @param array $columns A column => value array to search for
     * @param callable $callback A user callback
     * @return DBOTable
     */
    public function ifHas(array $columns, callable $callback){
        if($this->has($columns)){
            call_user_func_array($callback, array($columns));
        }
        return $this;
    }

    /**
     * Executes a user callback if the table does not contain a match
     * @param array $columns A column => value array to search for
     * @param callable $callback A user callback
     * @return DBOTable
     */
    public function ifHasNot(array $columns, callable $callback){
        if(!$this->has($columns)){
            call_user_func_array($callback, array($columns));
        }
        return $this;
    }

    public function with(array $columns, callable $foundRows, callable $foundNothing = null){
        $cols  = array_keys($columns);
        $this->_testColumns($cols);
        $cols  = $this->_formatColumns($cols);
        $vals  = array_values($columns);
        $table = $this->_buildTableSyntax();

        $where = implode(" = ? and ", $cols) . " = ?";
        $where = $this->_buildWhere($where, $vals);

        $rows          = $this->_getAll("select " . implode(",", $this->columns) . " from $table where " . $where, $vals);
        $this->columns = array();
        if(count($rows) > 0){
            if(is_callable($foundRows)){
                foreach($rows as $row){
                    call_user_func_array($foundRows, array($row));
                }
            }
        }else{
            if(is_callable($foundNothing)){
                call_user_func_array($foundNothing, array());
            }
        }
        return $this;
    }

    /**
     * Gets a list of items from a table based on the primary key
     * @param mixed $id
     * @param boolean $uniq
     * @return array|boolean
     */
    public function getItemById($id, $uniq = true){
        $id     = (int)$id;
        $table  = $this->_buildTableSyntax();
        $column = $this->_getPrimary();
        $extra  = $uniq ? "limit 1" : "";
        $query  = "select * from $table where $column = ? $extra";
        if($uniq){
            $array = $this->_getRow($query, array($id));
        }else{
            $array = $this->_getAll($query, array($id));
        }
        $this->setArray($array);
        $this->joins = array();
        return $this;
    }

    /**
     * Adds a table to join on from the initial table or previous join() calls
     * @param string $table
     * @param array $on
     * @return DBOTable
     * @throws Exception
     */
    public function join($table, array $on){
        $joins = $this->_buildJoin($on);

        $this->joins[$table . "|join"] = $joins;
        return $this;
    }

    public function leftJoin($table, array $on){
        $joins = $this->_buildJoin($on);

        $this->joins[$table . "|left join"] = $joins;
        return $this;
    }

    public function setColumns(array $columns){
        $keys          = array_keys($columns);
        $cols          = array_values($columns);
        $this->_testColumns($cols);
        $cols          = $this->_formatColumns($cols);
        $this->columns = array_combine($keys, $cols);
    }

    /**
     * Gets Rows based on the array passed in
     * @param array $columns
     * @param bool $uniq
     * @param array $orderBy
     * @return DBOTable
     * @throws Exception
     */
    public function getItemsByColumn(array $columns, $uniq = false, array $orderBy = array()){
        $cols  = array_keys($columns);
        $vals  = array_values($columns);
        $this->_testColumns($cols);
        $cols  = $this->_formatColumns($cols);
        $where = array();
        foreach($cols as $col){
            $where[] = "$col = ?";
        }

        $order = array();
        foreach($orderBy as $key => $value){
            if(is_int($key)){
                $key   = $value;
                $value = "asc";
            }
            if(!$this->_validName($key)){
                throw new Exception("Invalid Column Name '$key'");
            }
            $value   = !in_array($value, array("asc", "desc")) ? "asc" : $value;
            $order[] = "$key $value";
        }

        $orderStr = "";
        if(count($order) > 0){
            $orderStr = "order by " . implode(",", $order);
        }
        $table = $this->_buildTableSyntax();
        if((bool)$uniq){
            $array = $this->_getRow("select * from $table where " . implode(" and ", $where) . " $orderStr limit 1", $vals);
        }else{
            $array = $this->_getAll("select * from $table where " . implode(" and ", $where) . " $orderStr", $vals);
        }
        $this->setArray($array);
        $this->joins = array();
        return $this;
    }

    /**
     * Formats a column or an array of database columns using a callback
     * @param string|array $columns
     * @param \Modules\Database\callable $formatter
     * @return DBOTable
     */
    public function formatColumn($columns, callable $formatter){
        if(!is_array($columns)){
            $columns = array($columns);
        }
        foreach($this as $key => $val){
            if(is_array($val)){
                foreach($val as $k => $v){
                    if(in_array($k, $columns)){
                        ModuleShare::$array[$key][$k] = $formatter($v);
                    }
                }
            }else{
                ModuleShare::$array[$key] = $formatter($val);
            }
        }
        return $this;
    }

    public function count(){
        return count($this->toArray());
    }

    /**
     * Creates a database table syntax. Example: tableA on tableB using(columnA)
     * @return type
     */
    protected function _buildTableSyntax(){
        $str = $this->table;
        foreach($this->joins as $tblJoin => $join){
            list($table, $joinType) = explode("|", $tblJoin);
            $str .= " $joinType $table ";
            $stritm = array();
            $i      = false;
            foreach($join as $j){
                $extra = "";
                if(strpos($j, "using(") === false && !$i){
                    $extra = "on";
                }
                $i        = true;
                $stritm[] = " $extra $j ";
            }
            $str .= implode(" and ", $stritm);
        }
        return $str;
    }

    protected function _buildWhere($where, $values){
        $groups = explode("?", $where);
        foreach($values as $offset => $value){
            if(is_null($value)){
                $groups[$offset] = str_replace("=", "is", $groups[$offset]);
            }
        }
        return implode("?", $groups);
    }

    /**
     * Gets data where column value equals value
     * @param string $column The column to use
     * @param mixed $value The value of the column
     * @return array
     * @throws Exception
     */
    protected function _getItemsByColumn($column, $value, $uniq = false){
        if(!$this->_validName($column)){
            throw new Exception("Invalid column format '$column'.");
        }
        if(!(bool)$uniq){
            $array = $this->_getAll("select * from `$this->table` where `$column` = ?", array($value));
        }else{
            $array = $this->_getRow("select * from `$this->table` where `$column` = ? limit 1", array($value));
        }
        $this->setArray($array);
    }

    protected function _buildJoin(array $on){
        $keys  = array_keys($on);
        $vals  = array_values($on);
        $joins = array();
        foreach($on as $k => $v){
            if(is_int($k) && $this->_validName($v)){
                $joins[] = "using({$vals[$k]})";
            }else{
                if(!$this->_validName($k)){
                    throw new Exception("Invalid name '$k'");
                }
                if(!$this->_validName($v)){
                    throw new Exception("Invalid name '$v'");
                }
                $joins[] = "$k = {$on[$k]}";
            }
        }
        return $joins;
    }

    protected function _formatColumns(array $columns){
        $final = array();
        foreach($columns as $col){
            $newCol  = explode(".", $col);
            $final[] = "`" . implode("`.`", $newCol) . "`";
        }
        return $final;
    }

    /**
     * Gets the primary key of a table
     * @return string|boolean
     */
    private function _getPrimary(){
        if(array_key_exists($this->table, $this->table_primary_keys)){
            return $this->table_primary_keys[$this->table];
        }
        if(!$this->_validName($this->table)){
            return false;
        }
        $key = $this->getOne("select COLUMN_NAME from information_schema.COLUMNS where COLUMN_KEY = 'pri' and TABLE_NAME = ? limit 1", array($this->table));

        $this->table_primary_keys[$this->table] = $key;
        return $key;
    }

}
