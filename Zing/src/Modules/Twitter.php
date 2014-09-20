<?php

namespace Modules;

class Twitter extends Module{

    private $oauth = null;

    /**
     * Initialize the twitter connection
     * @param array $config
     */
    public function init(array $config){
        $this->oauth = new \Modules\Twitter\TwitterOAuth($config);
    }

    /**
     * Makes a get request to the twitter API
     * @param string $call
     * @param array $params
     * @return array|object
     */
    public function get($call, array $params = array()){
        return $this->oauth->get($call, $params);
    }

    /**
     * Makes a post request to the twitter API
     * @param string $call
     * @param array $params
     * @return array|object
     */
    public function post($call, array $params = array()){
        return $this->oauth->post($call, $params);
    }

    /**
     * Formats a tweet with links from hashtags and mentions
     * @param string $string
     * @return string
     */
    public function format($string){
        $r = preg_replace("/(http(s)?:\/\/.+?)(\s|$)/", '<a target="blank" href="$1">$1</a>$3', $string);
        $r = preg_replace("/((\#)(.+?))(\W|\s|$)/", '<span class="twitter-hash">#</span><a target="blank" href="https://twitter.com/hashtag/$3?src=hash">$3</a>$4', $r);
        $r = preg_replace("/((\@)(.+?))(\W|\s|$)/", '<span class="twitter-hash">@</span><a target="blank" href="https://twitter.com/$3">$3</a>$4', $r);
        return $r;
    }

}
