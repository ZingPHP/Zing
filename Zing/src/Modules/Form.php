<?php

use Modules\Form;
use Modules\FormItem;
use Modules\Module;

namespace Modules;

class Form extends Module{

    protected
            $formItems = array(),
            $action    = "",
            $method    = "get",
            $formName  = "",
            $errors    = array();

    /**
     * Creates a form
     * @return \Form
     */
    public function createForm($name, $params = array()){
        $form               = new Form();
        $params["formName"] = $name;
        $form->setForm($params);
        return $form;
    }

    /**
     * Creates an item in a form
     * @param string $type
     * @return \FormItem
     */
    public function createFormItem(){
        $fi                = new FormItem($this->formName);
        $this->formItems[] = $fi;
        return $fi;
    }

    public function getForm(){
        $rform = array();
        /* if(!isset($_SESSION["ZingForm"])){
          return;
          } */
        /** @var $item FormItem */
        foreach($this->formItems as $item){
            $params   = $item->getParams();
            $remember = isset($params["remember"]) ? (bool)$params["remember"] : true;
            $html     = $item->getHtml();
            $value    = "";
            if($remember){
                $form   = $this->formName;
                $method = $_SESSION["ZingForm"][$form]["info"]["method"];
                foreach($_SESSION["ZingForm"][$form]["data"] as $key => $val){
                    if(isset($_POST["ZingForm"]) || isset($_GET["ZingForm"])){
                        if($method === "post" && isset($_POST["ZingForm"][$form][$key]) && $_SESSION["ZingForm"][$form]["data"][$key]["label"] == $item->getLabel()){
                            $value = $_POST["ZingForm"][$form][$key];
                            break;
                        }elseif($method === "get" && isset($_GET["ZingForm"][$form][$key]) && $_SESSION["ZingForm"][$form]["data"][$key]["label"] == $item->getLabel()){
                            $value = $_GET["ZingForm"][$form][$key];
                            break;
                        }
                    }
                }
                $value = 'value="' . htmlentities($value) . '"';
            }
            $replacement = str_replace("__ZINGFORM_EXTRA__", $value, $html);

            $rform[$item->getLabel()] = $replacement;
        }
        $this->save();
        return $rform;
    }

    public function buildForm(){
        $this->save();
        $opt = '<form action="' . $this->action . '" method="' . $this->method . '">';
        /** @var $item FormItem */
        foreach($this->formItems as $item){
            $opt .= $item->getHtml();
        }
        $opt .= '</form>';
        return $opt;
    }

    public function getErrors(){
        return $this->errors;
    }

    /**
     *
     * @param string $form_name
     * @return Form
     */
    public function rebuildForm($form_name){
        $action = $_SESSION["ZingForm"][$form_name]["info"]["action"];
        $method = $_SESSION["ZingForm"][$form_name]["info"]["method"];
        $form   = $this->createForm($form_name, array("method" => $method, "action" => $action));
        /** @var $item FormItem */
        foreach($_SESSION["ZingForm"][$form_name]["data"] as $item){
            $label = $item["label"];
            $form->createFormItem()->$item["type"]($label, $item["params"]);
        }
        return $form;
    }

    public function validate($form){
        $method = $_SESSION["ZingForm"][$form]["info"]["method"];
        foreach($_SESSION["ZingForm"][$form]["data"] as $key => $item){
            $label = $item["label"];
            $value = "";
            if($method === "post" && isset($_POST["ZingForm"][$form][$key])){
                $value = $_POST["ZingForm"][$form][$key];
            }elseif($method === "get" && isset($_GET["ZingForm"][$form][$key])){
                $value = $_GET["ZingForm"][$form][$key];
            }
            if(isset($item["params"])){
                $this->testItem($label, $value, $item["params"]);
            }
        }
        return count($this->errors) > 0 ? false : true;
    }

