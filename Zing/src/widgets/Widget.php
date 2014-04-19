<?php

namespace Widgets;

class Widget extends Zing{

    protected $html     = "";
    protected $settings = array();

    final public function getHtml(){
        return $this->html;
    }

    final protected function setOptions(array $settings = array()){
        $this->settings = array_merge($this->settings, $settings);
    }

}
