<?php

namespace Modules\TemplateEngines\Twig;

use Interfaces\ZingTemplate;
use Twig_Autoloader;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * @property Twig_Environment $twig Twig Template Engine
 */
class ZingTemplateLoader implements ZingTemplate{

    protected $twig = null;
    protected $vars = array();

    public function init(){
        require_once __DIR__ . "/Autoloader.php";
        Twig_Autoloader::register();
        $loader     = new Twig_Loader_Filesystem(__DIR__ . "/../../../../../Websites/Templates");
        $this->twig = new Twig_Environment($loader);
        return $this->twig;
    }

    public function render($filename){
        $pos      = strrpos($filename, "Templates/");
        $filename = substr($filename, $pos + strlen("Templates/"));
        $template = $this->twig->loadTemplate($filename);
        echo $template->render($this->vars);
    }

    public function assign($key, $value = ""){
        $this->vars[$key] = $value;
    }

    public function append($key, $value = ""){
        if(!array_key_exists($key, $this->vars)){
            if(is_array($value)){
                $this->vars[$key] = array();
            }else{
                $this->vars[$key] = "";
            }
        }
        if(is_array($value)){
            array_push($this->vars, $value);
        }else{
            $this->vars[$key] .= $value;
        }
    }

}
