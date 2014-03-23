<?php

namespace Modules;

class Validate extends Module{

    /**
     * Tests a string to see if it is formatted as a valid email address.
     * @param string $email
     * @return boolean
     */
    public function isEmail($email){
        return $this->_filterVar($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Tests a string to see if it is formatted as a vaild ip address.
     * @param string $ip
     * @return boolean
     */
    public function isIP($ip){
        return $this->_filterVar($ip, FILTER_VALIDATE_IP);
    }

    /**
     * Tests a string to see if it is formatted as a valid url.
     * @param string $url
     * @return boolean
     */
    public function isURL($url){
        return $this->_filterVar($url, FILTER_VALIDATE_URL);
    }

    /**
     * Tests a string to see if it is formatted as a valid username.
     * @param string $username
     * @return boolean
     */
    public function isUserName($username){
        return !preg_match("/[^a-zA-Z0-9_]/", $username);
    }

    /**
     * Tests a string to see if it is formatted as a valid database or table name.
     * @param string $name
     * @return boolean
     */
    public function isTableName($name){
        return !preg_match("/[^a-zA-Z0-9\$_]/", $name);
    }

    /**
     * Tests a string to see if matches the required format.
     * For example: (###) ###-####
     * Will require
     *     - open parentheses
     *     - 3 digits
     *     - close parentheses
     *     - space
     *     - 3 digits
     *     - dash
     *     - 4 digits
     *
     * Special Characters:
     * "." = Any character
     * "#" = A digit character
     * "@" = An alpha character
     * "&" = A digit or alpha character
     * @param string $input
     * @param string $format
     * @return boolean
     */
    public function isFormat($input, $format){
        $pattern = str_split(trim($format));
        $split   = str_split(trim($input));
        if(count($pattern) != count($split)){
            return false;
        }
        foreach($pattern as $pk => $pv){
            if($pv === "." && isset($split[$pk])){
                continue;
            }elseif($pv === "#" && ctype_digit($split[$pk])){
                continue;
            }elseif($pv === "@" && ctype_alpha($split[$pk])){
                continue;
            }elseif($pv === "&" && ctype_alnum($split[$pk])){
                continue;
            }elseif($pv === $split[$pk]){
                continue;
            }else{
                return false;
            }
        }
        return true;
    }

    protected function _filterVar($string, $value){
        return filter_var($string, $value);
    }

}
