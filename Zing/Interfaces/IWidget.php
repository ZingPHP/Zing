<?php

namespace Interfaces;

interface IWidget{

    public function setSettings(array $settings);

    public function runWidget();

    public function setDefaultSettings();
}
