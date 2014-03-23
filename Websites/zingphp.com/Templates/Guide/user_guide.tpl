<a name="introduction"></a><h2>Introduction</h2>
<a name="getting-started"></a><h3>Getting Started</h3>
<p>
    Welcome. ZingPHP is a very useful and powerful tool to to make websites quickly.
    ZingPHP is different from many of the other frameworks out there, yet it is super
    easy to learn, use and is very powerful.
</p>

<a name="getting-started"></a><h3>Hello World!</h3>
<p>
    Using ZingPHP we can easily create a page that displays the text <code>Hello World!</code>.
    All we need to do is place our text within the <code>main()</code> method of our page.
</p>
<pre class="prettyprint">
class Home extends Zing{

    public function main(){
        echo "Hello World!";
    }

}
</pre>
<p>
    Pretty simple huh? There are a few things that we need to remember though:
</p>
<ol>
    <li><code>Home</code> is a reserved class for the fall back page to run, similar to <code>index.php</code></li>
    <li><code>main</code> is a reserved method for the fall back action to run.</li>
</ol>
<p>
    So, to make sense of that here are a few examples:
</p>
<ul>
    <li><code>http://mysite.com</code> will automatically load <code>Home::main()</code></li>
    <li><code>http://mysite.com/settings</code> will automatically load <code>Settings::main()</code></li>
    <li><code>http://mysite.com/settings/password</code> will automatically load <code>Settings::password()</code></li>
</ul>
<hr />
<a name="modules"></a><h2>Modules</h2>
<a name="modules-what's-a-module"></a><h3>What's a Module?</h3>
<p>
    A module, is a class that can be used within the Zing framework. A module <b>MAY NOT</b> depend on another modules existence in order to work, except for the existence of the <code>Module</code> class. (the smarty template engine is an exception, it is handled differently).
</p>
<a name="modules-custom-modules"></a><h3>Custom Modules</h3>
<p>
    When creating a module, remember to extend <code>Module</code>, this class has some extra module features,
    and it will receive the websites current configuration array, which can be accessed using <code>$this->config["my_setting"]</code>. If you add a custom constructor to your module, make sure to give Module the config.
</p>
<pre class="prettyprint">
class MyModule extends Module{

    public function __construct($config){
        parent::__construct($config);
        // My code here
    }

}
</pre>
<p>
    Custom modules can be created, and once a module is created, it needs to be added to the Zing core modules array: <code>Zing/Zing.php>>Zing::$modules</code>. If it is not added, Zing cannot use this module.
</p>
<p>
    Zing does not load modules unless it is called upon within a page. This reduces the number of classes and files the Zing framework needs to load in order to run. Adding hundreds of modules will not slow down Zing since it will not load the module unless needed.
</p>
<p>
    Modules should be placed in the directory: <code>Zing/src/modules</code>
</p>