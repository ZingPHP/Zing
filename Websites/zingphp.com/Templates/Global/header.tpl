<!doctype html>
<html>
    <head>
        <title>ZINGPHP | PHP Framework</title>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <style>
            @import url(http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800);
            *{
                box-sizing: border-box;
            }
            html, body{
                background-color: #f1d13e;
                background-color: #7dcefd;
                font-family: 'Open Sans', Arial, "Trebuchet MS", "Helvetica Neue", Helvetica, Arial, sans-serif;
                padding: 0;
                margin: 0;
                height: 100%;
                font-size: 20px;
            }
            h1, h2, h3{
                font: 300 60px 'Open Sans', Arial, "Trebuchet MS", "Helvetica Neue", Helvetica, Arial, sans-serif;
                color: #3c7b9f;
                margin: 0;
                padding: 0;
            }
            a h1{
                text-decoration: none;
            }
            h1{
                line-height: 1em;
                font-size: 100px;
                text-transform: uppercase;
                letter-spacing: -1px;
                margin: 0 0 6px 0;
                float: left;
                margin-left: 50px;
            }
            h1 > b{
                color: #ffffff;
            }
            h1.blerb{
                font-size: 60px;
                text-align: center;
                width: 100%;
                float: left;
                margin-left: 0;
                color: #ffffff;
                margin-top: 150px;
                text-transform: none;
            }
            h2{
                font-size: 40px;
            }
            h2.blerb{
                text-align: center;
                width: 100%;
                float: left;
                margin-left: 0;
                text-transform: none;
            }
            h3{
                font-size: 30px;
            }
            nav{
                background-color: #333;
                background-color: #2e3234;
                float: right;
                margin: 0;
                padding: 0 50px;
            }
            nav > a{
                padding: 10px 20px;
                float: left;
                color: #ffffff;
                color: #747474;
                font-weight: bold;
                text-transform: uppercase;
                text-decoration: none;
            }
            nav > a:hover{
                background-color: #3c7b9f;
                color: #000;
                color: #fff;
            }
            .content{
                background-color: #ffffff;
                padding: 100px 50px;
                clear: both;
                /*height: 100%;*/
            }
            main{
                /*padding-top: 400px;*/
            }
            main > div.content > div.section{
                display: table;
                width: 90%;
                margin: auto;
                table-layout: fixed;
            }
            main > div.content > div.section > div{
                display: table-cell;
                padding: 40px;
            }
            main > div.content > div.section > div{
                text-align: center;
            }
            main > div.content > div.section > div .fa{
                font-size: 30px;
                color: #ccc;
                vertical-align: middle;
                padding-right: 10px;
            }

            .row-split{
                width: 100%;
                height: 50px;
                float: left;
            }

            .content.white{
                background-color: #ffffff;
            }
            .content.green{
                background-color: #7dfd81;
            }

            .arrow-down{
                width: 0;
                height: 0;
                border-left: 16px solid transparent;
                border-right: 16px solid transparent;
                position: absolute;
                left: 0;
                right: 0;
                margin: auto;
            }

            .arrow-down.white{
                border-top: 16px solid rgb(255, 255, 255);
            }

            .content.green h2{
                color: #35802d;
            }

            main{
                margin-top: 200px;
                float: left;
            }
        </style>
    </head>
    <body>
        <nav>
            <a href="/">Home</a>
            <a href="http://github.com/TheColorRed/Zing">Download</a>
            <a href="/docs">Documentation</a>
            <a href="/community">Community</a>
        </nav>