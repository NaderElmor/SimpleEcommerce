<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title><?php getTitle();?></title>
    <link rel="stylesheet" href="<?php echo $css?>bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo $css?>all.min.css"/>
    <link rel="stylesheet" href="<?php echo $css?>frontend.css"/>

</head>
<body>
<div class="upper-bar">
    <div class="container">
        <?php
        if(isset($_SESSION['userSession']))
        {
            $stmt = $con->prepare("select * from users where userID = ?");

            $stmt->execute(array($_SESSION['userIDSession']));
            $getuserData = $stmt->fetch(); // I will add it as a session

            if(empty($getuserData['userImage'])) {
                echo '<img src="admin/uploads/usersphotos/defaultuser.jpg" class="img-circle myimg"/>';
            } else {
                echo '<img src="admin/uploads/usersphotos/'.$getuserData['userImage'].'" class="img-circle myimg"/>';
            }


            ?>
            <div class="btn-group my-info">
                        <span class="btn  dropdown-toggle" data-toggle="dropdown">
                            <?php echo $_SESSION['userSession']?>
                            <span class="caret"></span>
                        </span>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">My profile</a></li>
                    <li><a href="newad.php">New AD</a></li>
                    <li><a href="profile.php#myads">My ADS</a></li>
                    <li><a href="logout.php">Log out</a></li>

                </ul>
            </div>

            <?php


        }
        else
        {


            ?>
            <a href="login.php">
                <span class="pull-right">Login/Signup</span>
            </a>
        <?php } ?>
    </div>
</div>

<nav class="navbar navbar-inverse">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><?php echo lang('home')?></a>
        </div>

        <div class="collapse navbar-collapse" id="app-nav">
            <div class="nav navbar-nav navbar-right">
                <?php

                $categories = getAllFrom('*','categories','where parent = 0','categoryID');
                foreach ($categories as $category)
                {
                    echo '<li><a href="categories.php?pageID='. $category['categoryID'].'">'.$category['categoryName'].'</a></li>';
                }

                ?>
            </div>








        </div>
    </div>
</nav>
</body>
<!-- end of header -->