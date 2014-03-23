<?php

class Guide extends Zing{

    public function catchAll(){
        $document = $this->smarty->fetch(__DIR__ . "/../Templates/Guide/user_guide.tpl");
        $this->smarty->assign("doc", $document);
    }

}
