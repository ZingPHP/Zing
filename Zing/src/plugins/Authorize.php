<?php

class Authorize{

    public $errors;
    protected
            $invoceNumber = 0,
            $price        = 0,
            $cardNumber   = "",
            $memberID     = 0,
            $cardInfo     = null,
            $cardExp      = "",
            $cardCode     = "",
            $productId    = 0;
    private $username,
            $password,
            $url            = "https://secure.authorize.net/gateway/transact.dll";

    public function setLogin($username, $password){
        $this->username = $username;
        $this->password = $password;
    }

    public function setInvoiceNumber($number){
        $this->invoceNumber = $number;
    }

    public function setPrice($price){
        $this->price = $price;
    }

    public function setCardNumber($number){
        $this->cardNumber = $number;
    }

    public function setExp($expDate){
        $this->cardExp = $expDate;
    }

    public function sendPayment(){
        $post_data = array(
            'x_tran_key'       => $this->username,
            'x_login'          => $this->password,
            "x_method"         => "CC",
            "x_type"           => "AUTH_CAPTURE",
            "x_version"        => "3.1",
            "x_delim_data"     => "TRUE",
            "x_delim_char"     => "|",
            "x_relay_response" => "FALSE",
            "x_invoice_num"    => $this->invoceNumber . "-" . date("dmy"),
            "x_amount"         => $this->price,
            "x_card_num"       => $this->cardNumber,
            "x_exp_date"       => $this->cardExp,
            "x_card_code"      => $this->cardCode,
            "x_description"    => "Acesse Marketing Product #$this->productId",
                /* "x_cust_id"        => $member->member_id,
                  "x_first_name"     => $member->first_name,
                  "x_last_name"      => $member->last_name,
                  "x_address"        => $cardInfo->street_b,
                  "x_city"           => $cardInfo->city_b,
                  "x_state"          => $cardInfo->state_b,
                  "x_zip"            => $cardInfo->postal_b,
                  "x_country"        => $this->getCountry($cardInfo->country_b_id),
                  "x_email"          => $member->email,
                  "x_customer_ip"    => $this->getIPAddress(), */
        );

        if(isset($_SERVER["ENVIRONMENT"]) && $_SERVER["ENVIRONMENT"] == "dev"){
            $post_data['x_test_request'] = "TRUE";
        }
        $http = new Http();
        $http->prepare($this->url, $post_data);
        $http->sendRequest();
    }

}
