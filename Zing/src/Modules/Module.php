<?php

namespace Modules;

use Iterator;
use Zing;

class Module extends Zing implements Iterator{

    public $config = array();

    public function __construct($config = array()){
        $this->config = $config;
    }

    /**
     * Creates an instance of the current object
     * @return mixed
     */
    public function getInstance(){
        return new $this();
    }

    public function __toString(){
        return ModuleShare::$string;
    }

    /**
     * Gets the current local string
     * @return string
     */
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

    /**
     * Loops through the local array
     * @param function $callback
     * @return Module
     */
    final public function each($callback){
        foreach(ModuleShare::$array as $key => $value){
            call_user_func_array($callback, array($value, $key));
        }
        return $this;
    }

    /**
     * Sets the local array
     * @param mixed $array
     */
    final public function setArray($array){
        $array              = $this->toArray($array);
        ModuleShare::$array = $array;
    }

    /**
     * Interchanges each row and the corresponding column.
     * @param bool $apply
     * @return array
     */
    final public function transpose(){
        $array = ModuleShare::$array;
        if(!isset($array[0]) || !is_array($array[0])){
            return;
        }
        $newArray = array();
        foreach($array as $arr){
            foreach($arr as $key => $val){
                if(!array_key_exists($key, $newArray)){
                    $newArray[$key] = array();
                }
                $newArray[$key][] = $val;
            }
        }
        $this->setArray($newArray);
        return $this;
    }

    /**
     * Picks values from a particular key
     * @param string $column
     * @return array
     */
    final public function pick($column){
        if(!isset(ModuleShare::$array[0]) || !is_array(ModuleShare::$array[0])){
            return;
        }
        $array = array();
        foreach(ModuleShare::$array as $arr){
            foreach($arr as $key => $val){
                if($key === $column){
                    $array[] = $val;
                }
            }
        }
        return $array;
    }

    /**
     * Select one item for the key and one item for the value from a multidimensional array
     * @param mixed $key
     * @param mixed $value
     * @return type
     */
    final public function keyVal($key, $value){
        if(!isset(ModuleShare::$array[0]) || !is_array(ModuleShare::$array[0])){
            return;
        }
        $array = array();
        foreach(ModuleShare::$array as $arr){
            if(isset($arr[$key]) && isset($arr[$value])){
                $array[$arr[$key]] = $arr[$value];
            }
        }
        return $array;
    }

    /**
     * Sorts a multidimetional array by column
     * @param string $column    The column to use for sorting
     * @param string $direction The direction to sort (asc|desc)
     * @return Module
     */
    final public function sort($column, $direction = "asc"){
        $array = ModuleShare::$array;
        if(!isset($array[0]) || !is_array($array[0])){
            return;
        }
        uasort($array, function($a, $b) use ($column, $direction){
            if($direction == "asc" || !in_array($direction, array("asc", "desc"))){
                return strnatcmp($a[$column], $b[$column]);
            }else{
                return strnatcmp($b[$column], $a[$column]);
            }
        });
        $this->setArray($array);
        return $this;
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
