<?php

namespace Widgets\Calendar;

class Calendar extends \Widgets\Widget implements \Widgets\IWidget{

    public function setDefaultOptions(){
        return array(
            "day" => "short"
        );
    }

    public function runWidget(){
        $this->build();
    }

    protected function build(){
        $days = date("t");
        $cday = date("d");
        $str  = "<table class='zing-calendar'>";
        $str .= $this->buildTitles();
        $str .= $this->buildOffset();
        $str .= $this->buildDays();
        $str .= "</table>";

        $this->html = $str;
        return true;
    }

    protected function buildTitles(){
        $names = array(
            array("Sun", "Sunday"),
            array("Mon", "Monday"),
            array("Tue", "Tuesday"),
            array("Wed", "Wednesday"),
            array("Thu", "Thursday"),
            array("Fri", "Friday"),
            array("Sat", "Saturday")
        );
        $str   = "\n\t<tr>";
        $key   = $this->settings["day"] == "short" ? 0 : 1;
        for($i = 0; $i < 7; $i++){
            $str .= "\n\t\t<th>" . $names[$i][$key] . "</th>";
        }
        $str .= "\n\t</tr>";
        return $str;
    }

    protected function buildOffset(){
        $fday = date("w", strtotime("first day of this month"));
        $str  = "\n\t<tr>";
        for($i = 0; $i < $fday; $i++){
            $str .= "\n\t\t<td></td>";
        }
        return $str;
    }

    protected function buildDays(){
        $fday   = date("w", strtotime("first day of this month"));
        $days   = date("t");
        $cday   = (int)date("d");
        $str    = "";
        $offset = $fday - 1;
        for($i = 1; $i <= $days; $i++){
            if(($i + $offset) % 7 === 0){
                $str .= "\n\t</tr>\n\t<tr>";
            }
            $style = $cday == $i ? "selected" : "";
            $str .= "\n\t\t<td class='$style'>$i</td>";
        }
        $str .= "\n\t</tr>\n";
        return $str;
    }

}
