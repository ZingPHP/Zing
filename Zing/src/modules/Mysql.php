<?php

class Mysql extends Module{

    private
            $hostname = "",
            $username = "",
            $password = "",
            $database = "",
            $db       = null,
            $sql      = null;
    private $table_primary_keys = array();

    public function setConnectionParams($config){
      $this->hostname = isset($config["hostname"]) ? $config["hostname"] : "";
      $this->username = isset($config["username"]) ? $config["username"] : "";
      $this->password = isset($config["password"]) ? $config["password"] : "";
      $this->database = isset($config["username"]) ? $config["database"] : "";
    }

    /**
     * Initialize a mysql database object
     * @param array $config
     * @return \Mysql
     */
    public function init($config){
        return new Mysql($config);
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
        try{
            $this->db = new PDO("mysql:dbname=$this->database;host=$this->hostname", $this->username, $this->password);
            return $this;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function query($query, $params = array()){
        try{
            $this->connect();
            $this->sql = $this->db->prepare($query);
            $this->bind($query, $params);
            $this->sql->execute();
            return true;
        }catch(Exception $e){
            throw $e;
        }
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
        return $this->sql->fetchAll();
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
        return $this->sql->fetch();
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
     * Gets a list of items from a table based on the primary key
     * @param string $table
     * @param mixed $id
     * @param boolean $uniq
     * @return array|boolean
     */
    public function getById($table, $id, $uniq = true){
        $id     = (int)$id;
        $column = $this->getPrimary($table);
        if($this->validName($table)){
            $extra = $uniq ? "limit 1" : "";
            return $this->getAll("select * from $table where $column = ? $extra", array($id));
        }
        return false;
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
    protected function bind($query, $params){
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
    private function validName($string){
        return !preg_match("/[^a-zA-Z0-9\$_]/i", $string);
    }

    /**
     * Gets the primary key of a table
     * @param string $table
     * @return string|boolean
     */
    private function getPrimary($table){
        if(array_key_exists($table, $this->table_primary_keys)){
            return $this->table_primary_keys[$table];
        }
        if(!$this->validName($table)){
            return false;
        }
        $key                              = $this->getOne("select COLUMN_NAME from information_schema.COLUMNS where COLUMN_KEY = 'pri' and TABLE_NAME = ? limit 1", array($table));
        $this->table_primary_keys[$table] = $key;
        return $key;
    }

}
