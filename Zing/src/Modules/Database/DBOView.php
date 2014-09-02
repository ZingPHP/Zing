<?php

namespace Modules\Database;

class DBOView extends \Modules\Database\DBOTable{

    protected
            $rows          = 25,
            $offset        = 0,
            $columns       = "*",
            $filter        = "",
            $foundRows     = 0,
            $resultSetSize = 0,
            $page          = 0;

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

    public function setPage($page){
        $this->page = $page;
        return $this;
    }

    public function setFilter($filter){
        $this->filter = $filter;
        return $this;
    }

    public function getTableView(){
        $where   = !empty($this->filter) ? "where $this->filter" : "";
        $columns = $this->getColumns();
        $offset  = $this->getOffset();

        $data = $this->getAll("select SQL_CALC_FOUND_ROWS $columns from $this->table $where limit $offset, $this->rows");

        $this->resultSet = count($data);
        $this->foundRows = $this->getOne("select found_rows()");
        return $this;
    }

    public function getMeta(){
        return array(
            "total"  => $this->foundRows,
            "rows"   => $this->resultSetSize,
            "page"   => $this->page,
            "pages"  => ceil($this->foundRows / $this->resultSetSize),
            "offset" => $this->getOffset(),
        );
    }

    protected function getOffset(){
        return (($this->page * $this->rows) - $this->rows);
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
