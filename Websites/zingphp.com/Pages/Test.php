<?php

class Test extends Zing{

    public function main(){
        $users = $this->dbo("localhost")->getTable("users");
        var_dump($users->getItemsByFirstName("ryan")->toArray());
    }

}
