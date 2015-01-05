<?php

namespace Modules;

use Exception;
use Modules\Cache\APCache;
use Modules\Cache\FCache;
use Modules\Cache\Memcache;
use Modules\Module;

class Cache extends Module{

    const FCACHE   = 1;
    const MEMCACHE = 2;
    const APC      = 3;

    protected $cache        = null;
    protected $memcacheHost = "localhost";
    protected $memcachePort = 11211;

    /**
     * Sets the Caching engine to use. Valid Engines are:
     * FCACHE   = File Caching
     * MEMCACHE = MemCache
     * APC      = PHP's APC
     * @param int $cache_to_use
     * @return Cache
     * @throws \Exception
     */
    public function setEngine($cache_to_use = self::FCACHE){
        switch($cache_to_use){
            case self::FCACHE:
                $this->cache = new FCache($this->config);
                return $this->cache;
            case self::APC:
                if(!function_exists("apc_store")){
                    throw new Exception("APC is currently not installed or enabled.");
                }
                $this->cache = new APCache($this->config);
                return $this->cache;
            case self::MEMCACHE:
                if(!function_exists("memcache_connect")){
                    throw new Exception("Memcache is currently not installed or enabled.");
                }
                $this->cache = new Memcache($this->config);
                return $this->cache;
            default:
                throw new \Exception("Caching engine not supported.");
        }
        return $this;
    }

    /**
     * The data to cache
     * @param string $name
     * @param mixed $data
     */
    public function put($name, $data){
        $this->_setCacheEngine();
        $this->cache->put($name, $data);
    }

    /**
     * Tests the cache to see if it has expired
     * @param string $name
     * @param int $ttl
     * @return bool
     */
    public function isExpired($name, $ttl){
        $this->_setCacheEngine();
        return $this->cache->isExpired($name, $ttl);
    }

    /**
     * Gets an item from the cache
     * @param string $name
     * @return mixed
     */
    public function get($name){
        $this->_setCacheEngine();
        return $this->cache->get($name);
    }

    /**
     * Deletes an item or a list of items from the cache
     * @param string|array $name
     * @return Cache
     */
    public function delete($name){
        $this->_setCacheEngine();
        $name = $this->toArray($name);
        foreach($name as $cache_name){
            $this->cache->delete($cache_name);
        }
        return $this;
    }

    /**
     * Deletes everything that is cached
     * @return Cache
     */
    public function destroy(){
        $this->_setCacheEngine();
        $this->cache->destroyCache();
        return $this;
    }

    /**
     * Uses a callback to cache the returned data on a timed interval
     * @param string $name
     * @param int $ttl
     * @param callback $callback
     * @return mixed
     * @throws Exception
     */
    public function save($name, $ttl, $callback){
        $this->_setCacheEngine();
        if(!is_callable($callback)){
            throw new Exception("Paramater 3 must be a callable function.");
        }
        if($this->isExpired($name, $ttl)){
            $data = call_user_func($callback);
            $this->put($name, $data, $ttl);
        }
        return $this->get($name);
    }

    /**
     * sets the caching engine if one isn't set
     * @return void
     */
    protected function _setCacheEngine(){
        if($this->cache !== null){
            return;
        }
        $this->setEngine();
    }

}
