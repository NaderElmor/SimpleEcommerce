<?php

    //to activate error messages
    ini_set('display_errors','on');
    error_reporting(E_ALL);


    require 'admin/connectDB.php';

    // Routes

   $temps = 'includes/templates/';             //templates directory

   $css = 'layout/css/';                       //css directory

   $js = 'layout/js/';                         //js directory

   $lang = 'includes/languages/';             //language directory

   $funcs = 'includes/functions/';             //language directory







    // include the important files
    require $funcs.'functions.php';

    require $lang.'english.php'; //Must be the first


    require $temps.'header.php';


































