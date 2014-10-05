<?php

use Modules\Database\DBOMeta;
use Modules\Database\DBOTable;
use Modules\Database\DBOView;

namespace Modules\Database;

class DBOView extends DBOTable{

    protected
            $rows          = 25,
            $offset        = 0,
            $columns       = array(),
            $filter        = "",
            $foundRows     = 0,
            $resultSetSize = 0,
            $page          = 0;

    public function __construct($table_name, $db, $config){
        if(!$this->_validName($table_name)){
            throw new Exception("Invalid Table Name '$table_name'.");
        }
        $this->table = $table_name;
        parent::__construct($table_name, $db, $config);
    }

    /**
     * Sets the columns to get
     * @param array $columns    An array of columns (key = dynamic column; value = alias)
     * @return DBOView
     */
    public function setColumns(array $columns){
        $this->_testColumns($columns);
        $this->columns = $columns;
        return $this;
    }

    /**
     * Adds additional columns to get
     * @param array $columns
     * @return DBOView
     */
    public function addColumns(array $columns){
        $this->_testColumns($columns);
        $this->columns = !is_array($this->columns) ? array() : $this->columns;
        $this->columns = array_merge($this->columns, $columns);
        return $this;
    }

    /**
     * Sets the number of rows to return
     * @param int $rows
     * @return DBOView
     */
    public function setRows($rows){
        $this->rows = (int)$rows;
        return $this;
    }

    /**
     * Set the page number
     * @param int $page    The page number (1 or greater)
     * @return DBOView
     */
    public function setPage($page){
        $this->page = (int)$page > 0 ? (int)$page : 1;
        return $this;
    }

    /**
     * Set the filter to use
     * @param type $filter    Filter to use without the "WHERE"
     * @return DBOView
     */
    public function setFilter($filter){
        $this->filter = $filter;
        return $this;
    }

    /**
     * Get the the table view
     * @return DBOView
     */
    public function getTableView(){
        $where   = !empty($this->filter) ? "where $this->filter" : "";
        $columns = $this->getColumns();
        $offset  = $this->getOffset();

        $data = $this->getAll("select SQL_CALC_FOUND_ROWS $columns from $this->table $where limit $offset, $this->rows");

        $this->resultSet = count($data);
        $this->foundRows = $this->getOne("select found_rows()");
        return $this;
    }

    /**
     * Get the result information from the current view
     * @return DBOMeta
     */
    public function getMeta(){
        $meta         = new DBOMeta();
        $meta->total  = $this->foundRows;
        $meta->rows   = $this->resultSetSize;
        $meta->page   = $this->page;
        $meta->pages  = $this->getPages();
        $meta->offset = $this->getOffset();
        return $meta;
    }

    /**
     * Get the total number of pages in the view
     * @return int
     */
    protected function getPages(){
        return ceil($this->foundRows / $this->resultSetSize);
    }

    /**
     * Get the current views offset
     * @return int
     */
    protected function getOffset(){
        return (($this->page * $this->rows) - $this->rows);
    }

    /**
     * Converts the columns to a string
     * @return string
     */
    protected function getColumns(){
        if(empty($this->columns)){
            return "*";
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

class DBOMeta{

    public
            $total  = 0,
            $rows   = 0,
            $page   = 0,
            $pages  = 0,
            $offset = 0;

}
