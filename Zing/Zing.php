<?php

use Modules\Tpl;
use Modules\Cache;
use Modules\Date;
use Modules\DBO;
use Modules\File;
use Modules\Form;
use Modules\Http;
use Modules\Input;
use Modules\Mail;
use Modules\Math;
use Modules\Twitter;
use Modules\User;
use Modules\Util;
use Modules\Validate;
use Modules\Session;

define("__TPL__", $_SERVER["DOCUMENT_ROOT"] . "/Websites/Templates");

/**
 * @property Input $input Functionality for global variables
 * @property Http $http Functionality to Http
 * @property DBO $DBO Functionality to connect to databases
 * @property Smarty $smarty Functionality for smarty templates
 * @property Twig_Environment $twig Functionality for twig templates
 * @property Tpl $tpl Template Engine
 * @property Form $form Functionality for forms and form validation
 * @property User $user Functionality to work with users
 * @property Mail $mail Functionality to work with emails
 * @property Util $util Functionality to access utilites
 * @property File $file Functionality to access files
 * @property Math $math Functionality to access math
 * @property Date $date Functionality to access dates
 * @property Validate $validate Functionality to access dates
 * @property Cache $cache Functionality to access dates
 * @property Twitter $twitter Twitter Accessability
 * @property Session $session Session Manager
 */
class Zing{

// Static properties
    public static $page         = "Home";
    public static $action       = "main";
    public static $noBody       = false;
    public static $params       = array();
    public static $isAjax       = false;
    protected
            $db               = array(),
            $host             = "",
            $root             = "",
            $tplExtention     = "tpl",
            $pageExists       = false,
            $namespace        = "",
            $pageTitle        = "";
    private
            $headerTpl          = "Global/header",
            $footerTpl          = "Global/footer",
            $mainTpl            = "",
            $tplShell           = null,
            $config             = array(),
            $fullConfig         = array(),
            $modules            = array(
                "DBO"      => false,
                "input"    => false,
                "session"  => false,
                "http"     => false,
                "form"     => false,
                "user"     => false,
                "mail"     => false,
                "tpl"      => false,
                "util"     => false,
                "file"     => false,
                "math"     => false,
                "date"     => false,
                "validate" => false,
                "cache"    => false,
                "twitter"  => false,
    );
    public static
            $widgets      = array(),
            $widgetStyles = array();

    /**
     * Initiates modules on the fly.
     * @param string $name
     * @return instance
     */
    public function __get($name){
        if(array_key_exists($name, $this->modules) && !$this->modules[$name]){
            $class                = ucfirst($name);
            $class                = "Modules\\$class";
            $this->$name          = new $class($this->config);
            $this->modules[$name] = true;
        }
        return $this->$name;
    }

    public function __construct(){
        $this->root = $_SERVER["DOCUMENT_ROOT"];
        if(isset($_GET["host"])){
            $this->host = preg_replace("/^www(.*?)\./", "", $_GET["host"]);
        }else{
            $this->host = preg_replace("/^www(.*?)\./", "", $_SERVER["HTTP_HOST"]);
        }
    }

    /**
     * Gets a value from the loaded configuration.
     * @param string $key
     * @return mixed
     */
    final public function config($key){
        return Zing::$config[$key];
    }

    /**
     * Sets the current page (Name of the class).
     * @param string $page
     */
    final public function setPage($page){
        if(empty($page) || $page == "/"){
            $page = "Home";
        }
        $_GET["page"] = $page;
        Zing::$page   = trim($page, "/");
    }

    /**
     * Sets the current action to run (Name of the method).
     * @param string $action
     */
    final public function setAction($action){
        $_GET["action"] = $action;
        Zing::$action   = $action;
    }

    /**
     * Sets whether or not the current request is an ajax request.
     * @param boolean $is_ajax
     */
    final public function setIsAjax($is_ajax){
        Zing::$isAjax = (bool)$is_ajax;
    }

    /**
     * Initialize the framework and website for usage.
     * @param array $config
     */
    final public function init($config){
        $this->fullConfig = $config;
        $this->getWebsiteConfig();
    }

    final public function run(){
        try{
            if($this->load()){
                $this->runPage();
            }
        }catch(Exception $e){
            try{
                $loaded = (bool)$this->loadTemplates();
                if($loaded){
                    //echo "Loaded";
                }else{
                    foreach(Zing::$widgets as $widget){
                        $w      = $widget["instance"];
                        $getCss = (bool)$w->getSetting("loadCSS");
                        if($getCss){
                            foreach($widget["css"] as $style){
                                echo $style;
                            }
                        }
                    }
                }
            }catch(Exception $e){
                echo $e->getMessage();
                $this->notFound();
            }
        }
    }

