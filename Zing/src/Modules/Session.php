<?php

namespace Modules;

class Session extends Module{

    public function forceTo($key, $value, $location){
        if(!isset($_SESSION[$key]) || $_SESSION[$key] !== $value){
            header("Location: $location");
            exit;
        }
    }

    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return $default;
    }

    public function delete($key){
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }

    public function destroy($key = null){
        if($key === null){
            $_SESSION = null;
            session_destroy();
        }else{
            unset($_SESSION[$key]);
        }
    }

}
