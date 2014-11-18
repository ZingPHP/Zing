<?php

namespace Modules\Image;

use Modules\Module;

class Layer extends Module{

    protected $name = "";

    public function setName($layer_name){
        $this->name = $layer_name;
    }

}
