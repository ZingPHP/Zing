<?php

class Test extends Zing{

    public function main(){
        echo $this->getWidget("Calendar/Calendar", [
            "day"      => "full",
            "zerofill" => true,
            "link"     => "http://blog.mysite.com/stories/%x"
        ]);
    }

}
