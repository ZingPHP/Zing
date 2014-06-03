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

}
