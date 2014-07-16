<?php

use Modules\Module;
use Modules\Twitter\TwitterOAuth;

namespace Modules;

class Twitter extends Module{

    private $oauth = null;

    /**
     * Sets initialization data
     * @param array $config
     * @return TwitterOAuth
     */
    public function init(array $config){
        $this->oauth = new TwitterOAuth($config);
        return $this->oauth;
    }

    /**
     * Gets data from a twitter call
     * @param string $call
     * @param array $params
     * @return mixed
     */
    public function get($call, array $params = array()){
        return $this->oauth->get($call, $params);
    }

    /**
     * Posts data to a twitter call
     * @param string $call
     * @param array $postParams
     * @param array $getParams
     * @return mixed
     */
    public function post($call, array $postParams = null, array $getParams = null){
        return $this->oauth->post($call, $postParams, $getParams);
    }

    /**
     * Formats a twitter string
     * @param string $string
     * @return string
     */
    public function format($string){
        $r = preg_replace("/(http(s)?:\/\/.+?)(\s|$)/", '<a target="blank" href="$1">$1</a>$3', $string);
        $r = preg_replace("/((\#)(.+?))(\W|\s|$)/", '<span class="twitter-hash">#</span><a target="blank" href="https://twitter.com/hashtag/$3?src=hash">$3</a>$4', $r);
        $r = preg_replace("/((\@)(.+?))(\W|\s|$)/", '<span class="twitter-mention">@</span><a target="blank" href="https://twitter.com/$3">$3</a>$4', $r);
        return $r;
    }

}
