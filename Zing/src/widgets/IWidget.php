<?php

namespace Widgets;

interface IWidget{

    public function setOptions(array $settings);

    public function runWidget();

    public function setDefaultOptions();
}
