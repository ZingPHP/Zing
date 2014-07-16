<?php

namespace Widgets;

class Widget extends Zing{

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

    final public function setOptions(array $settings = array()){
        $this->settings = array_merge($this->settings, $settings);
    }

}
