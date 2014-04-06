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
<hr />
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
        $users = $this->dbo("localhost")->getTable("users");
        var_dump($users->getItemsByFirstName("ryan")->toArray());
    }

}
</pre>
<p>
    In our above example, we are connecting to the <code>localhost</code> database
    which was defined in our <code>$config</code> from the previous section
    (the global database). Next we are telling the db to look at the users table
    and get the row(s) <code>WHERE FirstName = 'ryan'</code>.
</p>
<blockquote>
    <b>Note:</b> <code>FirstName</code> and <code>firstname</code> mean the same
    thing on windows machines. On *nix machines they are two different things.
</blockquote>
<p>
    If you need more of an "SQL" approach, there are some handy functions within
    the DBO class to help you get data as well, take this code for example:
</p>
<pre>
class Home extends Zing{

    public function main(){
        $localhost = $this->dbo("localhost");
        $users = $localhost->getAll("select * from users where username = 'ryan'");
        var_dump($users->toArray());
    }

}
</pre>
<a name="databases-looping-through-results" id="databases-looping-through-results"></a><h3>Looping Through Results</h3>
<p>
    We can easily loop through the database results multiple ways; you can either
    use the commonly used <code>foreach</code> construct built into php or you can
    use ZingPHP's <code>each()</code> method, both ways work basically the same way.
    So choose which ever method you feel more comfortable doing.
</p>
<pre>
class Home extends Zing{

    public function main(){
        $users = $this->dbo("localhost")->getTable("users");

        // Using ZingPHP's each() method:
        $users->getItemsByFirstName("ryan")->each(function($row){
            echo $row["firstname"] . " " . $row["lastname"] . "&lt;br /&gt;";
        });

        // Using PHP's built in foreach:
        $rows  = $users->getItemsByFirstName("ryan");
        foreach($rows as $row){
            echo $row["firstname"] . " " . $row["lastname"] . "&lt;br /&gt;";
        }
    }

}
</pre>
<hr />
<a name="caching" id="caching"></a><h2>Caching</h2>
<p>
    Caching is a way of getting information quickly from a cache. For example say you need to
    get the same information from the database constantly. Doing lots of queries on your database
    can really slow down a website, so to solve this we can cache the data and get it from a cache.
</p>
<p>
    ZingPHP supports multiple types of caching engines, and file caching. By default
    ZingPHP uses file caching since not all servers have a caching engine.
</p>
<a name="caching-database-caching" id="caching-database-caching"></a><h3>Database Caching</h3>
<p>
    This snippet will get the latest <code>20</code> news items and cache it for <code>120</code> seconds
    in a cache called <code>LatestNews</code>. Once <code>120</code> seconds is up, the next
    person to load the page will re-cache <code>20</code> new news items.
</p>
<pre>
class Home extends Zing{

    public function main(){

        $news = $this->cache->cache("LatestNews", 120, function(){
            return $this->dbo("localhost")->getAll("
                select * from news
                where PostDate = curdate()
                order by PostTime desc limit 20
            ");
        });

        var_dump($news);
    }

}
</pre>
<p>
    If you prefer to use another caching engine, you can always switch. To do
    the exact same thing as above but with <code>APC</code>, you would use
    <code>setEngine()</code> like this:
</p>
<pre>
class Home extends Zing{

    public function main(){

        $news = $this->cache
        ->setEngine(Cache::APC)->cache("LatestNews", 120, function(){
            return $this->dbo("localhost")->getAll("
                select * from news
                where PostDate = curdate()
                order by PostTime desc limit 20
            ");
        });

        var_dump($news);
    }

}
</pre>
<p>
    Some times you need to cache items that almost never change, such as a list of countries,
    or your website's supported languages. It is vary rare that you need to check the database
    one every request, and since it almost never changes, we can set it to never expire by setting
    the second parameter to <code>null</code>.
</p>
<pre>
class Home extends Zing{

    public function main(){

        $news = $this->cache->cache("Languages", null, function(){
            return $this->dbo("localhost")->getAll("select * from languages");
        });

        var_dump($news);
    }

}
</pre>
<p>
    When we use <code>null</code>, the only way to clear the cache is to manually delete it.
    There are two ways to do this, we can either use <code>delete()</code> to delete selected
    items, or we can use <code>destroy()</code> to clear all the items that are cached.
</p>
<pre>
class Home extends Zing{

    public function main(){

        // Deletes just "Languages" from the cache
        $this->cache->delete("Languages");

        // Deletes "Languages" and "LatestNews" from the cache
        $this->cache->delete(array("Languages", "LatestNews"));

        // Deletes everything from the cache
        $this->cache->destroy();

    }

}
</pre>