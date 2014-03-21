<?php

namespace Modules;

class FCache extends Module{

    protected $root  = "";
    protected $cache = "";
    protected $key   = "";
    protected $life  = 0;

    /**
     * Initialize a cache to use. The cache doesn't need to exist to be initialized.
     * If the cache directory is not found, it will be created.
     * @param string $key
     * @param string $root
     * @return self
     */
    public function init($key, $root = "/cache"){
        $this->root = __DIR__ . str_replace("//", "/", "/../../$root");
        if(!is_dir($this->root)){
            mkdir($this->root);
        }
        $this->key = $key;
        return $this;
    }

    /**
     * Update the cache if the time period has passed
     * @param int $life_span Time in seconds
     * @param callback $callback
     * @return type
     * @throws Exception
     */
    public function cache($life_span, $callback){
        if(!is_callable($callback)){
            throw new Exception("Parameter 2 must be a callable function");
        }
        if($this->isExpired($life_span)){
            $data = call_user_func($callback);
            $this->put($data);
        }
        return $this->get();
    }

    /**
     * Test to see if a cache has expired.
     * @param int $life_span Time in seconds
     * @return boolean
     */
    public function isExpired($life_span){
        $this->life = $life_span;
        $file       = $this->root . "/" . $this->key . ".cache.php";
        if(is_file($file)){
            $mtime = filemtime($file);
            return (time() >= ($mtime + $this->life));
        }else{
            return true;
        }
    }

    /**
     * Set the content in the initialized cache replacing the old cache.
     * @param mixed $content
     * @return boolean
     */
    public function put($content){
        $file = $this->root . "/" . $this->key . ".cache.php";
        if(!is_dir(dirname($this->root))){
            return false;
        }
        $this->delete();
        $content = json_encode($content);
        return (bool)file_put_contents($file, "<?php exit; ?>" . $content);
    }

    /**
     * Gets the content from the current initialized cache.
     * @return mixed
     */
    public function get(){
        $file = $this->root . "/" . $this->key . ".cache.php";
        if(is_file($file)){
            $content = preg_replace("/^<\?php exit; \?>/i", "", file_get_contents($file), 1);
            return json_decode($content, true);
        }
        return array();
    }

    /**
     * Deletes the current initialized cache.
     * @return boolean
     */
    public function delete(){
        $file = $this->root . "/" . $this->key . ".cache.php";
        if(is_file($file)){
            return unlink($file);
        }
        return false;
    }

    /**
     * Gets the name of the current initialized cache.
     * @return string
     */
    public function getCacheName(){
        return $this->key;
    }

}
