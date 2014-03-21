<!doctype html>
<html>
    <head>
        <title>ZINGPHP | PHP Framework</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
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
            h2{
                font-size: 40px;
            }
            h3{
                font-size: 30px;
            }
            nav{
                background-color: #2e3234;
                float: right;
                margin: 0;
                padding: 0 50px;
            }
            nav a{
                padding: 10px 20px;
                float: left;
                color: #ffffff;
                color: #747474;
                font-weight: bold;
                text-transform: uppercase;
                text-decoration: none;
            }
            nav a:hover{
                background-color: #3c7b9f !important;
                color: #000;
                color: #fff;
                text-decoration: none;
            }
            nav li{
                float: left;
            }
            .content{
                background-color: #ffffff;
                padding: 100px 50px;
                clear: both;
                /*height: 100%;*/
            }
            main > div.content > div > div{
                text-align: center;
            }
            h3 > i.fa{
                font-size: 30px;
                color: #ccc;
                vertical-align: middle;
                padding-right: 10px;
            }

            .row-split{
                width: 100%;
                height: 100px;
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
                border-left: 50px solid transparent;
                border-right: 50px solid transparent;
                position: absolute;
                left: 0;
                right: 0;
                margin: auto;
            }

            .arrow-down.white{
                border-top: 50px solid rgb(255, 255, 255);
            }

            .content.green h2{
                color: #35802d;
            }

            .navbar{
                border-radius: 0;
            }

            /*.container{
                padding-left: 150px;
                padding-right: 150px;
            }*/

            .header{
                min-height: 500px;
            }
            @media(max-width: 480px) {
                h1 {
                    font-size: 70px;
                }
            }
            @media (min-width: 926px) { /* Insert your own breakpoint as needed */
                .mobile-menu { display: none; }
            }
            @media (max-width: 925px) { /* Insert your own breakpoint as needed */
                .mobile-menu { display: block; width: 100%;float: left; }
                .mobile-menu a { display: block; width: 100%;float: left; }
                nav#mainNav .nav { display: none; }
                nav#mainNav.navbar .nav>li { width: 100%; }
                nav#mainNav.navbar .nav>li>a { width: 100%; }
                nav#mainNav{ width: 100%; }
                nav li{ float: none; }
                .container{ padding-left: 15px;padding-right: 15px; }
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".mobile-menu a").click(function(e){
                    e.preventDefault();
                    $("nav#mainNav .nav").slideToggle();
                    var status = $(this).attr('data-status');
                    if(status === 'plus'){
                        $(this).attr('data-status', 'minus');
                        $(this).html('<i class="fa fa-minus"></i> Menu');
                    }else if(status === 'minus'){
                        $(this).attr('data-status', 'plus');
                        $(this).html('<i class="fa fa-plus"></i> Menu');
                    }
                });
            });
            $(window).resize(function(){
                var vwptWidth = $(window).width();
                if(vwptWidth > 925){
                    $("ul.nav").removeAttr("style");
                    $(".mobile-menu a").attr('data-status', 'plus');
                }
            });
        </script>
    </head>
    <body>
        <div class="header">
            <nav id="mainNav" class="navbar" role="navigation">
                <div class="navbar-inner">
                    <div class="mobile-menu">
                        <a data-status="plus" href=""><i class="fa fa-plus"></i>  Menu</a>
                    </div>
                    <ul class="nav">
                        <li><a href="/">Home</a></li>
                        <li><a href="http://github.com/TheColorRed/Zing">Download</a></li>
                        <li><a href="/docs">Documentation</a></li>
                        <li><a href="/community">Community</a></li>
                    </ul>
                </div>
            </nav>
            <a href="/"><h1><b>Zing</b>php</h1></a>
        </div>