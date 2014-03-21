<?php

class Home extends Zing{

    public function main(){
        $this->fcache->init("test");

        $this->fcache->cache(10, function(){
            //echo "<p><b>Running Cache</b></p>";
            return array(
                "hello",
                "how",
                "are",
                "you"
            );
        });
        var_dump($this->fcache->get());
    }

}
