<?php

class Home extends Zing{

    public function main(){
        $cache = $this->cache->setCacheEngine(\Modules\Cache::APC)
                ->cache("test", 10, function(){
            echo "Rebuild Cache";
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