    final public function setRoute($path){
        if(isset($this->config["route"])){
            return;
        }
        if(!isset($this->config["routes"])){
            goto loadDefault;
        }
        $routes = $this->config["routes"];
        $route  = explode("/", trim($path, "/"));
        // Start Testing all possible routes for this request
        foreach($routes as $rt){
            $useRt  = explode(" ", $rt);
            $testRt = explode("/", trim($useRt[0], "/"));
            $params = array();
            $match  = true;
            // Loop through all test items in defined route
            for($i = 0; $i < count($testRt); $i++){
                $is_param = preg_match("/^@|^#/", $testRt[$i]);
                // Not a paramter keep testing current route
                if(!$is_param && isset($route[$i]) && $route[$i] == $testRt[$i]){
                    continue;
                    // Is a parameter, test current parameter futher
                }elseif($is_param && (isset($route[$i]) || $testRt[$i] == "@action" || $testRt[$i] == "@page")){
                    // Set default page/action if defined in route but not set
                    if(!isset($route[$i])){
                        if($testRt[$i] == "@action" && !isset($route[$i])){
                            $route[$i] = "main";
                        }elseif($testRt[$i] == "@page" && !isset($route[$i])){
                            $route[$i] = "home";
                        }
                    }
                    // Get the key/value pair to use
                    // If it is null then this isn't a valid route
                    // and start testing next route
                    $keyVal = $this->getKeyValue($testRt[$i], $route[$i]);
                    if($keyVal === null){
                        $match  = false;
                        $params = array();
                        break;
                    }
                    // Test for int types
                    // If it is not an int when required
                    // start testing next route
                    if(isset($testRt[$i])){
                        $intTest = explode("=", $testRt[$i]);
                        if(preg_match("/^#/", $intTest[0])){
                            if(!ctype_digit($keyVal["val"])){
                                $match  = false;
                                $params = array();
                                break;
                            }
                        }
                    }
                    // Add to array and set page/action if correct
                    $params[$keyVal["key"]] = $keyVal["val"];
                    if($keyVal["key"] == "page"){
                        $this->setPage($route[$i]);
                    }elseif($keyVal["key"] == "action"){
                        $this->setAction($route[$i]);
                    }
                }elseif(!isset($route[$i])){
                    $match  = false;
                    $params = array();
                    break;
                }else{
                    $match  = false;
                    $params = array();
                    break;
                }
            }
            if($match){
                $_GET = array_merge($_GET, $params);
                if(isset($useRt[1])){
                    $defaults = explode(",", $useRt[1]);
                    foreach($defaults as $default){
                        $defaultItem = explode("=", $default);
                        $is_param    = preg_match("/^@|^#/", $defaultItem[0]);
                        if($is_param){
                            $key  = str_replace(array("@", "#"), "", $defaultItem[0]);
                            $_GET = array_merge($_GET, array($key => $defaultItem[1]));
                            if($key == "action"){
                                $this->setAction($defaultItem[1]);
                            }
                            if($key == "page"){
                                $this->setPage($defaultItem[1]);
                            }
                        }
                    }
                }
                $count   = 0;
                preg_replace("/^\/?ajax\//i", "", $path, -1, $count);
                $is_ajax = (bool)$count;
                $this->setIsAjax($is_ajax);
                return;
            }
        }
        // No route was found, set default route
        if(empty($params)){
            loadDefault:
            $count   = 0;
            preg_replace("/^\/?ajax\//i", "", $path, -1, $count);
            $is_ajax = (bool)$count;
            $params  = explode("/", trim($path, "/"));
            $this->setPage(isset($params[0]) ? $params[0] : "Home");
            $this->setAction(isset($params[1]) ? $params[1] : "main");
            $this->setIsAjax($is_ajax);
        }
        //var_dump($_GET, $params);
        $_GET = array_merge($_GET, $params);
    }

    private function getKeyValue($testItem, $routeItem){
        $keyVal = explode("=", $testItem);
        $items  = count($keyVal);
        //var_dump($testItem, $routeItem, $keyVal);
        if($items == 2 && $routeItem == $keyVal[1]){
            $key = preg_replace("/@|#/", "", $keyVal[0], 1);
            $val = $keyVal[1];
        }else{
            if($items == 2){
                return null;
            }else{
                $key = preg_replace("/@|#/", "", $testItem, 1);
                $val = $routeItem;
            }
        }
        return array("key" => $key, "val" => $val);
    }

