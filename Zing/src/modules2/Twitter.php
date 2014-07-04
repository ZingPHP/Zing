<?php

namespace Modules;

class Twitter extends Module{

    private $oauth = null;

    public function init(array $config){
        $this->oauth = new \Modules\Twitter\TwitterOAuth($config);
    }

    public function get($call, array $params = array()){
        return $this->oauth->get($call, $params);
    }

    public function format($string){
        $r = preg_replace("/(http(s)?:\/\/.+?)(\s|$)/", '<a target="blank" href="$1">$1</a>$3', $string);
        $r = preg_replace("/((\#)(.+?))(\W|\s|$)/", '<span class="twitter-hash">#</span><a target="blank" href="https://twitter.com/hashtag/$3?src=hash">$3</a>$4', $r);
        $r = preg_replace("/((\@)(.+?))(\W|\s|$)/", '<span class="twitter-hash">@</span><a target="blank" href="https://twitter.com/$3">$3</a>$4', $r);
        return $r;
    }

}
