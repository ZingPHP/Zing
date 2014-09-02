<?php

namespace Modules\Image;

class Layer extends \Modules\Module{

    protected $name = "";

    public function setName($layer_name){
        $this->name = $layer_name;
    }

}