    /**
     * Runs before the page call (should be overridden)
     */
    public function runBefore(){
// To use this function override it in the Page's class
    }

    /**
     * Runs after the page call (should be overridden)
     */
    public function runAfter(){
// To use this function override it in the Page's class
    }

    /**
     * Displays a 404 page. Error files are located in "Zing/Errors".
     */
    final public function notFound(){
        header("HTTP/1.0 404 Not Found");
        header("Status: 404 Not Found");
        $not_found = $this->root . "/Zing/Errors/404.php";
        if(is_file($not_found)){
            require_once $not_found;
        }else{
            echo "<!DOCTYPE html><html><head><title>Page Not Found</title></head><body><h1>Page Not Found</h1><p>The Page you are looking for was not found.</p></body></html>";
        }
        exit;
    }

    final public function setTitle($title){
        $this->pageTitle = $title;
    }

    /**
     * Loads the template for the current page
     * @param class $class
     */
    final protected function loadTemplates(){
        $templates = $this->root . "/Websites/Templates/";
        if(!isset($this->config["host"])){
            throw new Exception("Host Not Found");
        }
        $templates = $this->root . "/Websites/Templates/";
        $extention = $this->tpl->getFileExtention();

        $shell_loaded = false;
        $header       = $templates . $this->headerTpl . "." . $extention;
        if(!empty($this->mainTpl)){
            $main = $templates . $this->mainTpl . "." . $extention;
        }else{
            $main = $templates . ucfirst(Zing::$page) . "/" . Zing::$action . "." . $extention;
        }
        if(!is_file($main) && !$this->pageExists){
            throw new Exception("Template Not Found.");
        }
        $footer = $templates . $this->footerTpl . "." . $extention;
        if($this->tplShell !== null){
            $shell = $templates . "Shells/" . $this->tplShell . "." . $extention;
            if(is_file($shell) && !$shell_loaded){
                $this->tpl->assign("file", $main);
                $this->tpl->display($shell);
            }
            $shell_loaded = true;
        }
        $loadedTpl = false;
        if(!empty($this->pageTitle)){
            $this->tpl->assign("PageTitle", $this->pageTitle);
        }
        if(is_file($header) && (is_file($main) || Zing::$noBody) && !$shell_loaded){
            $this->tpl->display($header);
            $loadedTpl = true;
        }

        if(is_file($main) && !$shell_loaded){
            $this->tpl->display($main);
            $loadedTpl = true;
        }

        if(is_file($footer) && (is_file($main) || Zing::$noBody) && !$shell_loaded){
            $this->tpl->display($footer);
            $loadedTpl = true;
        }
        return $loadedTpl;
    }

    /**
     * Sets the location of the header of the current file relative to the
     * templates directory. By default Global/header.tpl is used.
     * @param string $tpl
     */
    final protected function setHeader($tpl){
        $this->headerTpl = $tpl;
    }

    /**
     * Sets the location of the footer of the current file relative to the
     * templates directory. By default Global/footer.tpl is used.
     * @param string $tpl
     */
    final protected function setFooter($tpl){
        $this->footerTpl = $tpl;
    }

    /**
     * Sets the location of the main template to use for the current file.
     * @param string $tpl
     */
    final protected function setTemplate($tpl){
        $this->mainTpl = $tpl;
    }

    /**
     * Sets a shell template to use on the page
     * @param string $tpl
     */
    final protected function setShell($tpl){
        $this->tplShell = $tpl;
    }

    /**
     * Loads the current page and action for use.
     * If a Page is not found, attempt to load the templates.
     */
    private function load(){
        if(!isset($this->config["host"])){
            throw new Exception("Host Not Found");
        }
        if(isset($this->config["route"])){
            Zing::$page   = isset($this->config["route"]["page"]) ? $this->config["route"]["page"] : Zing::$page;
            Zing::$action = isset($this->config["route"]["action"]) ? $this->config["route"]["action"] : Zing::$action;
        }
        $page_file = __DIR__ . "/../Websites/Pages/" . ucfirst(Zing::$page) . ".php";
        if(is_file($page_file)){
            require_once $page_file;
            Zing::$page   = ucfirst(Zing::$page);
            Zing::$action = lcfirst(Zing::$action);
            return true;
        }else{
            try{
                if(!Zing::$isAjax){
                    $this->loadTemplates();
                    return false;
                }
            }catch(Exception $e){
                $this->notFound();
            }
        }
        return false;
    }

