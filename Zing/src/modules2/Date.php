<?php

namespace Modules;

class Date extends Module{

    /**
     * Checks to see if a time overlaps a list of times
     * @param date $start_time
     * @param date $end_time
     * @param array $times
     * @return boolean
     */
    public function timeOverlap($start_time, $end_time, array $times){
        $ustart = strtotime($start_time);
        $uend   = strtotime($end_time);
        foreach($times as $time){
            $start = strtotime($time["start"]);
            $end   = strtotime($time["end"]);
            if($ustart <= $end && $uend >= $start){
                return true;
            }
        }
        return false;
    }

    /**
     * Takes two dates to find the number of day
     * @param date $date1
     * @param date $date2
     * @return int
     */
    public function dateDiff($date1, $date2){
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        return abs($time1 - $time2) / (24 * 60 * 60);
    }

    /**
     * Takes to dates and/or times to find the time difference
     * @param datetime $date1
     * @param datetime $date2
     * @return string
     */
    public function timeDiff($date1, $date2){
        $time1               = strtotime($date1);
        $time2               = strtotime($date2);
        $seconds             = abs($time1 - $time2);
        $hours               = floor($seconds / (60 * 60));
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes             = floor($divisor_for_minutes / 60);
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds             = ceil($divisor_for_seconds);
        return "$hours:$minutes:$seconds";
    }

    /**
     * Gets the number of seconds in a time
     * @param time $time
     * @return int
     */
    public function timeToSec($time){
        $times = array_replace(array(0, 0, 0), explode(":", $time));
        $th    = (int)$times[0] * 60 * 60;
        $tm    = (int)$times[1] * 60;
        return $th + $tm + (int)$times[2];
    }

    public function minToSec($minutes){
        return $minutes * 60;
    }

    /**
     * Tests to see if the date is a weekday (Monday - Friday)
     * @param date $date
     * @return boolean
     */
    public function isWeekday($date){
        $d = date('w', strtotime($date));
        return ($d != 6 && $d != 0) ? true : false;
    }

    /**
     * Tests to see if the date is a weekend (Saturday or Sunday)
     * @param date $date
     * @return boolean
     */
    public function isWeekend($date){
        $d = date('w', strtotime($date));
        return ($d == 6 || $d == 0) ? true : false;
    }

    /**
     * Returns a pretty fomatted date:
     * Sunday March 2nd 2014
     * @param date $date
     * @param string $format
     * @return string
     */
    public function prettyDate($date, $format = "l F jS Y"){
        $time = strtotime($date);
        return date($format, $time);
    }

}
