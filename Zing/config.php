<?php

$config = array(
    "websites" => array(
        array(
            "name"      => "ZingPHP",
            "host"      => "zingphp.org",
            "tplEngine" => "Twig",
            "alias"     => array(
                "zing.com",
                "monkey.com",
                "172.21.20.75"
            ),
            "databases" => array(
                "localhost" => array(
                    "hostname" => "localhost",
                    "username" => "zingphp",
                    "password" => "abc123",
                    "database" => "test"
                )
            )
        ),
    )
);
