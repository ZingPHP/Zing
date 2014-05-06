<h2>Module <small>implements Iterator</small></h2>
<p>
    The Module class is the root class for all modules. All modules should extend
    this class in order to work properly.
</p>
<div class="toc">
    <ul>
        <li><a href="">Module::__construct()</a></li>
        <li><a href="">Module::__toString()</a></li>
        <li><a href="">Module::current()</a></li>
        <li><a href="">Module::defaultInt()</a></li>
        <li><a href="">Module::defaultString()</a></li>
        <li><a href="">Module::each()</a></li>
        <li><a href="">Module::getString()</a></li>
        <li><a href="">Module::key()</a></li>
        <li><a href="">Module::next()</a></li>
        <li><a href="">Module::replace()</a></li>
        <li><a href="">Module::rewind()</a></li>
        <li><a href="">Module::setArray()</a></li>
        <li><a href="">Module::toArray()</a></li>
        <li><a href="">Module::valid()</a></li>
    </ul>
</div>
<hr />
<h3>__construct()</h3>
<p>
    Loads the config when initializing a module (Zing does this automatically)
</p>
<div class="toc">
    <p>
        <span>array $config</span>
        <span>The config for the website</span>
    </p>
</div>
<hr />
<h3>__toString()</h3>
<p>
    Gets internal string when object is echo'd/printed (set by <a href="#defaultString()">defaultString()</a>)
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<hr />
<h3>current() <small>implemented by Iterator</small></h3>
<p>
    Gets the current position of the internal array
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<hr />
<h3>defaultInt()</h3>
<p>
    Sets the default int value
</p>
<div class="toc">
    <p>
        <span>int $int</span>
        <span>The default int value</span>
    </p>
</div>
<hr />
<h3>defaultString()</h3>
<p>
    Sets the default string value
</p>
<div class="toc">
    <p>
        <span>string $string</span>
        <span>The default string value</span>
    </p>
</div>
<hr />
<h3>each()</h3>
<p>
    Loops through the internal array.
</p>
<div class="toc">
    <p>
        <span>callback $callback</span>
        <span>The function to call for each item <code>myFunc($value, $key)</code></span>
    </p>
</div>
{literal}
<pre>
class MyPage extends Zing{

    public function main(){
        $db = $this->getDbo("localhost");

        $db->getAll("select * from my_table limit 100")
           ->each(function($row){
                echo "&lt;p&gt;{$row['col1']} -> {$row['col2']}&lt;/p&gt;";
            });
    }

}
</pre>
{/literal}
<hr />
<h3>getString()</h3>
<p>
    Gets the internal string (set by <a href="#defaultString()">defaultString()</a>)
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<hr />
<h3>key() <small>implemented by Iterator</small></h3>
<p>
    Gets the current internal key position
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<hr />
<h3>next() <small>implemented by Iterator</small></h3>
<p>
    Increments the internal key position
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<hr />
<h3>replace()</h3>
<p>
    Basic <a href="http://php.net/str_replace">str_replace</a> on the internal string
</p>
<div class="toc">
    <p>
        <span>string $find</span>
        <span>What to find in the string</span>
    </p>
    <p>
        <span>string $replace</span>
        <span>The replacement</span>
    </p>
</div>
<hr />
<h3>rewind() <small>implemented by Iterator</small></h3>
<p>
    Sets the internal array position to 0
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<hr />
<h3>setArray()</h3>
<p>
    Sets the internal array after <a href="#toArray()">toArray()</a>
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<hr />
<h3>toArray()</h3>
<p>
    Converts a value to an array unless the value is already an array
</p>
<div class="toc">
    <p>
        <span>mixed $value</span>
        <span>The value to convert to an array</span>
    </p>
</div>
<hr />
<h3>valid() <small>implemented by Iterator</small></h3>
<p>
    Checks if current internal array position is valid
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>