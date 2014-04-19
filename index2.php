<?php

session_start();
require_once __DIR__ . "/Zing/Zing.php";
require_once __DIR__ . "/Zing/config.php";

$zing = new Zing();
$zing->init($config);

/*
 * Load Our Routes
 */

$zing->route("/test/:id", "Test::main");

$zing->run();
