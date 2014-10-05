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

spl_autoload_register(function($class){
    require_once __DIR__ . "/Smarty.class.php";
});
