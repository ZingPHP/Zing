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
            array_push($this->unassgned[$key], $value);
        }else{
            $this->unassgned[$key] .= $value;
        }
    }

    public function parseTpl($tpl, $key, $data = ""){
        $smarty = new Smarty();
        if(is_array($key)){
            foreach($key as $k => $v){
                $smarty->assign($k, $v);
            }
        }else{
            $smarty->assign($key, $data);
        }
        $cache_id = md5($_SERVER['REQUEST_URI']);
        return $smarty->fetch("string:$tpl", $cache_id);
    }

}
