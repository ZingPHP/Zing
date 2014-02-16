<?php

class Password extends Module{

    /**
     * Creates a salt that you can save into your datatabse
     * @param string $password The password to generate a salt from
     * @return string
     */
    public function create($password){
        $salt = hash("sha256", time() . uniqid() . rand(1, 1000));
        return crypt($password, $salt);
    }

    /**
     * Verifies that the password and salt can regenerate the salt
     * @param string $password The password a user gives such as from a form
     * @param string $salt The salt that was created from self::create()
     * @return boolean
     */
    public function verify($password, $salt){
        if(crypt($password, $salt) == $salt){
            return true;
        }
        return false;
    }

}
