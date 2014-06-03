<h2>Core</h2>
<p>
    The core are the core components to make Zing work the way it works.
</p>
<div class="toc">
    <ul>
        <li><a href="">Date::dateDiff()</a></li>
        <li><a href="">Date::isWeekday()</a></li>
        <li><a href="">Date::isWeekend()</a></li>
        <li><a href="">Date::minToSec()</a></li>
        <li><a href="">Date::prettyDate()</a></li>
        <li><a href="">Date::timeDiff()</a></li>
        <li><a href="">Date::timeOverlap()</a></li>
        <li><a href="">Date::timeToSec()</a></li>
    </ul>
</div>
<hr />
<h3>dateDiff()</h3>
<p>
    Finds the number of days between two dates
</p>
<div class="toc">
    <p>
        <span>string $date1</span>
        <span>The first date</span>
    </p>
    <p>
        <span>string $date2</span>
        <span>The second date</span>
    </p>
</div>
<pre>
class MyPage extends Zing{

    public function main(){
        // prints 8
        echo $this->date->dateDiff("2014-05-01", "2014-04-23");
        // prints 69
        echo $this->date->dateDiff("2/13/2014", "2014-04-23");
    }

}
</pre>
<hr />
<h3>isWeekday()</h3>
<p>
    Tests to see if the date is a weekday (Monday - Friday)
</p>
<div class="toc">
    <p>
        <span>string $date</span>
        <span>The date to test</span>
    </p>
</div>
<pre>
class MyPage extends Zing{

    public function main(){
        // Displays true
        var_dump($this->date->isWeekday("2014-05-01"));
        // Displays false
        var_dump($this->date->isWeekday("3/1/2014"));
    }

}
</pre>
<hr />
<h3>isWeekend()</h3>
<p>
    Tests to see if the date is a weekend (Saturday or Sunday)
</p>
<div class="toc">
    <p>
        <span>string $date</span>
        <span>The date to test</span>
    </p>
</div>
<pre>
class MyPage extends Zing{

    public function main(){
        // Displays false
        var_dump($this->date->isWeekend("2014-05-01"));
        // Displays true
        var_dump($this->date->isWeekend("3/1/2014"));
    }

}
</pre>
<hr />
<h3>minToSec()</h3>
<p>
    Converts minutes to seconds
</p>
<div class="toc">
    <p>
        <span>int $minutes</span>
        <span>The date to test</span>
    </p>
</div>
<pre>
class MyPage extends Zing{

    public function main(){
        // Displays 120
        var_dump($this->date->minToSec(2));
        // Displays 600
        var_dump($this->date->minToSec(10));
    }

}
</pre>
<hr />
<h3>prettyDate()</h3>
<p>
    Converts minutes to seconds
</p>
<div class="toc">
    <p>
        <span>string $date</span>
        <span>The date to beautify</span>
    </p>
    <p>
        <span>string $format = </span>
        <span></span>
    </p>
</div>
<pre>
class MyPage extends Zing{

    public function main(){
        // Displays 120
        var_dump($this->date->minToSec(2));
        // Displays 600
        var_dump($this->date->minToSec(10));
    }

}
</pre>