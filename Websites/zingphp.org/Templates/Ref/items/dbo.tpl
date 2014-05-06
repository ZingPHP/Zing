<h2>DBO <small>Database Objects</small></h2>
<p>
    The DBO module allows Zing to connect to a database and make queries.
</p>
<div class="toc">
    <ul>
        <li><a href="#beginTransaction()">DBO::beginTransaction()</a></li>
        <li><a href="#commitTransaction()">DBO::commitTransaction()</a></li>
        <li><a href="#connect()">DBO::connect()</a></li>
        <li><a href="#getAffectedRows()">DBO::getAffectedRows()</a></li>
        <li><a href="#getAll()">DBO::getAll()</a></li>
        <li><a href="#getInsertID()">DBO::getInsertID()</a></li>
        <li><a href="#getOne()">DBO::getOne()</a></li>
        <li><a href="#getRow()">DBO::getRow()</a></li>
        <li><a href="#getTable()">DBO::getTable()</a></li>
        <li><a href="#init()">DBO::init()</a></li>
        <li><a href="#query()">DBO::query()</a></li>
        <li><a href="#rollBackTransaction()">DBO::rollBackTransaction()</a></li>
        <li><a href="#setConnectionParams()">DBO::setConnectionParams()</a></li>
    </ul>
</div>
<hr />
<a name="beginTransaction()"></a>
<h3>beginTransaction()</h3>
<p>
    Starts a database transaction
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        $db = $this->getDbo("localhost");
        // Start database transaction
        $db->beginTransaction();
        // Insert some data
        $db->query("insert into my_table (col1, col2) values ('abc', '123')");
        $db->query("insert into my_table (col1, col2) values ('123', 'abc')");
        // Commit the data to the database
        $db->commitTransaction();
    }

}
</pre>
<hr />
<a name="commitTransaction()"></a>
<h3>commitTransaction()</h3>
<p>
    Saves the data to the database
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        $db = $this->getDbo("localhost");
        // Start database transaction
        $db->beginTransaction();
        // Insert some data
        $db->query("insert into my_table (col1, col2) values ('abc', '123')");
        $db->query("insert into my_table (col1, col2) values ('123', 'abc')");
        // Commit the data to the database
        $db->commitTransaction();
    }

}
</pre>
<hr />
<a name="connect()"></a>
<h3>connect()</h3>
<p>
    Connects to the database (DBO does this automatically when needed)
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<hr />
<a name="getAffectedRows()"></a>
<h3>getAffectedRows()</h3>
<p>
    Gets the number of rows affected by the last query
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Select database to use
        $db = $this->getDbo("localhost");
        // Update some rows in the database
        $db->query("update my_table set col1 = col1 + 1 where col2 = '123'");
        // Get the number of rows updated
        var_dump($db->getAffectedRows());
    }

}
</pre>
<hr />
<a name="getAll()"></a>
<h3>getAll()</h3>
<p>
    Gets all rows in a query
</p>
<div class="toc">
    <p>
        <span>string $query</span>
        <span>The query string</span>
    </p>
    <p>
        <span>array $params</span>
        <span>The the replacements</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Select database to use
        $db = $this->getDbo("localhost");

        // Do a database query
        $data = $db->getAll("select * from my_table where col1 = :col1", array(
            "col1" => 'abc'
        ));

        // Display the results
        var_dump($data);
    }

}
</pre>
<hr />
<a name="getInsertID()"></a>
<h3>getInsertID()</h3>
<p>
    Get the last auto_increment id from a query
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Select database to use
        $db = $this->getDbo("localhost");

        // Insert some data into the table
        $db->query("insert into my_table (col1, col2) values (:col1, :col2)",
        array(
            "col1" => 'abc',
            "col2" => '123'
        ));

        // Get the last insert ID
        var_dump($db->getInsertID());
    }

}
</pre>
<hr />
<a name="getOne()"></a>
<h3>getOne()</h3>
<p>
    Get the first column of the first row
</p>
<p class="bg-info">
    <code>limit 1</code> should be used with this method, as 0 or 1 item is always returned.
