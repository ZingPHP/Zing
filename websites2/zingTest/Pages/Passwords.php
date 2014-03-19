<?php

class Passwords extends Zing{

    public function main(){
        //var_dump($this->password->setSalt("ryan"));
        //var_dump($this->password->create("ryan"));

        /* $username = "ryannaddy";
          $password = $this->password->create("abc123");

          $this->db->localhost->query("insert into members (username, password) values (?, ?)", array(
          $username,
          $password
          )); */
        
        $password = $this->db->localhost->getOne("select password from members where member_id = 1");
        
        var_dump($this->password->verify("abc123", $password));
    }

}
