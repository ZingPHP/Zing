<?php

namespace Modules\Database;

use Modules\Database\DBOFilter;

class DBOFilter{

    protected $filter = array(
        "limitStart" => 0,
        "limit"      => 1000,
        "eq"         => array(),
        "neq"        => array(),
        "gt"         => array(),
        "lt"         => array(),
        "between"    => array(),
        "contains"   => array(),
    );

    /**
     * Sets the filters row limit return
     * @param int $limit Number of results to return
     * @param int $limitStart Limit offset
     * @return DBOFilter
     */
    public function setLimit($limit, $limitStart = 0){
        $this->filter["limit"]      = (int)$limit;
        $this->filter["limitStart"] = (int)$limitStart;
        return $this;
    }

    /**
     * Sets values where x = y
     * @param array $filter Settings to set
     * @return DBOFilter
     */
    public function setEq(array $filter){
        $this->filter["eq"] = array_merge($this->filter["eq"], $filter);
        return $this;
    }

    /**
     * Sets values where x != y
     * @param array $filter Settings to set
     * @return DBOFilter
     */
    public function setNotEq(array $filter){
        $this->filter["neq"] = array_merge($this->filter["neq"], $filter);
        return $this;
    }

    /**
     * Sets values where x > y
     * @param array $filter Settings to set
     * @return DBOFilter
     */
    public function setGt(array $filter){
        $this->filter["gt"] = array_merge($this->filter["gt"], $filter);
        return $this;
    }

    /**
     * Sets values where x < y
     * @param array $filter Settings to set
     * @return DBOFilter
     */
    public function setLt(array $filter){
        $this->filter["lt"] = array_merge($this->filter["lt"], $filter);
        return $this;
    }

    /**
     * Sets values where x between y and z
     * @param array $filter Settings to set
     * @return DBOFilter
     */
    public function setBetween(array $filter){
        $this->filter["between"] = array_merge($this->filter["between"], $filter);
        return $this;
    }

    /**
     * Sets values where x like '%y%'
     * @param array $filter Settings to set
     * @return DBOFilter
     */
    public function setContains(array $filter){
        $this->filter["contains"] = array_merge($this->filter["contains"], $filter);
        return $this;
    }

    public function getFilter(){
        return $this->filter;
    }

}
