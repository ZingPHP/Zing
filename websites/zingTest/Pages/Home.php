<?php

/**
 *
 * @author Ryan Naddy <rnaddy@corp.acesse.com>
 * @name Home.php
 * @version 1.0.0 Mar 3, 2014
 */
class Home extends Zing{

    public function main(){
        echo $this->input->defaultString("'name' not found");
    }

}
