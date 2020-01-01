<?php
session_start();

$pageTitle = 'Profile';
require 'init.php';

if(isset($_SESSION['userSession'])) {

    $getUser = $con->prepare("SELECT * from users WHERE userName = ?  ");

    $getUser->execute(array($_SESSION['userSession']));

    $info = $getUser->fetch();

    $userID = $info['userID'];



    ?>

    <h2 class="text-center">My profile</h2>

    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My information</div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                        <li>
                            <i class="fas fa fa-unlock-alt"></i>
                            <span>Name</span> :  <?php echo $info['userName']; ?>
                        </li>
                        <li>
                            <i class="fas fa fa-envelope"></i>
                            <span>Email</span> :  <?php echo $info['email']; ?>
                        </li>
                        <li>
                            <i class="fas fa fa-user"></i>
                            <span>Full name</span> :  <?php echo $info['fullName']; ?>
                        </li>
                        <li>
                            <i class="fas fa fa-calendar"></i>
                            <span>date</span> :  <?php echo $info['date']; ?>
                        </li>
                        <li>
                            <i class="fas fa fa-tag"></i>
                            <span>Fav category</span> :  uu
                        </li>
                    </ul>
                    <a class="btn btn-primary">
                        Edit  <i class="fas fa-pen"></i>
                    </a>

                </div>
            </div>

        </div>
    </div>

    <div class="myads block" id="myads">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My ads</div>
                <div class="panel-body">
                        <?php

                        $items = getAllFrom("*","items","where userID = $userID AND approve = 1","itemID");

                        if(!empty($items))
                        {
                            echo "<div class='row'>";
                            foreach ($items as $item)
                            {
                                echo '<div class="col-sm-6 col-md-3">';
                                echo '<div class="thumbnail item-box">';
                                echo '<span class="price-tag price-label">$'.$item['price'].'</span>';

                                if(empty($item['image'])) {
                                    echo '<img src="admin/uploads/itemsphotos/defaultItem.png"/>';
                                } else {
                                    echo '<img src="admin/uploads/itemsphotos/'.$item['image'].'"/>';
                                }
                                     echo '<div class="caption">';
                                     echo '<h3><a href="items.php?itemID='.$item['itemID'].'">'.$item['itemName'].'</a></h3>';
                                     echo '<p>'.$item['description'].'</p>';
                                     echo '<p class="date">'.$item['date'].'</p>';
                                  if($item['approve'] == 0)
                                  {
                                    echo "<div class='not-approved'>Not Approved yet  <i class='fas fa fa-pen'></i></div>";
                                  }

                                echo '</div>';

                                echo '</div>';
                                echo '</div>';
                            }
                            echo "</div>";

                        }
                        else
                        {
                            echo "You haven't added ads yet ! <a href='newad.php'>Create Your first ad</a>" ;
                        }

                        ?>

                </div>
            </div>

        </div>
    </div>

    <div class="mycomments block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">latest comments</div>
                <div class="panel-body">
                    <?php

                    $comments = getAllFrom("content","comments","where userID = $userID ","commentID",'asc');


                    if (!empty($comments))
                    {
                        foreach ($comments as $comment)
                        {
                            echo '<p>'. $comment['content'].'</p>';
                        }

                    }else
                    {
                        echo "There is no comments !";
                    }
                    ?>

                </div>
            </div>

        </div>
    </div>


    <?php
}else
{
    header('Location: login.php');
    exit();
}

require $temps.'footer.php';
?>