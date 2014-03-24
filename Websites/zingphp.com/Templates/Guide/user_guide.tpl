<a name="intro" id="intro"></a><h2>Introduction</h2>
<a name="intro-getting-started" id="intro-getting-started"></a><h3>Getting Started</h3>
<p>
    Welcome. ZingPHP is a very useful and powerful tool to to make websites quickly.
    ZingPHP is different from many of the other frameworks out there, yet it is super
    easy to learn, use and is very powerful.
</p>

<a name="intro-hello-world" id="intro-hello-world"></a><h3>Hello World!</h3>
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
<a name="modules" id="modules"></a><h2>Modules</h2>
<a name="modules-whats-a-module" id="modules-whats-a-module"></a><h3>What's a Module?</h3>
<p>
    A module, is a class that can be used within the Zing framework. A module <b>MAY NOT</b> depend on another modules existence in order to work, except for the existence of the <code>Module</code> class. (the smarty template engine is an exception, it is handled differently).
</p>
<a name="modules-custom-modules" id="modules-custom-modules"></a><h3>Custom Modules</h3>
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
<a name="databases" id="databases"></a><h2>Databases</h2>
<a name="databases-connecting-to-a-database" id="databases-connecting-to-a-database"></a><h3>Connecting to a Database</h3>
<p>
    It is possible to have one or more database connections in ZingPHP, you just
    need to configure each one in your configuration file. They can also be local
    databases that are for one website, or global databases that all sites can use.
</p>
<p>
    Here is a configuration file for one site that has two local databases and
    one global database.
</p>
<pre>
$config = array(
    "websites" => array(
        array(
            // ...
            // Other website configurations
            // ...
            "databases" => array( // Local databases that only this site can use
                "db1" => array( // Defaults to a MySQL connection
                    "hostname" => "localhost",
                    "username" => "root",
                    "password" => "",
                    "database" => "test"
                ),
                "db2" => array( // Connects to a SQLite server
                    "hostname" => "localhost",
                    "username" => "root",
                    "password" => "",
                    "database" => "test",
                    "dsn"      => "sqlite"
                )
            )
        ),
    ),
    "databases" => array(
        "localhost" => array( // Global databases all sites can use
            "hostname" => "localhost",
            "username" => "root",
            "password" => "",
            "database" => "test"
        ),
    )
);
</pre>
<p>
    That is all you need to do to connect to a database! The key for each array
    is the name that will be used to use that database (see next section for example).
    ZingPHP will not actually connect to the database, until a database query is run.
    This way only pages that need database information connect, this makes your page run faster.
</p>
<a name="databases-select-an-id" id="databases-selecting-data"></a><h3>Selecting Data</h3>
<p>
    ZingPHP has many ways to select data from the database, and new ways are
    always getting added. Lets take a look at a few of the ways that you can
    select data from your database to use within your application.
</p>
<pre>
class Home extends Zing{

    public function main(){
        $user = $this->db->localhost->getById("users", 100);
        print_r($user);
    }

}
</pre>
<p>
    In our above example, we are connecting to the <code>localhost</code> database
    which was defined in our <code>$config</code> from the previous section
    (the global database). Next we are telling the db to look at the users table
    and get the row where the primary key is <code>100</code>.
</p>