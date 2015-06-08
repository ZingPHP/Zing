<?php

$config = array(
    "websites" => array(
        array(
            "name"      => "ZingPHP",
            "host"      => "zingframework.com",
            "tplEngine" => "Twig",
            "alias"     => array(
                "zingframework.org",
                "beta.zingframework.com"
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
