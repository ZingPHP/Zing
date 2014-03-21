<?php

namespace Modules;

class Util extends Module{

    /**
     * Tests whether or not the list of arguments are empty
     * @param mixed $args,...
     * @return boolean
     */
    public function isEmpty($args){
        $args = func_get_args();
        foreach($args as $arg){
            if(empty($arg)){
                return true;
            }
        }
        return false;
    }

    /**
     * Tests whether or not the list of arguments are blank
     * @param mixed $args,...
     * @return boolean
     */
    public function isBlank($args){
        $args = func_get_args();
        foreach($args as $arg){
            if($this->blank($arg)){
                return true;
            }
        }
        return false;
    }

    /**
     * Tests a value to see if it is blank. Blank values are void of valid
     * non-white space characters.
     * @param $value $string
     * @return boolean
     */
    public function blank($value){
        if(is_string($value)){
            $value = str_replace(array(" ", "\t", "\n", "\r"), "", $value);
        }
        return empty($value);
    }

}
