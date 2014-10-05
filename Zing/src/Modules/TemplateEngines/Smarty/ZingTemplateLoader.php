<?php

namespace Modules\TemplateEngines\Smarty;

use Interfaces\ZingTemplate;
use Smarty;

/**
 * @property Smarty $smarty Smarty Template Engine
 */
class ZingTemplateLoader implements ZingTemplate{

    protected $smarty = null;

    public function init(){
        require_once __DIR__ . "/Smarty.class.php";
        $this->smarty = new Smarty();
        return $this->smarty;
    }

    public function render($filename){
        $this->smarty->display($filename);
    }

    public function assign($key, $value = ""){
        $this->smarty->assign($key, $value);
    }

}
