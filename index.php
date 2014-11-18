<?php

/**
 * @author Ryan Naddy <zing@phpsnips.com>
 */
$page    = "Home";
$action  = "main";
$is_ajax = false;

// Get the page to be loaded
if(isset($_GET["page"])){
    $page = $_GET["page"];
}

// Get the action to preform
if(isset($_GET["action"])){
    $action = $_GET["action"];
}

// Test if this is an ajax request
if(isset($_GET["ajax"])){
    $is_ajax = (bool)(int)$_GET["ajax"];
}

session_start();

// Initialize Zing
require_once __DIR__ . "/Zing/Zing.php";
require_once __DIR__ . "/Zing/config.php";
$zing = new Zing();
$zing->init($config);
$zing->setRoute(filter_input(INPUT_GET, "path"));
$zing->run();
