<?php

class Test extends Zing{

    public function main(){
        $users = $this->dbo("localhost")->getTable("users");
        $rows  = $users->getItemsByFirstName("ryan")->each(function($row){
            echo $row["firstname"] . " " . $row["lastname"] . "<br />";
        });

        foreach($rows as $row){
            echo $row["firstname"] . " " . $row["lastname"] . "<br />";
        }
    }

}
