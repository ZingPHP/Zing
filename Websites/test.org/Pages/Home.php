<?php

class Home extends Zing{

    public function main(){
        echo <<<STYLE
        <style>
        table{
            border-collapse: collapse;
            border: solid 1px #000000;
        }
        table td{
            height: 32px;
            width: 32px;
            border: solid 1px #000000;
            cursor: default;
            text-align: center;
            vertical-align: middle;
        }
        table td:hover{
            cursor: -webkit-zoom-in;
            cursor: -moz-zoom-in;;
        }
        table td.city-hall{
           background-image: url(/Websites/test.org/images/color.php?color=%239999FF);
        }
        table td.none:hover{
            background-image: url(/Websites/test.org/images/color.php?color=%23000000&alpha=0.2), url(/Websites/test.org/images/color.php?color=%23FFFFFF);
        }
        table td.city-hall:hover{
            background-image: url(/Websites/test.org/images/color.php?color=%23000000&alpha=0.2), url(/Websites/test.org/images/color.php?color=%239999FF);
        }
        </style>
STYLE;
        echo "<table>";
        $chi = rand(0, 29);
        $chj = rand(0, 29);
        for($i = 0; $i < 30; $i++){
            echo "<tr>";
            for($j = 0; $j < 30; $j++){
                $color = ($chi == $i && $chj == $j) ? "city-hall" : "none";
                $color = urlencode($color);
                $text  = ($chi == $i && $chj == $j) ? "CH" : "";
                $title = ($chi == $i && $chj == $j) ? "City Hall" : "";
                echo "<td title='$title' class='$color'>$text</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

}
