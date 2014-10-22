<?php

namespace Interfaces;

interface ZingTemplate{

    public function init();

    public function render($filename);

    public function assign($key, $value = "");

    public function append($key, $value = "");
}
