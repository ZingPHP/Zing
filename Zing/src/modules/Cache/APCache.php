<?php

namespace Modules\Cache;

class APCache extends \Modules\Module implements \Modules\Cache\ICache{

    public function get($name){
        return apc_fetch($name);
    }

    public function isExpired($name, $ttl){
        if($ttl === null){
            return false;
        }
        $info = apc_cache_info("user");
        $time = time();
        foreach($info["cache_list"] as $value){
            if($value["info"] === $name){
                return (bool)(($time - $value["creation_time"]) >= $ttl);
            }
        }
    }

    public function put($name, $data){
        apc_store($name, $data);
    }

    public function destroy(){
        apc_clear_cache("user");
    }

    public function delete($name){
        return apc_delete($name);
    }

}
