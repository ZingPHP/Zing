<h2>Cache</h2>
<p>
    The Cache module allows Zing to cache data to a caching engine.
</p>
<div class="toc">
    <ul>
        <li><a href="#cache()">Cache::cache()</a></li>
        <li><a href="#delete()">Cache::delete()</a></li>
        <li><a href="#destroy()">Cache::destroy()</a></li>
        <li><a href="#get()">Cache::get()</a></li>
        <li><a href="#isExpired()">Cache::isExpired()</a></li>
        <li><a href="#put()">Cache::put()</a></li>
        <li><a href="#setEngine()">Cache::setEngine()</a></li>
    </ul>
</div>
<hr />
<a name="cache()"></a>
<h3>cache()</h3>
<p>
    Saves data to the cache
</p>
<div class="toc">
    <p>
        <span>string $name</span>
        <span>The name of the cache</span>
    </p>
    <p>
        <span>int $ttl</span>
        <span>The time in seconds for the cache to live for</span>
    </p>
    <p>
        <span>callback $callback</span>
        <span>The callback to run to build the cache</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Set the caching engine to File Cache
        $fcache = $this->cache->setEngine();

        // Cache data every 120 seconds
        $data = $fcache->cache("test_cache", 120, function(){
            $array = array();
            $array[] = rand(1, 100);
            $array[] = rand(1, 100);
            return $array
        });

        // Dump the data that is in the cache
        var_dump($data);
    }

}
</pre>
<hr />
<a name="delete()"></a>
<h3>delete()</h3>
<p>
    Deletes an item from the cache
</p>
<div class="toc">
    <p>
        <span>string $name</span>
        <span>The name of the cache</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Set the caching engine to File Cache
        $fcache = $this->cache->setEngine();

        // Delete the data from the cache
        $fcache->delete("test_cache");
    }

}
</pre>
<hr />
<a name="destroy()"></a>
<h3>destroy()</h3>
<p>
    Destroys the entire cache
</p>
<div class="toc">
    <p>
        void
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Set the caching engine to File Cache
        $fcache = $this->cache->setEngine();

        // Destroy the entire cache
        $fcache->destory();
    }

}
</pre>
<hr />
<a name="get()"></a>
<h3>get()</h3>
<p>
    Gets an item from the cache
</p>
<div class="toc">
    <p>
        <span>string $name</span>
        <span>The name of the cache</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Set the caching engine to File Cache
        $fcache = $this->cache->setEngine();

        // Gets data from the cache
        $data = $fcache->get("test_cache");
        var_dump($data);
    }

}
</pre>
<hr />
<a name="isExpired()"></a>
<h3>isExpired()</h3>
<p>
    Tests to see if the cache is expired
</p>
<div class="toc">
    <p>
        <span>string $name</span>
        <span>The name of the cache</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Set the caching engine to File Cache
        $fcache = $this->cache->setEngine();

        // Tests the cache to see if it is expired
        if($fcache->isExpired("test_cache")){
            echo "I am an expired cache.";
        }else{
            echo "I have not expired yet!";
        }
    }

}
</pre>
<hr />
<a name="put()"></a>
<h3>put()</h3>
<p>
    Puts an item into the cache
</p>
<div class="toc">
    <p>
        <span>string $name</span>
        <span>The name of the cache</span>
    </p>
    <p>
        <span>mixed $data</span>
        <span>The data to save into the cache</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Set the caching engine to File Cache
        $fcache = $this->cache->setEngine();

        // Puts data into the cache
        $fcache->put("test_cache", array("this", "is", "data"));
    }

}
</pre>
<hr />
<a name="setEngine()"></a>
<h3>setEngine()</h3>
<p>
    Sets the engine to use to store data
</p>
<div class="toc">
    <p>
        <span>int $cache_to_use = self::FCACHE</span>
        <span>The name of the caching engine to use</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Set the caching engine to File Cache
        $fcache   = $this->cache->setEngine(Cache::FCACHE);
        // Set the caching engine to APC Cache
        $apc      = $this->cache->setEngine(Cache::APC);
        // Set the caching engine to MemCache
        $memcache = $this->cache->setEngine(Cache::MEMCACHE);
    }

}
</pre>