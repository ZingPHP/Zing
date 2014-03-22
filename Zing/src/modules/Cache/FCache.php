<?php

namespace Modules\Cache;

class FCache extends \Modules\Module implements \Modules\Cache\ICache{

    protected $root  = "";
    protected $cache = "/cache";
    protected $key   = "";
    protected $life  = 0;

    public function __construct($config = array()){
        parent::__construct($config);
        $this->root = __DIR__ . str_replace("//", "/", "/../../cache");
        if(!is_dir($this->root)){
            mkdir($this->root);
        }
    }

    /**
     * Test to see if a cache has expired.
     * @param int $ttl Time in seconds
     * @return boolean
     */
    public function isExpired($cache, $ttl){
        $this->life = $ttl;
        $file       = $this->root . "/$cache.cache.php";
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
    public function put($cache, $content, $ttl = 0){
        $file = $this->root . "/$cache.cache.php";
        if(!is_dir(dirname($this->root))){
            return false;
        }
        $this->delete($cache);
        $content = json_encode($content);
        return (bool)file_put_contents($file, "<?php exit; ?>" . $content);
    }

    /**
     * Gets the content from the current initialized cache.
     * @return mixed
     */
    public function get($cache){
        $file = $this->root . "/$cache.cache.php";
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
    public function delete($cache){
        $file = $this->root . "/$cache.cache.php";
        if(is_file($file)){
            return unlink($file);
        }
        return false;
    }

    public function destroy(){
        $files = glob($this->root . "/*.cache.php");
        foreach($files as $file){
            unlink($file);
        }
        return $this;
    }

}
