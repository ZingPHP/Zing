<?php

class Home extends Zing{

    public function main(){
        try{
            $db = $this->dbo("localhost");
            var_dump($db->getById("users", 1));
            //var_dump($db);
            //var_dump($db->getOne("select username from users where user_id = 1"));
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

}
