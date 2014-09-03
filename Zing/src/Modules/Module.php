<?php

namespace Modules;

use Iterator;

class Module implements Iterator{

    public $config = array();

    public function __construct($config = array()){
        $this->config = $config;
    }

    public function __toString(){
        return ModuleShare::$string;
    }

    final public function getString(){
        return ModuleShare::$string;
    }

    /**
     * Sets a default string to the string property
     * it can then be returned in a module.
     * @param string $default
     * @return \Module
     */
    final public function defaultString($default = ""){
        ModuleShare::$string = (string)$default;
        return $this;
    }

    /**
     * Sets a default integer to the int property
     * it can then be returned in a module.
     * @param int $int
     * @return \Module
     */
    final public function defaultInt($int){
        ModuleShare::$int = (int)$int;
        return $this;
    }

    final public function replace($find, $replace){
        str_replace($find, $replace, ModuleShare::$string);
        return $this;
    }

    /**
     * Converts a value into an array unless it is already an array.
     * @param mixed $value
     * @return array
     */
    final public function toArray($value = null){
        $nargs = func_num_args();
        if($nargs === 0){
            return ModuleShare::$array;
        }
        if(is_array($value)){
            return $value;
        }
        return array($value);
    }

    final public function each($callback){
        foreach(ModuleShare::$array as $key => $value){
            call_user_func_array($callback, array($value, $key));
        }
        return $this;
    }

    /**
     * Sets the shared array
     * @param mixed $array
     */
    final public function setArray($array){
        $array = $this->toArray($array);
        ModuleShare::$array = $array;
    }

    //
    // Begin Iterator methods
    //
    final public function rewind(){
        ModuleShare::$position = 0;
    }

    final public function current(){
        return ModuleShare::$array[ModuleShare::$position];
    }

    final public function key(){
        return ModuleShare::position;
    }

    final public function next(){
        ++ModuleShare::$position;
    }

    final public function valid(){
        return isset(ModuleShare::$array[ModuleShare::$position]);
    }

}

class ModuleShare{

    public static $array    = array();
    public static $position = 0;
    public static $string   = "";
    public static $int      = 0;

}
