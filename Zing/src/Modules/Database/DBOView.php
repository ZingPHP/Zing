<?php

namespace Modules\Database;

class DBOView extends \Modules\Database\DBOTable{

    protected
            $rows    = 25,
            $offset  = 0,
            $columns = "*",
            $filter  = "";

    public function __construct($table_name, $db, $config){
        if(!$this->_validName($table_name)){
            throw new \Exception("Invalid Table Name '$table_name'.");
        }
        $this->table = $table_name;
        parent::__construct($table_name, $db, $config);
    }

    public function setColumns(array $columns){
        $this->_testColumns($columns);
        $this->columns = $columns;
        return $this;
    }

    public function addColumns(array $columns){
        $this->_testColumns($columns);
        $this->columns = !is_array($this->columns) ? array() : $this->columns;
        $this->columns = array_merge($this->columns, $columns);
        return $this;
    }

    public function setRows($rows){
        $this->rows = (int)$rows;
        return $this;
    }

    public function setOffset($offset){
        $this->offset = (int)$offset;
        return $this;
    }

    public function setFilter($filter){
        $this->filter = $filter;
        return $this;
    }

    public function getTableView(){
        $where   = !empty($this->filter) ? "where $this->filter" : "";
        $columns = $this->getColumns();
        return $this->getAll("select $columns from $this->table $where limit $this->offset, $this->rows");
    }

    protected function getColumns(){
        if(is_string($this->columns)){
            return $this->columns;
        }
        $cols    = array_keys($this->columns);
        $aliases = array_values($this->columns);
        $strs    = array();
        foreach($cols as $key => $col){
            $alias = $aliases[$key];
            if(is_int($col)){
                $str = "`$col`";
            }else{
                $str = "$col as `$alias`";
            }
            $strs[] = "$str";
        }
        return implode(", ", $strs);
    }

}
