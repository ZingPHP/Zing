<?php

namespace Modules;

class Cache extends \Modules\Module{

    const FCACHE   = 1;
    const MEMCACHE = 2;
    const APC      = 3;

    protected $cache = null;

    /**
     * Sets the Caching engine to use. Valid Engines are:
     * FCACHE   = File Caching
     * MEMCACHE = MemCache
     * APC      = PHP's APC
     * @param int $cache_to_use
     * @return \Modules\Cache
     * @throws \Exception
     */
    public function setEngine($cache_to_use = self::FCACHE){
        switch($cache_to_use){
            case self::FCACHE:
                $this->cache = new \Modules\Cache\FCache();
                break;
            case self::APC:
                if(!function_exists("apc_store")){
                    throw new \Exception("APC is currently not installed or enabled.");
                }
                $this->cache = new \Modules\Cache\APCache();
                break;
            default:
                throw new \Exception("Caching engine not supported.");
        }
        return $this;
    }

    public function put($name, $data){
        $this->_setCacheEngine();
        $this->cache->put($name, $data);
    }

    public function isExpired($name, $ttl){
        $this->_setCacheEngine();
        return $this->cache->isExpired($name, $ttl);
    }

    public function get($name){
        $this->_setCacheEngine();
        return $this->cache->get($name);
    }

    public function delete($name){
        $this->_setCacheEngine();
        $name = $this->toArray($name);
        foreach($name as $cache_name){
            $this->cache->delete($cache_name);
        }
        return $this;
    }

    public function destroy(){
        $this->_setCacheEngine();
        $this->cache->destroyCache();
        return $this;
    }

    public function cache($name, $ttl, $callback){
        $this->_setCacheEngine();
        if(!is_callable($callback)){
            throw new \Exception("Paramater 3 must be a callable function.");
        }
        if($this->isExpired($name, $ttl)){
            $data = call_user_func($callback);
            $this->put($name, $data, $ttl);
        }
        return $this->get($name);
    }

    protected function _setCacheEngine(){
        if($this->cache !== null){
            return;
        }
        $this->setCacheEngine();
    }

}
