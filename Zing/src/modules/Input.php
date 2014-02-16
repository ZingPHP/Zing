<?php

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
            return "";
        }
        if($nargs === 2){
            $_GET[$key] = $value;
            return true;
        }elseif($nargs === 1){
            return $_GET[$key];
        }else{
            return false;
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
            return "";
        }
        if($nargs === 2){
            $_POST[$key] = $value;
            return true;
        }elseif($nargs === 1){
            return $_POST[$key];
        }else{
            return false;
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
            return "";
        }
        if($nargs === 2){
            $_SERVER[$key] = $value;
            return true;
        }elseif($nargs === 1){
            return $_SERVER[$key];
        }else{
            return false;
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
            return "";
        }
        if($nargs === 2){
            setcookie($key, $value);
            return true;
        }elseif($nargs === 1){
            return $_COOKIE[$key];
        }else{
            return false;
        }
    }

}