</p>
<div class="toc">
    <p>
        <span>string $query</span>
        <span>The query string</span>
    </p>
    <p>
        <span>array $params</span>
        <span>The the replacements</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Select database to use
        $db = $this->getDbo("localhost");

        // Select select a column from a table
        $item = $db->getOne("select col1 from my_table where id = :id limit 1",
        array(
            "col2" => 52562
        ));

        // Display col1's data
        var_dump($item);
    }

}
</pre>
<hr />
<a name="getRow()"></a>
<h3>getRow()</h3>
<p>
    Gets a row from the database
</p>
<p class="bg-info">
    <code>limit 1</code> should be used with this method, as 0 or 1 row is always returned.
</p>
<div class="toc">
    <p>
        <span>string $query</span>
        <span>The query string</span>
    </p>
    <p>
        <span>array $params</span>
        <span>The the replacements</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Select database to use
        $db = $this->getDbo("localhost");

        // Select a row from the database
        $row = $db->getRow("select * from my_table where id = :id limit 1",
        array(
            "col2" => 52562
        ));

        // Display the data
        var_dump($row);
    }

}
</pre>
<hr />
<a name="getTable()"></a>
<h3>getTable()</h3>
<p>
    Gets a table object
</p>
<div class="toc">
    <p>
        <span>string $table_name</span>
        <span>The table that will become an object</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        // Select database to use
        $db = $this->getDbo("localhost");

        // Get a table object
        $my_table = $db->getTable("my_table");
    }

}
</pre>
<hr />
<a name="getTable()"></a>
<h3>getTable()</h3>
<p>

</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<h4>Examples</h4>
<pre>
class MyPage extends Zing{

    public function main(){
        $example =
    }

}
</pre>
<hr />
<a name="init()"></a>
<h3>init()</h3>
<p>
    Initializes a new Database Object (Zing does the automatically)
</p>
<div class="toc">
    <p>
        <span>array $config</span>
        <span>The Web site configuration information (From config.php)</span>
    </p>
</div>
<hr />
<a name="rollBackTransaction()"></a>
<h3>rollBackTransaction()</h3>
<p>
    rolls back a transaction (don't save to the database)
</p>
<div class="toc">
    <p>
        <span>void</span>
    </p>
</div>
<pre>
class MyPage extends Zing{

    public function main(){
        // Select database to use
        $db = $this->getDbo("localhost");
        $db->beginTransaction();
        // Insert data into table
        $my_table = $db->query("insert into my_table (col1) values (:col1)",
        array(
            "col1" => 123
        ));
        // Don't save the changes
        $db->rollbackTransaction();
    }

}
</pre>
<hr />
<a name="query()"></a>
<h3>query()</h3>
<p>
    Sends a query to the database (usually for inserts/updates/deletes)
</p>
<div class="toc">
    <p>
        <span>string $query</span>
        <span>The query string</span>
    </p>
    <p>
        <span>array $params</span>
        <span>The the replacements</span>
    </p>
</div>
<pre>
class MyPage extends Zing{

    public function main(){
        // Select database to use
        $db = $this->getDbo("localhost");

        // Insert data into table
        $my_table = $db->query("insert into my_table (col1) values (:col1)",
        array(
            "col1" => 123
        ));
        // Update the data
        $my_table = $db->query("update my_table set col1 = :col1",
        array(
            "col1" => 12345
        ));
        // Delete the data
        $my_table = $db->query("delete from my_table where col1 = :col1",
        array(
            "col1" => 12345
        ));
    }

}
</pre>
<hr />
<a name="setConnectionParams()"></a>
<h3>setConnectionParams()</h3>
<p>
    Sets up the database connections (Zing does the automatically)
</p>
<div class="toc">
    <p>
        <span>array $config</span>
        <span>The database connection information<br />
            <pre>array(
    "dsn"      => "mysql",     // Default = "mysql"
    "hostname" => "localhost", // Default = ""
    "username" => "example",   // Default = ""
    "password" => "example",   // Default = ""
    "database" => "example",   // Default = ""
    "port"     => 3306,        // Default = 3306
)
            </pre>
        </span>
    </p>
</div>