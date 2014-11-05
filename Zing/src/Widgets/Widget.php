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

}
