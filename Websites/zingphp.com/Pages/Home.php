<?php

class Home extends Zing{

    public function main(){
        $cache = $this->fcache->init("test")->cache(10, function(){
            echo "<p><b>Running Cache</b></p>";
            return array(
                "hello",
                "how",
                "are",
                "you"
            );
        });
        var_dump($cache);
    }

}
