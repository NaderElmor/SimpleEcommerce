<?php

    $dsn ='mysql:host=localhost;dbname=shop'; //id10107440_shop
    $user = 'root';  //id10107440_root
    $pass = ''; //rootroot

    $options = array
    (
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'

    );

    try
    {
        $con = new PDO($dsn,$user,$pass,$options);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }

    catch (PDOException $e)
    {
        echo $e->getMessage();
    }

