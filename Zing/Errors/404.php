<!DOCTYPE html>
<html>
    <head>
        <title>Page Not Found</title>
        <style>
            @import url(http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700);
            body, html{
                padding: 0;
                margin: 0;
                background-image: url(data:image/png;base64,<?php echo base64_encode(file_get_contents(__DIR__."/images/fail.png"))?>);
                background-repeat: no-repeat;
                background-size: auto 100%;
                height: 100%;
                background-position: center center;
                background-color: #95d3be;
            }
            h1, h2{
                padding: 0;
                margin: 0;
                color: #559f84;
                font-family: 'Yanone Kaffeesatz', sans-serif;
                font-size: 60px;
                text-align: center;
                text-shadow: 1px 1px 1px rgb(171,255,224);
            }
            h2{
                font-size: 30px;
                margin-top: 40px;
            }
            div#error{
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                margin: auto;
                width: 50%;
            }
        </style>
    </head>
    <body>
        <div id="error">
            <h1>Page Not Found</h1>
            <h2>
                The page you are looking for was not found on this server.
            </h2>
        </div>
    </body>
</html>