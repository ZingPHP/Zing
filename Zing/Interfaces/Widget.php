<?php

namespace Interfaces;

interface Widget{

    public function setSettings(array $settings);

    public function runWidget();

    public function setDefaultSettings();
}
