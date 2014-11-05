<?php

namespace Widgets;

use Interfaces\IWidget;
use Zing;

class Widget extends Zing implements IWidget{

    protected $html     = "";
    protected $settings = array(
        "loadCSS" => true
    );

    final public function getHtml(){
        return $this->html;
    }

    final public function saveSettings(){
        $class                       = $this->_callingClass();
        $_SESSION["widgets"][$class] = $this->settings;
    }

    final public function loadSavedSettings(){
        $class          = $this->_callingClass();
        $this->settings = $_SESSION["widgets"][$class];
    }

    final public function getSetting($key){
        return $this->settings[$key];
    }

    final public function setSettings(array $settings = array()){
        $this->settings = array_merge($this->settings, $settings);
    }

    public function runWidget(){

    }

    public function setDefaultSettings(){

    }

    private function _callingClass(){
        $className = get_called_class();
        $pos       = strrpos($className, "\\") + 1;
        $class     = substr($className, $pos);
        return $class;
    }

}