    final public function getWidget($widgetName, array $settings = array()){
        $widgetName = "\\Widgets\\" . str_replace("/", "\\", $widgetName) . "\\$widgetName";
        $widget     = new $widgetName();
        $opts       = $widget->setDefaultOptions();
        $widget->setOptions($opts);
        if(!empty($settings)){
            $widget->setOptions($settings);
        }
        $widget->runWidget();
        $wInfo       = new ReflectionClass($widget);
        $css         = dirname($wInfo->getFilename()) . "/css/*.css";
        $files       = glob($css);
        $styleSheets = array();
        foreach($files as $file){
            $styleSheets[] = "<style>" . file_get_contents($file) . "</style>";
        }
        Zing::$widgets[] = array(
            "css"      => $styleSheets,
            "instance" => $widget
        );
        return $widget->getHtml();
    }

    /**
     * Executes the current page and action.
     */
    /* private function exec(){
      $this->runPage();
      } */

    /**
     * Runs the page
     */
    private function runPage(){
        try{
            $reflection = new ReflectionMethod(Zing::$page, Zing::$action);
        }catch(Exception $e){
            $reflection = new ReflectionMethod(Zing::$page, "catchAll");
            $this->setAction("catchAll");
        }
        if($reflection->isPublic()){
            $class      = new Zing::$page();
            $class->initPage($this->config, $this->fullConfig);
            Zing::$page = Zing::$page;
            $action     = Zing::$isAjax ? Zing::$action . "Ajax" : Zing::$action;
            $class->runBefore();
            try{
                call_user_func_array(array($class, Zing::$action), array());
            }catch(Exception $e){
                echo $e->getMessage() . " in <b>" . $e->getFile() . "</b> on line " . $e->getLine();
                exit;
            }
            $class->runAfter();

            $this->pageExists = true;
            if(!Zing::$isAjax){
                $class->loadTemplates();
            }
        }
    }

    /**
     * Initialize the webpage to be used.
     * @param array $config
     */
    private function initPage($config, $fullConfig){
        $this->config     = $config;
        $this->fullConfig = $fullConfig;
        $this->setupDatabases();
    }

    /**
     * Get the configuration for the current website getting loaded.
     * @return void
     */
    private function getWebsiteConfig(){
        foreach($this->fullConfig["websites"] as $website){
            if(strtolower($website["host"]) === $this->host || (isset($website["alias"]) && in_array($this->host, $website["alias"]))){
                $this->config = $website;
                return;
            }
        }
    }

    /**
     * Prepare the databases that are in the config file.
     * This won't actually connect to the database
     */
    private function setupDatabases(){
// Setup Pirmary global databases
        if(isset($this->fullConfig["databases"]) && is_array($this->fullConfig["databases"])){
            foreach($this->fullConfig["databases"] as $name => $data){
                $this->db[$name] = $this->DBO->init($this->config);
                if(!isset($data["dsn"])){
                    $data["dsn"] = "mysql";
                }
                $this->db[$name]->setConnectionParams($data);
            }
        }
// Setup loacal database (duplicates override global databases)
        if(isset($this->config["databases"]) && is_array($this->config["databases"])){
            foreach($this->config["databases"] as $name => $data){
                $this->db[$name] = $this->DBO->init($this->config);
                if(!isset($data["dsn"])){
                    $data["dsn"] = "mysql";
                }
                $this->db[$name]->setConnectionParams($data);
            }
        }
    }

    /**
     *
     * @param string $name
     * @return DBO
     */
    final protected function dbo($name){
        if(!array_key_exists($name, $this->db)){
            throw new Exception("The database '$name' has not been defined.");
        }
        return $this->db[$name];
    }

}

/**
 * Loads classes so the user doesn't have to.
 *
 * We use spl_autoload_register() so the user can create their own autoloader
 * if needed using either a custom spl_autoload_register() or __autoload().
 *
 * When using Zing, the user shouldn't need to create thier own autoloader
 * Everything should be loaded automaically already, if not they are using
 * Zing incorrectly.
 */
spl_autoload_register(function($class){
    $class = str_replace("\\", "/", $class);
    $file  = __DIR__ . "/src/$class.php";
    if(is_file($file)){
        require_once $file;
        return;
    }
    $file = __DIR__ . "/src/Plugins/$class.php";
    if(is_file($file)){
        require_once $file;
        return;
    }
    $file = __DIR__ . "/../Websites/Helpers/$class.php";
    if(is_file($file)){
        require_once $file;
        return;
    }
    $file = __DIR__ . "/$class.php";
    if(is_file($file)){
        require_once $file;
        return;
    }
});