    private function testItem($label, $value, $tests){
        $format   = isset($tests["format"]) ? $tests["format"] : "";
        $required = (bool)(isset($tests["required"]) ? $tests["required"] : true);
        $minlen   = isset($tests["minlen"]) ? (int)$tests["minlen"] : null;
        $maxlen   = isset($tests["maxlen"]) ? (int)$tests["maxlen"] : null;

        if($this->isEmpty($value) && $required){
            $this->errors[$label][] = "Field is required";
        }
        if(!empty($minlen) && strlen($value) < $minlen){
            $this->errors[$label][] = "Field must be at lest $minlen characters";
        }
        if(!empty($maxlen) && strlen($value) > $maxlen){
            $this->errors[$label][] = "Field must be no more than $maxlen characters";
        }
        if(strtolower($format) === "email"){
            if(!$this->validEmail($value)){
                $this->errors[$label][] = "Invalid email";
            }
        }elseif(strtolower($format) === "number"){
            if(!ctype_digit($value)){
                $this->errors[$label][] = "Numbers only";
            }
        }elseif(!empty($format)){
            $pattern = str_split(trim($format));
            $split   = str_split(trim($value));
            if(count($pattern) != count($split)){
                $this->errors[$label][] = "Invalid pattern format '$format'";
            }else{
                foreach($pattern as $pk => $pv){
                    if($pv === "." && isset($split[$pk])){
                        continue;
                    }elseif($pv === "#" && ctype_digit($split[$pk])){
                        continue;
                    }elseif($pv === "%" && ctype_alpha($split[$pk])){
                        continue;
                    }elseif($pv === "&" && ctype_alnum($split[$pk])){
                        continue;
                    }elseif($pv === $split[$pk]){
                        continue;
                    }else{
                        $this->errors[$label][] = "Invalid pattern format '$format'";
                        break;
                    }
                }
            }
        }
    }

    private function isEmpty($string){
        return strlen(str_replace(array(" ", "\t", "\n", "\r"), "", $string)) > 0 ? false : true;
    }

    private function save(){
        unset($_SESSION["ZingForm"]);
        /** @var $item FormItem */
        foreach($this->formItems as $item){
            $type   = $item->getType();
            $name   = $item->getName();
            $params = $item->getParams();
            $label  = $item->getLabel();

            $_SESSION["ZingForm"][$this->formName]["info"]["action"] = $this->action;
            $_SESSION["ZingForm"][$this->formName]["info"]["method"] = $this->method;
            $_SESSION["ZingForm"][$this->formName]["data"][$name]    = array(
                "type"   => $type,
                "label"  => $label,
                "params" => $params
            );
        }
    }

    private function setForm($params){
        $this->action   = isset($params["action"]) ? $params["action"] : "";
        $this->method   = isset($params["method"]) ? $params["method"] : "";
        $this->formName = isset($params["formName"]) ? $params["formName"] : "";
        if(!isset($_SESSION["ZingForm"][$this->formName])){
            $_SESSION["ZingForm"][$this->formName]["info"]           = array();
            $_SESSION["ZingForm"][$this->formName]["info"]["method"] = $this->method;
            $_SESSION["ZingForm"][$this->formName]["data"]           = array();
        }
    }

    protected function validEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}

class FormItem{

    protected
            $html   = "",
            $name   = "",
            $type   = "",
            $form   = "",
            $params = array(),
            $label  = "";

    public function __construct($form){
        $this->form = $form;
    }

    public function text($label, $params){
        $p           = $this->makeAttrs($params);
        $this->name  = $this->makeName();
        $this->type  = "text";
        $this->label = $label;
        $this->html  = '<input name="ZingForm[' . $this->form . '][' . $this->name . ']" type="text" ' . $p . ' __ZINGFORM_EXTRA__ />';
        return $this;
    }

    public function password($label, $params){
        $p           = $this->makeAttrs($params);
        $this->name  = $this->makeName();
        $this->type  = "password";
        $this->label = $label;
        $this->html  = '<input name="ZingForm[' . $this->form . '][' . $this->name . ']" type="password" ' . $p . ' __ZINGFORM_EXTRA__ />';
        return $this;
    }

    public function submit($label, $params){
        $p           = $this->makeAttrs($params);
        $this->name  = $this->makeName();
        $this->type  = "submit";
        $this->label = $label;
        $this->html  = '<input name="ZingForm[' . $this->form . '][' . $this->name . ']" type="submit" ' . $p . ' __ZINGFORM_EXTRA__ />';
        return $this;
    }

    public function getHtml(){
        return $this->html;
    }

    public function getName(){
        return $this->name;
    }

    public function getLabel(){
        return $this->label;
    }

    public function getType(){
        return $this->type;
    }

    public function getParams(){
        return $this->params;
    }

    private function makeName(){
        return md5(time() . uniqid());
    }

    private function makeAttrs($params){
        $this->params = $params;
        $str          = "";
        foreach($params["attr"] as $key => $val){
            $str .= $key . '="' . htmlentities($val) . '" ';
        }
        return trim($str);
    }

}
