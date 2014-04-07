<?php

class Test extends Zing{

    public function main(){
        $fcach = $this->cache->setEngine();

        $this->cache->cache("test1", 1, function(){
            echo "<p>Caching 1 seconds</p>";
            return "hi";
        });
        $fcach->cache("test5", 5, function(){
            echo "<p>Caching 5 seconds</p>";
            return "hi";
        });
        $fcach->cache("test10", 10, function(){
            echo "<p>Caching 10 seconds</p>";
            return "hi";
        });
    }

}
