<?php

namespace Modules;

use Exception;

class Tpl extends Module{

    protected $engines   = array();
    protected $engine    = null;
    protected $tplEngine = null;

    public function __construct($config = array()){
        $engines = glob(__DIR__ . "/TemplateEngines/*", GLOB_ONLYDIR);
        foreach($engines as $engine){
            $this->engines[] = basename($engine);
        }

        parent::__construct($config);
    }

    /**
     * Loads a template engine
     * @param type $engine
     * @return Tpl
     */
    public function getEngine($engine){
        $engineName      = "\\Modules\\TemplateEngines\\$engine\\ZingTemplateLoader";
        $this->tplEngine = new $engineName();
        $this->engine    = $this->tplEngine->init();
        return $this;
    }

    public function assign($key, $value = ""){
        if($this->tplEngine == null){
            if(!$this->setDefaultEngine()){
                throw new Exception("Template Engine Not Set");
            }
        }
        $this->tplEngine->assign($key, $value);
    }

    public function display($filename){
        if($this->tplEngine == null){
            if(!$this->setDefaultEngine()){
                throw new Exception("Template Engine Not Set");
            }
        }
        $this->tplEngine->render($filename);
    }

    private function setDefaultEngine(){
        if(isset($this->config["tplEngine"])){
            $this->getEngine($this->config["tplEngine"]);
            return true;
        }
        return false;
    }

}
