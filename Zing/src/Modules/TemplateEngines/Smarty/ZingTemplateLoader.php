<?php

namespace Modules\TemplateEngines\Smarty;

use Interfaces\ZingTemplate;
use Smarty;

/**
 * @property Smarty $smarty Smarty Template Engine
 */
class ZingTemplateLoader implements ZingTemplate{

    protected $smarty    = null;
    protected $unassgned = array();

    public function init(){
        require_once __DIR__ . "/Smarty.class.php";
        $this->smarty = new Smarty();
        return $this->smarty;
    }

    public function render($filename){
        foreach($this->unassgned as $key => $value){
            $this->assign($key, $value);
            unset($this->unassgned[$key]);
        }
        $this->smarty->display($filename);
    }

    public function assign($key, $value = ""){
        $this->smarty->assign($key, $value);
    }

    public function append($key, $value = ""){
        if(!array_key_exists($key, $this->unassgned)){
            if(is_array($value)){
                $this->unassgned[$key] = array();
            }else{
                $this->unassgned[$key] = "";
            }
        }
        if(is_array($value)){
            array_push($this->unassgned, $value);
        }else{
            $this->unassgned[$key] .= $value;
        }
    }

}
