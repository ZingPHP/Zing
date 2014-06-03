<?php

namespace Modules;

class User extends Module{

    /**
     * Creates a salt that you can save into your datatabse
     * @param string $password The password to generate a salt from
     * @return string
     */
    public function createPassword($password){
        $salt = hash("sha256", time() . uniqid() . rand(1, 1000));
        return crypt($password, $salt);
    }

    /**
     * Verifies that the password and salt can regenerate the salt
     * @param string $password The password a user gives such as from a form
     * @param string $salt The salt that was created from self::create()
     * @return boolean
     */
    public function verifyPassword($password, $salt){
        if(crypt($password, $salt) == $salt){
            return true;
        }
        return false;
    }

    /**
     * Sets session data for a particular user
     * @param array $data
     */
    public function login($data){
        foreach($data as $key => $val){
            $_SESSION[$key] = $val;
        }
        $_SESSION["ZingLoggedIn"] = true;
    }

    /**
     * Checks to see if a user is currently logged in
     * @return boolean
     */
    public function isLogged(){
        if(isset($_SESSION["ZingLoggedIn"]) && $_SESSION["ZingLoggedIn"]){
            return $_SESSION["ZingLoggedIn"];
        }
        return false;
    }

    /**
     * Forces user to be logged otherwise redirect to another page.
     * @param string $location
     */
    public function requireLogin($location){
        if(!$this->isLogged()){
            header("Location: $location");
            exit;
        }
    }

}
