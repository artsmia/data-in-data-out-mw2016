<?php
session_start();
?>

<!doctype html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Collections Prototype">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Collections Prototype</title>

    <!-- Web Application Manifest -->
    <link rel="manifest" href="manifest.json">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Collections Prototype">
    <link rel="icon" sizes="192x192" href="images/touch/chrome-touch-icon-192x192.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Collections Prototype">
    <link rel="apple-touch-icon" href="images/touch/apple-touch-icon.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <meta name="theme-color" content="#3372DF">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <!-- Page styles -->
    <link rel="stylesheet" href="styles/main.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto:300,700,400|Roboto+Condensed:400,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  </head>
  <body>
    <a id="top"></a>

    <main>
      <div class="g--half g--centered search_input">
        <h1>search for art</h1>
        <input type="text" name="search" id="search" value="" placeholder="search for something">
      </div>
      <div class="notify">
          <i class="fa fa-heart"></i>
        <h4>Your object has been saved!</h4>
      </div>
      <div class="header">
      </div>
      <a id="scrollStop"></a>

        <div class="content_block">
          <div class="json_result g--medium-1 g-wide--2">
            <h2 class="json_header">JSON Results</h2>

          </div>
          <div class="styled_result g--medium-1 g-wide--2 g--last">
            <h2 class="styled_header">Styled Results</h2>

          </div>
          <div class="wrapper">


          </div>
        </div>

    </main>
    <a href="#toTop" class="scrollToTop"><i class="icon icon-chevron-up"></i></a>

    <!-- build:js scripts/main.min.js -->
    <script src="scripts/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="scripts/search.js"></script>
    <script type="text/javascript">

    $(document).ready(function(){
      $(".notify").hide();
    $(".header").addClass("landing");
    $(".scrollToTop").click(function(){
      $("html,body").animate( { scrollTop:$("#top").offset().top } , 1000, 'linear' );
    });
  });
  $("#search").on('input', function () {
  if ($("#search").val().length > 3) {
    $(".header").removeClass("landing");
    $(".search_results").fadeIn(1500);
}
else {
  $(".header").addClass("landing");
  $(".special_features").fadeIn(1200)
};
});
  $(window).scroll(function(){
    if ($(document).scrollTop() > 200) {
      $('a.scrollToTop').fadeIn(200);
    } else {
      $('a.scrollToTop').fadeOut(200);
    }
  });
    </script>
    <!-- endbuild -->

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-XXXXX-X', 'auto');
      ga('send', 'pageview');
    </script>
    <!-- Built with love using Web Starter Kit -->
  </body>
</html>
