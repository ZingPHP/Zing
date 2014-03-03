<?php

/**
 * @property Input $input Functionality for global variables
 * @property Http $http Functionality to Http
 * @property Mysql $mysql Functionality to connect to databases
 * @property Smarty $smarty Functionality for smarty templates
 * @property Form $form Functionality for forms and form validation
 * @property User $user Functionality to work with users
 * @property Mail $mail Functionality to work with emails
 * @property Util $util Functionality to access utilites
 * @property File $file Functionality to access files
 * @property Math $math Functionality to access math
 * @property Date $date Functionality to access dates
 */
class Zing{

    // Static properties
    public static $page   = "Home";
    public static $action = "main";
    public static $isAjax = false;
    protected
            $db         = null,
            $host       = "",
            $root       = "";
    private
            $headerTpl    = "Global/header",
            $footerTpl    = "Global/footer",
            $mainTpl      = "",
            $tplShell     = null,
            $config       = array(),
            $fullConfig   = array(),
            $modules      = array(
                "mysql"  => false,
                "input"  => false,
                "http"   => false,
                "smarty" => false,
                "form"   => false,
                "user"   => false,
                "mail"   => false,
                "util"   => false,
                "file"   => false,
                "math"   => false,
                "date"   => false,
    );

    /**
     * Initiates modules on the fly.
     * @param string $name
     * @return instance
     */
    public function __get($name){
        if(array_key_exists($name, $this->modules) && !$this->modules[$name]){
            $class                = ucfirst($name);
            $this->$name          = new $class($this->config);
            $this->modules[$name] = true;
            return $this->$name;
        }
    }

    public function __construct(){
        //$this->db   = (object)$this->db;
        $this->db   = (object)array();
        $this->root = $_SERVER["DOCUMENT_ROOT"];
        if(isset($_GET["host"])){
            $this->host = $_GET["host"];
        }else{
            $this->host = $_SERVER["HTTP_HOST"];
        }
    }

    /**
     * Gets a value from the loaded configuration.
     * @param string $key
     * @return mixed
     */
    public function config($key){
        return Zing::$config[$key];
    }

    /**
     * Sets the current page (Name of the class).
     * @param string $page
     */
    public function setPage($page){
        Zing::$page = $page;
    }

    /**
     * Sets the current action to run (Name of the method).
     * @param string $action
     */
    public function setAction($action){
        Zing::$action = $action;
    }

    /**
     * Sets whether or not the current request is an ajax request.
     * @param boolean $is_ajax
     */
    public function setIsAjax($is_ajax){
        Zing::$isAjax = (bool)$is_ajax;
    }

    /**
     * Initialize the framework and website for usage.
     * @param array $config
     */
    public function init($config){
        $this->fullConfig = $config;
        $this->getWebsiteConfig();
    }

    /**
     * Loads the current page and action for use.
     */
    public function load(){
        $path      = isset($this->config["path"]) ? $this->config["path"] : "";
        $page_file = __DIR__ . "/.." . $path . "/Pages/" . ucfirst(Zing::$page) . ".php";
        if(is_file($page_file)){
            require_once $page_file;
            Zing::$page = ucfirst(Zing::$page);
            Zing::$action = lcfirst(Zing::$action);
        }else{
            $this->notFound();
        }
    }

    /**
     * Executes the current page and action.
     */
    public function exec(){
        try{
            $reflection = new ReflectionMethod(Zing::$page, Zing::$action);
            if($reflection->isPublic()){
                $class  = new Zing::$page();
                $class->initPage($this->config);
                Zing::$page = Zing::$page;
                $action = Zing::$isAjax ? Zing::$action . "Ajax" : Zing::$action;
                $class->runFirst();
                call_user_func_array(array($class, Zing::$action), array());
                $class->runLast();
                if(!Zing::$isAjax){
                    $class->loadTemplates();
                }
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->notFound();
        }
    }

    /**
     * Runs before the page call (should be overridden)
     */
    public function runFirst(){
        // To use this function override it in the Page's class
    }

    /**
     * Runs after the page call (should be overridden)
     */
    public function runLast(){
        // To use this function override it in the Page's class
    }

    /**
     * Loads the template for the current page
     * @param class $class
     */
    protected function loadTemplates(){
        $templates    = $this->root . $this->config["path"] . "/Templates/";
        $shell_loaded = false;
        $header       = $templates . $this->headerTpl . ".tpl";
        if(!empty($this->mainTpl)){
            $main = $templates . $this->mainTpl . ".tpl";
        }else{
            $main = $templates . Zing::$page . "/" . Zing::$action . ".tpl";
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
        if(is_file($header) && !$shell_loaded){
            $this->smarty->display($header);
        }

        if(is_file($main) && !$shell_loaded){
            $this->smarty->display($main);
        }

        if(is_file($footer) && !$shell_loaded){
            $this->smarty->display($footer);
        }
    }

    /**
     * Sets the location of the header of the current file relative to the
     * templates directory. By default Global/header.tpl is used.
     * @param string $tpl
     */
    protected function setHeader($tpl){
        $this->headerTpl = $tpl;
    }

    /**
     * Sets the location of the footer of the current file relative to the
     * templates directory. By default Global/footer.tpl is used.
     * @param string $tpl
     */
    protected function setFooter($tpl){
        $this->footerTpl = $tpl;
    }

    /**
     * Sets the location of the main template to use for the current file.
     * @param string $tpl
     */
    protected function setTemplate($tpl){
        $this->mainTpl = $tpl;
    }

    /**
     * Sets a shell template to use on the page
     * @param string $tpl
     */
    protected function setShell($tpl){
        $this->tplShell = $tpl;
    }

    /**
     * Displays a 404 page. Error files are located in "Zing/Errors".
     */
    public function notFound(){
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
            if(strtolower($website["host"]) === $this->host){
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
                $this->db->$name = $this->mysql->init($this->config);
                $this->db->$name->setConnectionParams($data);
            }
        }
        // Setup loacal database (duplicates override global databases)
        if(isset($this->config["databases"]) && is_array($this->config["databases"])){
            foreach($this->config["databases"] as $name => $data){
                $this->db->$name = $this->mysql->init($this->config);
                $this->db->$name->setConnectionParams($data);
            }
        }
    }

}

spl_autoload_register(function($class){
    $file = __DIR__ . "/src/modules/$class.php";
    if(is_file($file)){
        require_once $file;
        return;
    }
    $file = __DIR__ . "/src/plugins/$class.php";
    if(is_file($file)){
        require_once $file;
        return;
    }
    $file = __DIR__ . "/src/modules/Smarty/$class.class.php";
    if(is_file($file)){
        require_once $file;
    }
});
