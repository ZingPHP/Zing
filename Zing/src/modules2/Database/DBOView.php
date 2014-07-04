<?php

namespace Modules\Database;

class DBOView extends \Modules\Database\DBOTable{

    public function __construct($table_name, $db, $config){
        if(!$this->_validName($table_name)){
            throw new \Exception("Invalid Table Name '$table_name'.");
        }
        $this->table = $table_name;
        parent::__construct($table_name, $db, $config);
    }

    public function getTable(\Modules\Database\DBOFilter $filter){

    }

}
