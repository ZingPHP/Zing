<?php

class Module{

    public $config = array();
    public $string = "";
    public $int    = 0;

    public function __construct($config = array()){
        $this->config = $config;
    }

    public function __toString(){
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

}
