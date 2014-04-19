<?php

namespace Widgets;

interface IWidget{

    public function setOptions(array $settings);

    public function run();

    public function setDefaultOptions();
}
