<?php

$config = array(
    "websites" => array(
        array(
            "name"      => "Zing",
            "host"      => "zing.com",
            "alias"     => array(
                "zingphp.com",
                "monkey.com"
            ),
            "path"      => "/Websites/zingTest",
            "databases" => array(
                "localhost" => array(
                    "hostname" => "localhost",
                    "username" => "root",
                    "password" => "",
                    "database" => "test"
                )
            )
        ),
    )
);
