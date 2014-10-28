<?php

namespace Interfaces;

interface ZingTemplate{

    public function init();

    public function render($filename);

    public function assign($key, $value = "");

    public function append($key, $value = "");

    public function parseTpl($tpl, $key, $data = "");
}
