<?php

class Home extends Zing{

    public function main(){

        /* $users = $this->cache->cache("test", 10, function(){
          echo "<p>Caching Users...</p>";
          try{
          $users = $this->dbo("localhost")->getTable("users");
          return $users->getItemsByFirstName("ryan");
          }catch(\Exception $e){
          echo $e->getMessage();
          }
          });

          var_dump($users); */
    }

    public function catchAll(){
        $usersView = $this->dbo("localhost")->getTable("users")->getView();
    }

}
