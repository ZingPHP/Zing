<?php

namespace Modules\Cache;

use Modules\Cache,
    Modules\Cache\ICache;

class APCache extends Cache implements ICache{

    /**
     * Gets an item from APC Cache
     * @param string $name
     * @return mixed
     */
    public function get($name){
        return apc_fetch($name);
    }

    /**
     * Tests to see if an APC cache has expired
     * @param string $name
     * @param int $ttl
     * @return boolean
     */
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

    /**
     * Saves an item into APC cache
     * @param string $name
     * @param mixed $data
     */
    public function put($name, $data){
        apc_store($name, $data);
    }

    /**
     * Deletes everything from APC cache
     * @return bool
     */
    public function destroy(){
        return apc_clear_cache("user");
    }

    /**
     * Deletes an item from APC cache
     * @param string $name
     * @return bool
     */
    public function delete($name){
        return apc_delete($name);
    }

}
