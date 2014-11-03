<?php

namespace Modules;

use Exception;
use Modules\Database\DBOTable;
use Modules\Database\DBOView;
use PDO;

class DBO extends Module{

    const GET_ALL = "GET_ALL";
    const GET_ROW = "GET_ROW";
    const GET_ONE = "GET_ONE";

    protected
            $hostname = "",
            $username = "",
            $password = "",
            $database = "",
            $dsn      = "",
            $port     = 3306,
            $db       = null,
            $sql      = null,
            $dbConfig = null;

    public function setConnectionParams($config){
        $this->dbConfig = $config;
        $this->dsn      = isset($config["dsn"]) ? $config["dsn"] : "mysql";
        $this->hostname = isset($config["hostname"]) ? $config["hostname"] : "";
        $this->username = isset($config["username"]) ? $config["username"] : "";
        $this->password = isset($config["password"]) ? $config["password"] : "";
        $this->database = isset($config["username"]) ? $config["database"] : "";
        $this->port     = isset($config["port"]) ? $config["port"] : 3306;
    }

    public function __invoke(){
        //echo "here";
    }

    /**
     * Initialize a database object
     * @param array $config
     * @return DBO
     */
    public function init($config){
        return new DBO($config);
    }

    /**
     * Makes a connection to the database
     * @return \Mysql
     * @throws Exception
     */
    public function connect(){
        if($this->db !== null){
            return;
        }
        //echo "connecting...";
        try{
            $this->db = new PDO("$this->dsn:dbname=$this->database;host=$this->hostname;port=" . (int)$this->port, $this->username, $this->password);
            return $this;
        }catch(Exception $e){
            throw $e;
        }
    }

    /**
     * Creates a new Database Object Table
     * @param string $table_name    The name of the table
     * @return DBOTable
     */
    public function getTable($table_name){
        return new DBOTable($table_name, $this->dbConfig);
    }

    /**
     * Creates a new Database View
     * @param string $table_name    The name of the table
     * @return DBOView
     */
    public function getView($table_name){
        return new DBOView($table_name, $this->dbConfig);
    }

    /**
     * Executes a database query
     * @param string $query
     * @param array $params
     * @return boolean
     * @throws \Modules\Exception
     */
    public function query($query, array $params = array()){
        try{
            $this->connect();
            $this->sql = $this->db->prepare($query);
            $this->bind($query, $params);
            $this->sql->execute();
            return true;
        }catch(Exception$e){
            throw $e;
        }
        return $this;
    }

    /**
     * Get all results from a database query
     * @param string $query
     * @param array $params
     * @param boolean $qstr
     * @return array
     */
    public function getAll($query, array $params = array()){
        $this->query($query, $params);
        $array = $this->sql->fetchAll(PDO::FETCH_ASSOC);
        $this->setArray($array);
        return $this;
    }

    /**
     * Get one row from a database query
     * @param string $query
     * @param array $params
     * @param boolean $qstr
     * @return array
     */
    public function getRow($query, array $params = array()){
        $this->query($query, $params);
        $array = $this->sql->fetch(PDO::FETCH_ASSOC);
        $this->setArray($array);
        return ModuleShare::$array;
    }

    /**
     * Get one column from a database query
     * @param string $query
     * @param array $params
     * @param boolean $qstr
     * @return mixed
     */
    public function getOne($query, array $params = array()){
        $this->query($query, $params);
        return $this->sql->fetchColumn(0);
    }

    /**
     * Gets the next row in the data set
     * @param int $style
     * @return mixed
     */
    public function getNext($style = PDO::FETCH_ASSOC){
        return $this->sql->fetch($style);
    }

    /**
     * Gets the next dataset in a multi dataset query
     * @param int $style
     * @return mixed
     */
    public function getNextSet($style = DBO::GET_ALL){
        $this->sql->nextRowset();
        switch($style){
            case DBO::GET_ALL:
                return $this->sql->fetchAll();
            case DBO::GET_ROW:
                return $this->sql->fetch();
            case DBO::GET_ONE:
                return $this->sql->fetchColumn(0);
            default:
                return $this->sql->fetchAll();
        }
    }

    /**
     * Get the last auto increment insert id
     * @return integer
     */
    public function getInsertID(){
        return $this->db->lastInsertId();
    }

    /**
     * Get the number of rows affected by the last query
     * @return integer
     */
    public function getAffectedRows(){
        return $this->sql->rowCount();
    }

    /**
     * Start a database transaction
     * @return boolean
     */
    public function beginTransaction(){
        $this->connect();
        return $this->db->beginTransaction();
    }

    /**
     * Commit a database transaction
     * @return boolean
     */
    public function commitTransaction(){
        $this->connect();
        return $this->db->commit();
    }

    /**
     * Roll back a database transaction
     * @return boolean
     */
    public function rollBackTransaction(){
        $this->connect();
        return $this->db->rollBack();
    }

    /**
     * Makes parameters MySQL safe
     * @param type $params
     */
    protected function bind($query, array $params){
        if(strpos($query, "?")){
            array_unshift($params, null);
            unset($params[0]);
        }
        foreach($params as $key => $val){
            switch(gettype($val)){
                case "boolean":
                    $type = PDO::PARAM_BOOL;
                    break;
                case "integer":
                    $type = PDO::PARAM_INT;
                    break;
                case "string":
                    $type = PDO::PARAM_STR;
                    break;
                case "null":
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
            $this->sql->bindValue($key, $val, $type);
        }
    }

    /**
     * Tests to see if a string is a valid table/column name
     * @param string $string
     * @return boolean
     */
    protected function _validName($string){
        return !preg_match("/[^a-zA-Z0-9\$_\.]/i", $string);
    }

    protected function _getAll($query, array $params = array()){
        $this->query($query, $params);
        return $this->sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function _getRow($query, array $params = array()){
        $this->query($query, $params);
        return $this->sql->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tests an array of columns to see if they are vaild
     * @param array $columns    An array of coluns to test
     * @throws Exception     */
    protected function _testColumns(array $columns){
        foreach($columns as $column){
            if(!$this->_validName($column)){
                throw new Exception("Invalid Column Name '$column'.");
            }
        }
    }

    /**
     * Tests an array of tables to see if they are vaild
     * @param array $tables    An array of tables to test
     * @throws Exception     */
    protected function _testTables(array $tables){
        foreach($tables as $table){
            if(!$this->_validName($table)){
                throw new Exception("Invalid Table Name '$table'.");
            }
        }
    }

}
