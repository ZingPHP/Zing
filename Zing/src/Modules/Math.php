<?php

use Modules\Math;
use Modules\Module;

namespace Modules;

class Math extends Module{

    const miles      = true;
    const kilometers = true;
// US
    const mile       = "mile";
    const yard       = "yard";
    const foot       = "foot";
    const inch       = "inch";
// Metric
    const kilometer  = "kilometer";
    const meter      = "meter";
    const centimeter = "centimeter";
    const milimeter  = "milimeter";

    /**
     * Calculates the area of a triangle
     * @param type $base
     * @param type $height
     * @return type
     */
    public function triangleArea($base, $height){
        return ($base * $height) / 2;
    }

    /**
     * Calculates the area of a rectangle/square
     * @param type $height
     * @param type $width
     * @return type
     */
    public function rectArea($height, $width){
        return $height * $width;
    }

    /**
     * Calculates the area of a circle
     * @param type $radius
     * @return type
     */
    public function circleArea($radius){
        return pi() * pow($radius, 2);
    }

    /**
     * Calculates the circumference of a circle
     * @param type $diameter
     * @return type
     */
    public function circleCircumference($diameter){
        return pi() * $diameter;
    }

    /**
     * Calculates the age of something based off its date of birth.
     * Note: Uses PHP set time zone, usually UTC.
     * @param date $dob
     * @return type
     */
    public function getAge($dob){
        $seconds_in_year = (24 * 60 * 60) * 365.25;
        return floor((time() - strtotime($dob)) / $seconds_in_year);
    }

    /**
     * Converts Bytes into textual size (GB, MB, KB)
     * @param int $bites
     * @param int $decimalPlaces
     * @return string
     */
    public function toSize($bites, $decimalPlaces = 2){
        if($bites >= 1073741824){
            $fileSize = round($bites / 1024 / 1024 / 1024, $decimalPlaces) . 'GB';
        }elseif($bites >= 1048576){
            $fileSize = round($bites / 1024 / 1024, $decimalPlaces) . 'MB';
        }elseif($bites >= 1024){
            $fileSize = round($bites / 1024, $decimalPlaces) . 'KB';
        }else{
            $fileSize = $bites . ' bytes';
        }
        return $fileSize;
    }

    /**
     *
     * @param float $lat1  Latitude of point A
     * @param float $long1 Longitude of point A
     * @param float $lat2  Latitude of point B
     * @param float $long2 Logitude of point B
     * @param float $type  Type of distance miles or kilometers
     * @return type
     */
    public function distance($lat1, $long1, $lat2, $long2, $type = Math::miles){
        $pi = pi();
        $x  = sin($lat1 * $pi / 180) *
                sin($lat2 * $pi / 180) +
                cos($lat1 * $pi / 180) *
                cos($lat2 * $pi / 180) *
                cos(($long2 * $pi / 180) - ($long1 * $pi / 180));
        $x  = atan((sqrt(1 - pow($x, 2))) / $x);
        if($type == Math::miles){
            $distance = abs((1.852 * 60.0 * (($x / $pi) * 180)) / 1.609344);
        }elseif($type == Math::kilometers){
            $distance = abs((1.852 * 60.0 * (($x / $pi) * 180)));
        }
        return $distance;
    }

    /**
     *
     * @param int $deposit Initial deposit
     * @param float $rate  Interest Rate
     * @param int $n       # of times per year interest is compounded
     * @param int $time    Number of years invested
     * @return type
     */
    public function compoundInterest($deposit, $rate, $n, $time){
        return $deposit * pow(1 + $rate / $n, $n * $time);
    }

    public function lerp(array $pointA, array $pointB, $time){
        $arr1 = array_replace(array(0, 0, 0), $pointA);
        $arr2 = array_replace(array(0, 0, 0), $pointB);
        $x    = $this->lerp2($arr1[0], $arr2[0], $time);
        $y    = $this->lerp2($arr1[1], $arr2[1], $time);
        $z    = $this->lerp2($arr1[2], $arr2[2], $time);
        return array($x, $y, $z);
    }

    public function lerp2($v0, $v1, $t){
        return $v0 + ($v1 - $v0) * $t;
    }

    public function toFoot($from, $amount){
        return $this->_distance($from, Math::foot, $amount);
    }

    protected function _distance($from, $to, $amount){
        $fromInches = $amount;
        $toInches   = $amount;
        if($from != Math::inch){
            $fromInches = $this->_toInch($from, $amount);
            $toInches   = $this->_toInch($to, $amount);
        }
        switch($to){
            case Math::inch:
                return $fromInches;
            case Math::foot:
                return $fromInches / 12;
            case Math::mile:
                return $fromInches / 63360;
            case Math::centimeter:
                return $fromInches / 2.54;
        }
    }

    protected function _toInch($type, $amount){
        switch($type){
            case Math::foot:
                return $amount * 12;
            case Math::yard:
                return $amount * 36;
            case Math::mile:
                return $amount * 63360;
            case Math::centimeter:
                return $amount / 2.54;
            case Math::meter:
                return $amount * 39.3701;
            case Math::kilometer:
                return $amount * 39370.1;
        }
    }

    protected function _convertInch($to, $amount){
        switch($to){
            case Math::foot:
                return $amount / 12;
            case Math::yard:
                return $amount / 36;
            case Math::mile:
                return $amount / 63360;
            case Math::centimeter:
                return $amount * 2.54;
            case Math::meter:
                return $amount * 39.3701;
            case Math::kilometer:
                return $amount * 39370.1;
        }
    }

}
