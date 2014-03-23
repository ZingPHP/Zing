<style>
    div.nav-left{
        border: solid 1px #7dcefd;
        padding: 0;
        border-radius: 5px;
    }
    div.nav-left ul{
        list-style-type: none;
        margin: 0;
        padding: 0;
        text-align: left;
    }
    div.nav-left ul li{
        margin: 0;
        padding: 0;
    }

    div.doc-right{
        text-align: left !important;
    }

    div.doc-right h2{
        margin-bottom: 15px;
    }
    .nav-left ul > li > a{
        display: block;
        padding: 8px;
    }
    .nav-left ul > li > a.selected{
        color: #ffffff;
        background-color: #7dcefd;
    }
    .nav-left ul > li > a.selected:hover{
        background-color: #62c5ff;
    }
    .nav-left ul > li > a:hover:not(.selected){
        background-color: #eee;
    }
    .nav-left ul > li > a{
        text-decoration: none;
    }
    .nav-left ul > li > ul > li > a{
        padding-left: 40px;
        font-size: 15px;
    }
    pre{
        padding: 10px !important;
        border: solid 1px #ccc !important;
        font-size: 15px !important;
    }
    code{
        border: solid 1px #fdd8e4;
        border-radius: 3px !important;
    }
    .doc-right p, .doc-right ol li, .doc-right ul li{
        line-height: 25px;
        font-size: 15px;
    }
</style>
<main>
    <div class="content white">
        <div class="container" style="height: 100%;">
            <div class="col-sm-3 nav-left">
                <ul class="doc-nav">
                    <li class="main-doc-item"><a class="selected" href="#introduction">Introduction</a>
                        <ul class="sub-doc-item expanded">
                            <li><a href="#getting-started">Getting Started</a></li>
                            <li><a href="#hello-world">Hello World!</a></li>
                        </ul>
                    </li>
                    <li class="main-doc-item"><a href="#introduction">Modules</a>
                        <ul class="sub-doc-item" style="display: none;">
                            <li><a href="#getting-started">What's a module?</a></li>
                            <li><a href="#hello-world">Custom Modules</a></li>
                        </ul>
                    </li>
                    <li class="main-doc-item"><a href="#introduction">Databases</a>
                        <ul class="sub-doc-item" style="display: none;">
                            <li><a href="#getting-started">Getting Started</a></li>
                            <li><a href="#hello-world">Hello World!</a></li>
                            <li><a href="#hello-world">Hello World!</a></li>
                        </ul>
                    </li>
                    <li class="main-doc-item"><a href="#introduction">Caching</a>
                        <ul class="sub-doc-item" style="display: none;">
                            <li><a href="#getting-started">Getting Started</a></li>
                            <li><a href="#hello-world">Hello World!</a></li>
                            <li><a href="#hello-world">Hello World!</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-sm-9 doc-right">
                {$doc}
            </div>
        </div>
    </div>
    <div class="arrow-down white"></div>
</main>
<script>
    $(document).on("click", ".doc-nav a", function(e){
        e.preventDefault();

        if(!$(this).closest("ul").hasClass("sub-doc-item")){
            $(".sub-doc-item").slideUp("slow");
        }

        $("ul.doc-nav a.selected").removeClass("selected");
        $(this).addClass("selected").next('ul').slideToggle("slow");
    });
</script>
<link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/foundation.min.css">
<script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>
<script>$(document).ready(function(){
        $('pre').each(function(i, e){
            hljs.highlightBlock(e)
        });
    });</script>
<!--<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>-->