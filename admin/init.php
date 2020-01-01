<?php

    require 'connectDB.php';

    // Routes

   $temps = 'includes/templates/';             //templates directory

   $css = 'layout/css/';                       //css directory

   $js = 'layout/js/';                         //js directory

   $lang = 'includes/languages/';             //language directory

   $funcs = 'includes/functions/';             //language directory





    // include the important files
    require $funcs.'functions.php';


    require $temps.'header.php';


    // when we put $nonavbar ='' in any page, the nav bar will not be included in it
    if(!isset($nonavbar)){ require $temps.'navbar.php';}

































