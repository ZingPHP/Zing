<?php

namespace Modules\Database;

/**
 * @method array getItemsBy_*() getItemsBy*(mixed $value) Gets items from the table by column name
 */
class DBOTable extends \Modules\DBO{

    private $table_primary_keys = array();
    private $table;
    private $internalQuery      = array(
        "select" => "",
        "order"  => "",
        "where"  => "",
        "group"  => "",
    );

    public function __construct($table_name, $db, $config){
        $this->db = $db;
        if(!$this->_validName($table_name)){
            throw new \Exception("Invalid Table Name '$table_name'.");
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

    public function getView(){
        return new \Modules\Database\DBOView($this->table, $this->db, $this->config);
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
            throw new \Exception("Can't do an 'ignore' and 'duplicate key update' in the same query.");
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
        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($params));
        $p  = iterator_to_array($it, false);
        $this->beginTransaction();
        try{
            $result = $this->query($sql, $p);
            $this->commitTransaction();
            return $result;
        }catch(\Exception $e){
            $this->rollBackTransaction();
        }
    }

    public function insert(array $data){
        $keys   = array_keys($data);
        $values = array_values($data);
        foreach($keys as $key){
            if(!$this->_validName($key)){
                throw new Exception("Column '$key' is not a valid name.");
            }
        }
        $q = array_pad(array(), count($data), "?");
        $this->query("insert into `$this->table` (`" . implode("`,`", $keys) . "`) values (" . implode(",", $q) . ")", $values);
        return $this;
    }

    public function getAllRows(){
        $this->internalQuery["select"] = "select * from `$this->table`";
        return $this;
    }

    public function orderRows($column, $direction = "asc"){
        if(!$this->_validName($column)){
            throw new \Exception("Invalid order by column name '$column'");
        }
        $direction                    = !in_array($direction, array("asc", "desc")) ? "asc" : $direction;
        $this->internalQuery["order"] = "order by $column $direction";
        return $this;
    }

    public function filterRows($filter){
        $this->internalQuery["where"] = $filter;
        return $this;
    }

    public function go(){
        $select = $this->internalQuery["select"];
        $where  = $this->internalQuery["where"];
        $group  = $this->internalQuery["group"];
        $order  = $this->internalQuery["order"];
        $this->getAll("$select $where $group $order");
        return $this;
    }

    /**
     * Tests a table to see if a row exists using a filter.
     * @param string $filter Where clause
     * @param array $params
     * @return boolean
     * @throws \Exception
     */
    public function rowExists($filter, array $params = array()){
        return (bool)$this->getOne("select 1 from `$this->table` where $filter limit 1", $params);
    }

    /**
     * Tests a table to see if a row exists using an array.
     * @param array $columns
     * @return boolean
     * @throws \Exception
     */
    public function has(array $columns){
        $cols  = array_keys($columns);
        $vals  = array_values($columns);
        $this->_testColumns($cols);
        $where = array();
        foreach($cols as $col){
            $where[] = "$col = ?";
        }
        return (bool)$this->getOne("select 1 from $this->table where " . implode(" and ", $where) . " limit 1", $vals);
    }

    /**
     * Gets a list of items from a table based on the primary key
     * @param mixed $id
     * @param boolean $uniq
     * @return array|boolean
     */
    public function getItemById($id, $uniq = true){
        $id     = (int)$id;
        $table  = $this->table;
        $column = $this->_getPrimary();
        $extra  = $uniq ? "limit 1" : "";
        $query  = "select * from $table where $column = ? $extra";
        if($uniq){
            $array = $this->_getRow($query, array($id));
        }else{
            $array = $this->_getAll($query, array($id));
        }
        $this->setArray($array);
        return $this;
    }

    public function getItemByColumns(array $columns, array $orderBy = array()){
        $cols  = array_keys($columns);
        $vals  = array_values($columns);
        $this->_testColumns($cols);
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
                throw new \Exception("Invalid Column Name '$key'");
            }
            $value   = !in_array($value, array("asc", "desc")) ? "asc" : $value;
            $order[] = "$key $value";
        }

        $orderStr = "";
        if(count($order) > 0){
            $orderStr = "order by " . implode(",", $order);
        }

        $array = $this->_getAll("select * from $this->table where " . implode(" and ", $where) . " $orderStr", $vals);
        $this->setArray($array);
        return $this;
    }

    public function count(){
        return count($this->toArray());
    }

    /**
     * Gets data where column value equals value
     * @param string $column The column to use
     * @param mixed $value The value of the column
     * @return array
     * @throws \Exception
     */
    protected function _getItemsByColumn($column, $value, $uniq = false){
        if(!$this->_validName($column)){
            throw new \Exception("Invalid column format '$column'.");
        }
        if(!(bool)$uniq){
            $array = $this->_getAll("select * from `$this->table` where `$column` = ?", array($value));
        }else{
            $array = $this->_getRow("select * from `$this->table` where `$column` = ? limit 1", array($value));
        }
        $this->setArray($array);
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
