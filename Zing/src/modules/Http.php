<?php

class Http extends Module{

    private $opts = array();

    public function prepare($url, $postdata = array()){
        $post       = !empty($postdata);
        $this->opts = array(
            CURLOPT_URL            => $url,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER         => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => http_build_query($postdata),
            CURLOPT_POST           => $post,
        );
    }

    public function sendRequest(){
        $ch   = curl_init();
        curl_setopt_array($ch, $this->opts);
        $data = curl_exec($ch);
        return $data;
    }

    public function go($location){
        header("Location: $location");
        exit;
    }
    
}
