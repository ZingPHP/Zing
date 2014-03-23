<?php

namespace Modules;

class Module{

    public $config    = array();
    public $string    = "";
    public $int       = 0;
    public $namespace = "";

    public function __construct($config = array()){
        $this->config = $config;
        $namespace    = get_called_class();
    }

    public function __toString(){
        return $this->string;
    }

    public function getString(){
        return $this->string;
    }

    /**
     * Sets a default string to the string property
     * it can then be returned in a module.
     * @param string $default
     * @return \Module
     */
    public function defaultString($default = ""){
        $this->string = (string)$default;
        return $this;
    }

    /**
     * Sets a default integer to the int property
     * it can then be returned in a module.
     * @param int $int
     * @return \Module
     */
    public function defaultInt($int){
        $this->int = (int)$int;
        return $this;
    }

    public function replace($find, $replace){
        str_replace($find, $replace, $this->string);
        return $this;
    }

}
