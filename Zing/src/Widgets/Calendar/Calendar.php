<?php

namespace Widgets\Calendar;

use Widgets\Widget;

class Calendar extends Widget{

    public function setDefaultSettings(){
        return array(
            "day"      => "short",
            "zerofill" => false,
            "link"     => ""
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
        switch(strtolower($this->settings["day"])){
            case "short":
                $key = 0;
                break;
            case "full":
                $key = 1;
                break;
            default:
                $key = 0;
                break;
        }
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
        $fday       = date("w", strtotime("first day of this month"));
        $days       = date("t");
        $cday       = (int)date("d");
        $str        = "";
        $offset     = $fday - 1;
        $year       = date("Y");
        $year_short = date("y");
        $month      = date("m");
        for($i = 1; $i <= $days; $i++){
            if(($i + $offset) % 7 === 0){
                $str .= "\n\t</tr>\n\t<tr>";
            }
            $style    = $cday == $i ? "selected" : "";
            $padded   = str_pad((string)$i, 2, "0", STR_PAD_LEFT);
            $dspl     = (bool)$this->settings["zerofill"] ? $padded : $i;
            $unixtime = mktime("00", "00", "00", date("n"), $padded, $year);
            if(!empty($this->settings["link"])){
                $link = str_replace("%Y", $year, $this->settings["link"]);
                $link = str_replace("%y", $year_short, $link);
                $link = str_replace("%m", $month, $link);
                $link = str_replace("%d", $dspl, $link);
                $link = str_replace("%x", $unixtime, $link);
                $dspl = "<a href=\"$link\">$dspl</a>";
            }
            $str .= "\n\t\t<td class='$style'>$dspl</td>";
        }
        $str .= "\n\t</tr>\n";
        return $str;
    }

}
