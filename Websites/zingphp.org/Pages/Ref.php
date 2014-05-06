<?php

class Ref extends Zing{

    public function catchAll(){
        $item     = $this->input->get("action");
        $document = $this->smarty->fetch(__DIR__ . "/../Templates/Ref/items/$item.tpl");
        $this->smarty->assign("ref", $document);
    }

}
