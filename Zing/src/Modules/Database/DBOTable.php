<?php

namespace Modules\Database;

/**
 * @method array getItemsBy*() getItemsBy*(mixed $value) Gets items from the table by column name
 */
class DBOTable extends \Modules\DBO{

    private $table_primary_keys = array();
    private $table;

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
        if(preg_match("/^getItemsBy(.+)/", $name, $matches)){
            $this->_getItemsByColumn($matches[1], $arguments[0]);
        }
        return $this;
    }

    public function getView(){
        return new \Modules\Database\DBOView($this->table, $this->db, $this->config);
    }

    /**
     * Inserts multiple rows into the table
     * @param array $columns    Array of columns to use
     * @param array $params     Multilevel array of values
     * @param string $ignore    Adds an 'ignore' to the insert query
     * @param string $after     A final statment such as 'on duplicate key...'
     * @return boolean
     * @throws Exception
     */
    public function insertMultiRow(array $columns, array $params, $ignore = false, $after = ""){
        $ncols = count($columns);
        if((bool)$ignore && strlen($after) > 0){
            throw new \Exception("Can't do an 'ignore' and 'duplicate key update' in the same query.");
        }

        $ign = (bool)$ignore ? "ignore" : "";

        $sql  = "insert $ign into `$this->table`";
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

    /**
     * Insert data into the table where the key is the column and the value is the insert value
     * @param array $data    An array of data to insert
     * @return \Modules\Database\DBOTable
     */
    public function insert(array $data){
        $keys   = array_keys($data);
        $values = array_values($data);
        $this->_testColumns($keys);
        $q      = array_pad(array(), count($data), "?");
        $this->query("insert into `$this->table` (`" . implode("`,`", $keys) . "`) values (" . implode(",", $q) . ")", $values);
        return $this;
    }

    /**
     * Deletes a record or multiple records from the table
     * @param mixed $id     The column value
     * @param bool $uniq    Whether or not the column is unique
     * @return \Modules\Database\DBOTable
     */
    public function delete($id, $uniq = true){
        $id     = $id;
        $column = $this->_getPrimary();
        $extra  = (bool)$uniq ? "limit 1" : "";
        $this->beginTransaction();
        try{
            $this->query("delete from `$this->table` where $column = ? $extra", array($id));
            $this->commitTransaction();
        }catch(Exception $e){
            $this->rollBackTransaction();
        }
        return $this;
    }

    /**
     * Creates and executes a join query
     * @param array $tables     List of tables (other than current table)
     * @param array $columns    List of columns (join key on value)
     * @param string $filter    The Where clause without the "WHERE"
     * @param array $params     PDO Replacement values
     * @return \Modules\Database\DBOTable
     */
    public function join(array $tables, array $columns, $filter = "", array $params = array()){
        $this->_testTables($tables);
        $this->_testColumns($columns);
        $this->_testColumns(array_keys($columns));
        $joins = array();
        $i     = 0;
        foreach($columns as $col1 => $col2){
            $joins[] = "`{$tables[$i]}` on `$col1` = `$col2`";
            $i++;
        }
        $where = !empty($filter) ? " where $filter " : "";
        $this->getAll("select * from `$this->table` join " . implode(" join ", $joins) . " $where", $params);
        return $this;
    }

    /**
     * Tests a table to see if a row exists within the table
     * @param string $filter    The Where clause without the "WHERE"
     * @param array $params     PDO Replacement values
     * @return boolean
     * @throws \Exception
     */
    public function rowExists($filter, array $params = array()){
        return (bool)$this->getOne("select 1 from `$this->table` where $filter limit 1", $params);
    }

    /**
     * Gets a list of items from a table based on the primary key
     * @param mixed $id        The column value
     * @param boolean $uniq    Whether or not the column is unique
     * @return array|boolean
     */
    public function getItemById($id, $uniq = true){
        $id     = $id;
        $column = $this->_getPrimary();
        $extra  = (bool)$uniq ? "limit 1" : "";
        $array  = $this->_getAll("select * from `$this->table` where $column = ? $extra", array($id));
        $this->setArray($array);
        return $this;
    }

    /**
     * Gets the number of rows that were found
     * @return int
     */
    public function count(){
        return count($this->toArray());
    }

    /**
     * Gets data where column value equals value
     * @param string $column    The column to use
     * @param mixed $value      The value of the column
     * @return array
     * @throws \Exception
     */
    protected function _getItemsByColumn($column, $value){
        if(!$this->_validName($column)){
            throw new \Exception("Invalid column format '$column'.");
        }
        $array = $this->_getAll("select * from `$this->table` where `$column` = ?", array($value));
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

    /**
     * Tests an array of columns to see if they are vaild
     * @param array $columns    An array of coluns to test
     * @throws \Exception
     */
    private function _testColumns(array $columns){
        foreach($columns as $column){
            if(!$this->_validName($column)){
                throw new \Exception("Invalid Column Name '$column'.");
            }
        }
    }

    /**
     * Tests an array of tables to see if they are vaild
     * @param array $tables    An array of tables to test
     * @throws \Exception
     */
    private function _testTables(array $tables){
        foreach($tables as $table){
            if(!$this->_validName($column)){
                throw new \Exception("Invalid Table Name '$table'.");
            }
        }
    }

}
