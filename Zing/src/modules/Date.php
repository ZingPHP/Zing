<?php

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

}
