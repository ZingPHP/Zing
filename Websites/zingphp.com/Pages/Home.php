<?php

class Home extends Zing{

    public function main(){

        $users = $this->cache->cache("test", 10, function(){
            echo "<p>Caching Users...</p>";
            try{
                $users = $this->dbo("localhost")->getTable("users");
                return $users->getItemsByUsername("ryannaddy");
            }catch(\Exception $e){
                echo $e->getMessage();
            }
        });

        var_dump($users);

        /* try{
          $users = $this->dbo("localhost")->getTable("users");
          var_dump($users->getItemsByFirstName("ryan"));

          /* $db = $this->dbo("localhost");
          var_dump($db->getById("users", 1)); */
        //var_dump($db);
        //var_dump($db->getOne("select username from users where user_id = 1"));
        /* }catch(Exception $e){
          echo $e->getMessage();
          } */
    }

}
