<?php

/**
 * @property Modules\Input $input Functionality for global variables
 * @property Modules\Http $http Functionality to Http
 * @property Modules\DBO $dbo Functionality to connect to databases
 * @property Smarty $smarty Functionality for smarty templates
 * @property Modules\Form $form Functionality for forms and form validation
 * @property Modules\User $user Functionality to work with users
 * @property Modules\Mail $mail Functionality to work with emails
 * @property Modules\Util $util Functionality to access utilites
 * @property Modules\File $file Functionality to access files
 * @property Modules\Math $math Functionality to access math
 * @property Modules\Date $date Functionality to access dates
 * @property Modules\Validate $validate Functionality to access dates
 * @property Modules\Cache $cache Functionality to access dates
 * @property Modules\Twitter $twitter Twitter Accessability
 */
class Zing{

// Static properties
    public static $page         = "Home";
    public static $action       = "main";
    public static $params       = array();
    public static $isAjax       = false;
    protected
            $db               = array(),
            $host             = "",
            $root             = "",
            $pageExists       = false,
            $namespace        = "";
    private
            $headerTpl          = "Global/header",
            $footerTpl          = "Global/footer",
            $mainTpl            = "",
            $tplShell           = null,
            $config             = array(),
            $fullConfig         = array(),
            $modules            = array(
                "dbo"      => false,
                "input"    => false,
                "http"     => false,
                "smarty"   => false,
                "form"     => false,
                "user"     => false,
                "mail"     => false,
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
            $class = ucfirst($name);
            if($name !== "smarty"){
                $class = "Modules\\$class";
            }
            $this->$name          = new $class($this->config);
            $this->modules[$name] = true;
            return $this->$name;
        }
    }

    public function __construct(){
//$this->db   = (object)$this->db;
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
        Zing::$page = trim($page, "/");
    }

    /**
     * Sets the current action to run (Name of the method).
     * @param string $action
     */
    final public function setAction($action){
        $_GET["action"] = $action;
        Zing::$action = $action;
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
                    echo "Loaded";
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
                $this->notFound();
            }
        }
    }

    final public function setRoute($path){
        if(isset($this->config["route"])){
            return;
        }
        $routes = $this->config["routes"];
        $route  = explode("/", trim($path, "/"));
        foreach($routes as $rt){
            $testRt = explode("/", trim($rt, "/"));
            $params = array();
            $match  = true;
            for($i = 0; $i < count($testRt); $i++){
                $is_param = preg_match("/^@/", $testRt[$i]);
                if(!$is_param && isset($route[$i]) && $route[$i] == $testRt[$i]){
                    /* if($i == 0){
                      $params["page"] = $route[$i];
                      $this->setPage($route[$i]);
                      }elseif($i == 1){
                      $params["action"] = $route[$i];
                      $this->setAction($route[$i]);
                      } */
                    continue;
                }elseif($is_param && isset($route[$i])){
                    $v          = preg_replace("/@/", "", $testRt[$i], 1);
                    $params[$v] = $route[$i];
                    if($v == "page"){
                        $this->setPage($route[$i]);
                    }elseif($v == "action"){
                        $this->setAction($route[$i]);
                    }
                }else{
                    $params = array();
                    break;
                }
            }
            if($match){
                return;
            }
        }
        if(empty($params)){
            $count   = 0;
            preg_replace("/^ajax\//i", "", $path, -1, $count);
            $is_ajax = (bool)$count;
            $params  = explode("/", trim($path, "/"));
            $this->setPage(isset($params[0]) ? $params[0] : "Home");
            $this->setAction(isset($params[1]) ? $params[1] : "main");
            $this->setIsAjax($is_ajax);
        }
        //var_dump($_GET, $params);
        $_GET = array_merge($_GET, $params);
    }

    final public function linkRoutes(array $links = array()){
        $query = 1;
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

    /**
     * Loads the template for the current page
     * @param class $class
     */
    final protected function loadTemplates(){
        if(!isset($this->config["host"])){
            throw new Exception("Host Not Found");
        }
        $templates = $this->root . "/Websites/Templates/";

        $shell_loaded = false;
        $header       = $templates . $this->headerTpl . ".tpl";
        if(!empty($this->mainTpl)){
            $main = $templates . $this->mainTpl . ".tpl";
        }else{
            $main = $templates . Zing::$page . "/" . Zing::$action . ".tpl";
        }
        if(!is_file($main) && !$this->pageExists){
            throw new Exception("Template Not Found.");
        }
        $footer = $templates . $this->footerTpl . ".tpl";
        if($this->tplShell !== null){
            $shell = $templates . "Shells/" . $this->tplShell . ".tpl";
            if(is_file($shell) && !$shell_loaded){
                $this->smarty->assign("file", $main);
                $this->smarty->display($shell);
            }
            $shell_loaded = true;
        }
        $loadedTpl = false;
        if(is_file($header) && is_file($main) && !$shell_loaded){
            $this->smarty->display($header);
            $loadedTpl = true;
        }

        if(is_file($main) && !$shell_loaded){
            $this->smarty->display($main);
            $loadedTpl = true;
        }

        if(is_file($footer) && is_file($main) && !$shell_loaded){
            $this->smarty->display($footer);
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
            Zing::$page = isset($this->config["route"]["page"]) ? $this->config["route"]["page"] : Zing::$page;
            Zing::$action = isset($this->config["route"]["action"]) ? $this->config["route"]["action"] : Zing::$action;
        }
        $page_file = __DIR__ . "/../Websites/Pages/" . ucfirst(Zing::$page) . ".php";
        if(is_file($page_file)){
            require_once $page_file;
            Zing::$page = ucfirst(Zing::$page);
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
        $widgetName = "\\Widgets\\" . str_replace("/", "\\", $widgetName);
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
            $class  = new Zing::$page();
            $class->initPage($this->config);
            Zing::$page = Zing::$page;
            $action = Zing::$isAjax ? Zing::$action . "Ajax" : Zing::$action;
            $class->runBefore();
            call_user_func_array(array($class, Zing::$action), array());
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
    private function initPage($config){
        $this->config = $config;
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
                $this->db[$name] = $this->dbo->init($this->config);
                if(!isset($data["dsn"])){
                    $data["dsn"] = "mysql";
                }
                $this->db[$name]->setConnectionParams($data);
            }
        }
// Setup loacal database (duplicates override global databases)
        if(isset($this->config["databases"]) && is_array($this->config["databases"])){
            foreach($this->config["databases"] as $name => $data){
                $this->db[$name] = $this->dbo->init($this->config);
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
     * @return \Modules\DBO
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
    $file = __DIR__ . "/src/Modules/Smarty/$class.class.php";
    if(is_file($file)){
        require_once $file;
    }
});
