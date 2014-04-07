<?php

namespace Modules;

class Input extends Module{

    /**
     * Get or Set session variables
     * @param string $key    The key to the session variable
     * @param mixed $value   The value for the key
     * @return boolean
     */
    public function session($key, $value = null){
        $nargs = func_num_args();
        if(!isset($_SESSION[$key]) && $nargs === 1){
            return "";
        }
        if($nargs === 2){
            $_SESSION[$key] = $value;
            return true;
        }elseif($nargs === 1){
            return $_SESSION[$key];
        }else{
            return false;
        }
    }

    /**
     * Get or Set GET data
     * @param string $key   The key to the get variable
     * @param mixed $value  The value for the key
     * @return boolean
     */
    public function get($key, $value = null){
        $nargs = func_num_args();
        if(!isset($_GET[$key]) && $nargs === 1){
            return ModuleShare::$string;
        }
        if($nargs === 2){
            $_GET[$key] = $value;
            return true;
        }elseif($nargs === 1 && isset($_GET[$key])){
            return $_GET[$key];
        }else{
            return ModuleShare::$string;
        }
    }

    /**
     * Get or Set POST data
     * @param string $key   The key to the post variable
     * @param mixed $value  The value for the key
     * @return boolean
     */
    public function post($key, $value = null){
        $nargs = func_num_args();
        if(!isset($_POST[$key]) && $nargs === 1){
            return ModuleShare::$string;
        }
        if($nargs === 2){
            $_POST[$key] = $value;
            return true;
        }elseif($nargs === 1){
            return $_POST[$key];
        }else{
            return ModuleShare::$string;
        }
    }

    /**
     * Get or Set SERVER data
     * @param string $key   The key to the server variable
     * @param mixed $value  The value for the key
     * @return boolean
     */
    public function server($key, $value = null){
        $nargs = func_num_args();
        if(!isset($_SERVER[$key]) && $nargs === 1){
            return ModuleShare::$string;
        }
        if($nargs === 2){
            $_SERVER[$key] = $value;
            return true;
        }elseif($nargs === 1){
            return $_SERVER[$key];
        }else{
            return ModuleShare::$string;
        }
    }

    /**
     * Get or Set COOKIE data
     * @param string $key   The key to the cookie variable
     * @param mixed $value  The value for the key
     * @return boolean
     */
    public function cookie($key, $value = null){
        $nargs = func_num_args();
        if(!isset($_COOKIE[$key]) && $nargs === 1){
            return ModuleShare::$string;
        }
        if($nargs === 2){
            setcookie($key, $value);
            return true;
        }elseif($nargs === 1){
            return $_COOKIE[$key];
        }else{
            return ModuleShare::$string;
        }
    }

    /**
     * Gets the type of data passed in, this is similar to gettype()
     * only more types of values can be returned.
     * @param mixed $input
     * @return string
     */
    public function typeof($input){
        if(filter_var($input, FILTER_VALIDATE_EMAIL)){
            return "email";
        }
        if(filter_var($input, FILTER_VALIDATE_IP)){
            return "ip";
        }
        if(filter_var($input, FILTER_VALIDATE_URL)){
            return "url";
        }
        return gettype($input);
    }

    /**
     * Tests to see if a value is an email
     * @param string $input
     * @return boolean
     */
    public function isEmail($input){
        return $this->typeof($input) == "email" ? true : false;
    }

    /**
     * Tests to see if a value is an ip address
     * @param string $input
     * @return boolean
     */
    public function isIP($input){
        return $this->typeof($input) == "ip" ? true : false;
    }

    /**
     * Tests to see if a value is a URL
     * @param string $input
     * @return boolean
     */
    public function isURL($input){
        return $this->typeof($input) == "url" ? true : false;
    }

}
