<?php

namespace Modules\Cache;

class MemCache extends \Modules\Module implements \Modules\Cache\ICache{

    protected $host     = "localhost";
    protected $port     = 11211;
    protected $memcache = null;

    public function connect(){
        if($this->memcache !== null){
            return;
        }
        if(isset($this->config["memcache"]["host"])){
            $this->host = $this->config["memcache"]["host"];
        }
        if(isset($this->config["memcache"]["port"])){
            $this->port = $this->config["memcache"]["port"];
        }
        $this->memcache = new \Memcache();
        $this->memcache->connect($this->host, $this->port);
    }

    /**
     * Gets an item from APC Cache
     * @param string $name
     * @return mixed
     */
    public function get($name){
        $this->connect();
        $data = $this->memcache->get($name);
        return $data["data"];
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
        $cache = $this->memcache->get($name);
        $time  = $cache["time"];
        return (time() >= ($time + $ttl));
    }

    /**
     * Saves an item into APC cache
     * @param string $name
     * @param mixed $data
     */
    public function put($name, $data){
        $this->connect();
        $data = array(
            "time" => time(),
            "data" => $data
        );
        $this->memcache->set($name, $data, MEMCACHE_COMPRESSED);
    }

    /**
     * Deletes everything from APC cache
     * @return bool
     */
    public function destroy(){
        $this->connect();
        return $this->memcache->flush();
    }

    /**
     * Deletes an item from APC cache
     * @param string $name
     * @return bool
     */
    public function delete($name){
        $this->connect();
        return $this->memcache->delete($name);
    }

}
