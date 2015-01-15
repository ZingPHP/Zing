<?php

/**
 *
 * @author Ryan Naddy <rnaddy@corp.acesse.com>
 * @name Zen.php
 * @version 1.0.0 Jan 15, 2015
 */
class HTMLObject{

    protected
            $dom,
            $objectString = "",
            $objects      = array(),
            $parent,
            $last,
            $previousSibling;

    protected function tag($string){
        if(!preg_match("/^[\[.#+']/", $string)){
            return preg_split("/[.#+']/", $string)[0];
        }
        return false;
    }

    protected function classes($string){
        preg_match_all("/\.(.+?)(?=[\[.#'+]|$)/", $string, $matches);
        return implode(" ", $matches[1]);
    }

    protected function id($string){
        preg_match("/#(.+?)([\[.#'+]|$)/", $string, $matches);
        return isset($matches[1]) ? $matches[1] : "";
    }

    protected function attributes($string){
        preg_match("/\[(.+?)\]/", $string, $matches);
        $attrs = array();
        if(!empty($matches)){
            $keyvals = explode(",", $matches[1]);
            foreach($keyvals as $item){
                $kv            = explode("=", $item, 2);
                $attrs[$kv[0]] = $kv[1];
            }
        }
        return $attrs;
    }

    protected function text($string){
        preg_match("/'(.+)'/", $string, $matches);
        return isset($matches[1]) ? $matches[1] : "";
    }

    protected function isBlockElement($tag){
        return !in_array($tag, array("hr", "br", "input"));
    }

    protected function parse(){
        if(empty($this->objectString) && !is_string($this->objectString)){
            throw new Exception("Invalid Object String");
        }
        $this->objects = preg_split("/([>+])/", $this->objectString, null, PREG_SPLIT_DELIM_CAPTURE);
        foreach($this->objects as $key => $object){
            $prevOperator = isset($this->objects[$key - 1]) ? $this->objects[$key - 1] : null;

            if(in_array($object, array(">", "+"))){
                continue;
            }

            $tag     = $this->tag($object);
            $classes = $this->classes($object);
            $id      = $this->id($object);
            $attrs   = $this->attributes($object);
            $text    = $this->text($object);
            if(empty($this->parent)){
                $this->parent = new DOMElement($tag, $text);
                $this->dom->appendChild($this->parent);
                if(!empty($classes)){
                    $this->parent->setAttribute("class", $classes);
                }
                if(!empty($id)){
                    $this->parent->setAttribute("id", $id);
                }
                if(!empty($attrs)){
                    foreach($attrs as $key => $val){
                        $this->parent->setAttribute($key, $val);
                    }
                }
            }else{
                $element = new DOMElement($tag, $text);
                if($prevOperator == ">"){
                    $this->parent->appendChild($element);
                    $this->parent = $element;
                }elseif($prevOperator == "+"){
                    $this->last->parentNode->appendChild($element);
                }else{
                    $this->dom->appendChild($element);
                }
                $this->last = $element;

                if(!empty($classes)){
                    $element->setAttribute("class", $classes);
                }
                if(!empty($id)){
                    $element->setAttribute("id", $id);
                }
                if(!empty($attrs)){
                    foreach($attrs as $key => $val){
                        $element->setAttribute($key, $val);
                    }
                }
            }
        }
    }

    public function find($pattern){
        $objects = preg_split("/([>+])/", $pattern, null, PREG_SPLIT_DELIM_CAPTURE);
        $results = array();
        foreach($objects as $key => $object){
            $tag     = $this->tag($object);
            $classes = $this->classes($object);
            $id      = $this->id($object);
            $text    = $this->text($object);

            if(!preg_match("/^[.#]/", $object)){

            }

            $this->dom->getElementsByTagName($tag);
        }
        return $results;
    }

    public function setAttribute($pattern, $attr, $value){

    }

    public function bindValue(){

    }

    public function __construct($string = ""){
        $this->objectString = $string;
        $this->dom          = new DOMDocument();
    }

    public function getElement(){
        $this->parse();
        return $this->dom->saveHTML();
    }

}
