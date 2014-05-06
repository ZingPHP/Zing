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
    .nav-left ul > li:first-child > a:hover:not(.selected){
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
    .nav-left ul > li:last-child > a:hover:not(.selected){
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    .nav-left ul > li > a{
        text-decoration: none;
    }
    .nav-left ul > li > ul > li > a{
        padding-left: 40px;
        font-size: 15px;
        border-radius: 0 !important;
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
    .toc > ul > li{
        font-family: Menlo, Monaco, Consolas, 'Courier New', monospace;
        font-size: 1em;
    }
    .toc{
        background-color: #eee;
        border: solid 1px #ccc;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 50px;
    }
    .toc ul{
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .toc p span:first-child{
        display: block;
        font-weight: bold;
        font-family: Menlo, Monaco, Consolas, 'Courier New', monospace;
    }
    .toc p span:last-child{
        display: block;
        padding-left: 50px;

    }
    p.bg-info{
        padding: 10px;
        border-left: solid 2px;
    }
    @media(max-width: 926px){
        .nav-left{ position: static; }
    }
</style>
<main>
    <div class="content white">
        <div class="container" style="height: 100%;">
            <div class="row">
                <div class="col-sm-3 nav-left">
                    <ul class="doc-nav">
                        <li><a href="/ref/core">Core</a></li>
                        <li><a href="/ref/module" style="border-bottom: solid 1px #ccc;">Module</a></li>
                        <li><a href="/ref/cache">Cache</a></li>
                        <li><a href="/ref/dbo">DBO</a></li>
                        <li><a href="/ref/date">Date</a></li>
                        <li><a href="/ref/file">File</a></li>
                        <li><a href="/ref/form">Form</a></li>
                        <li><a href="/ref/http">Http</a></li>
                        <li><a href="/ref/image">Image</a></li>
                        <li><a href="/ref/input">Input</a></li>
                        <li><a href="/ref/mail">Mail</a></li>
                        <li><a href="/ref/math">Math</a></li>
                        <li><a href="/ref/user">User</a></li>
                        <li><a href="/ref/util">Util</a></li>
                        <li><a href="/ref/validate">Validate</a></li>
                    </ul>
                </div>
                <div class="col-sm-8 doc-right">
                    {$ref}
                </div>
            </div>
        </div>
    </div>
    <div class="arrow-down white"></div>
</main>
<link rel="stylesheet" href="http://yandex.st/highlightjs/8.0/styles/foundation.min.css" />
<script src="http://yandex.st/highlightjs/8.0/highlight.min.js"></script>
<script src="/media/js/stickySidebar.js"></script>
<script>
    $(document).ready(function(){
        $('pre').each(function(i, e){
            hljs.highlightBlock(e);
        });
    });
    /*$(document).on("click", ".doc-nav a", function(e){
     //e.preventDefault();
     if(!$(this).closest("ul").hasClass("sub-doc-item")){
     $(".sub-doc-item").slideUp("slow");
     }else{
     $("html, body").scrollTop($($(this).attr('href')).offset().top + 100);
     $('html, body').animate({
     scrollTop: $($(this).attr('href')).offset().top
     }, "fast");
     }

     $("ul.doc-nav a.selected").removeClass("selected");
     $(this).addClass("selected").next('ul').slideToggle("slow");
     return false;
     });*/


    var navOffset = 0;
    var set = false;
    $(document).ready(function(){
        navOffset = $(".nav-left").offset().top;
        /*$(window).on("scroll", function(){
         var scroll = $(document).scrollTop();
         var offset = $(".nav-left").offset().top;
         ///console.log(offset - scroll);
         if((offset - scroll - 10) <= 0){
         set = true;
         $(".nav-left").css({
         position: "fixed",
         top: "10px"
         });
         }
         if(scroll <= 200){
         set = false;
         $(".nav-left").css({
         position: "absolue",
         top: "auto"
         });
         }
         });*/
    });

</script>
<!--<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>-->