<?php
session_start();

$pageTitle = 'Items';
require 'init.php';
    /*
     * if you write items.php?itemID=5, number 5 will be stored in $itemId variable
     */
    $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;

    // check if the user exists in the database with a specific id
    $stmt = $con->prepare("select 
                                            items.*,categories.categoryName,users.userName 
                                    from
                                            items 
                                    inner join 
                                            categories
                                    ON
                                            categories.categoryID = items.categoryID 
                                    
                                    inner join 
                                            users
                                    ON
                                            users.userID = items.userID
                                    where
                                            itemID = ? 
                                    AND approve = 1");

    $stmt->execute(array($itemID));
    $count = $stmt->rowCount();

    if($count > 0)
    {
        $item = $stmt->fetch(); //get the first row in the database (array)


        ?>


        <div class="container">
            <div class="row">
                <div class="col-md-3 "> <?php
                    if(empty($item['image'])) {
                    echo '<img class=" img-responsive img-thumbnail center-block item-img" src="admin/uploads/itemsphotos/defaultItem.png"/>';
                    } else {
                    echo '<img class=" img-responsive img-thumbnail center-block item-img" src="admin/uploads/itemsphotos/'.$item['image'].'"/>';
                    } ?>
                </div>

                <div class="col-md-9 item-info">
                    <h2><?php   echo $item['itemName'] ?> </h2>
                    <p><?php    echo $item['description'] ?></p>
                    <ul class="list-unstyled">

                       <li>
                           <i class="fas fa fa-calendar"></i>
                           <span> Date  </span>   : <?php echo $item['date'] ?>
                       </li>

                       <li>
                           <i class="fas fa fa-money-bill"></i>
                           <span> Price  </span>: $<?php    echo $item['price'] ?>
                       </li>

                       <li>
                           <i class="fas fa fa-map-marked"></i>
                           <span> Country  </span> : <?php    echo $item['country'] ?>
                       </li>

                        <li>
                            <i class="fas fa fa-tags"></i>
                            <span> Category  </span>:<a href="#" target="_blank">   <?php    echo $item['categoryName'] ?></a>
                        </li>

                        <li>
                            <i class="fas fa fa-user"></i>
                            <span> Added by  </span>  :<a href="#" target="_blank">  <?php    echo $item['userName'] ?></a>
                        </li>



                        <!-- Start tags field -->
                        <li>
                            <i class="fas fa fa-user-tag"></i>
                            <span> Tags  </span>  :
                                <?php

                                      $tags = explode(",",$item['tags']);

                                foreach ($tags as $tag)
                                {
                                    $tag = str_replace(' ','',$tag);
                                    $tag = strtolower($tag);
                                    echo "<a class='label label-default' href='tags.php?tagName={$tag}'>#".$tag ."</a>    ";
                                }

                                ?>
                        </li>
                        <!-- End tags field -->
                    </ul>
                </div>
            </div>

            <hr class="custom-hr"/>

            <!-- Start comments section -->
            <?php if(isset($_SESSION['userSession'])) { ?>
            <div class="row">
                 <div class="col-md-offset-3">
                     <div class="items-comment"
                         <h3>Add comment</h3>
                         <form action="<?php echo $_SERVER['PHP_SELF'].'?itemID='.$item['itemID']?>" method="POST">
                             <textarea name="comment" required="required"></textarea>
                             <input class="btn btn-primary" type="submit" value="Comment"/>
                         </form>
                     <?php

                        if ($_SERVER['REQUEST_METHOD'] == 'POST')
                        {
                            $comment = filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
                            $userID  = $_SESSION['userIDSession'];
                            $itemID  = $item['itemID'];

                            if(!empty($comment))
                            {
                                $stmt = $con->prepare("insert into comments(content,status,userID,itemID,date) 
                                                                              values(:zcontent,0,:zuserID,:zitemID,NOw()) ");
                                  $stmt->execute(
                                     array(
                                             'zcontent' => $comment,
                                             'zuserID' => $userID,
                                             'zitemID' => $itemID

                                     ));



                            }

                        }

                     ?>

                     </div>
                 </div>
             </div>
    <?php }else
            {
                echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> to comment ';
            } ?>

        <hr class="custom-hr"/>
        <?php

        // Get the data from Data base
        $stmt = $con->prepare("SELECT 
                                                               comments.*,users.userName 
                                                    from 
                                                                comments 
                                                  
                                                    inner join 
                                                                users 
                                                                
                                                            ON  comments.userID = users.userID 
                                                            
                                                          where 
                                                                itemId = ?
                                                     ");
        $stmt->execute(array($itemID));
        $comments = $stmt->fetchAll();



        ?>

        <?php
        foreach ($comments as $comment)
        {
            echo '<div class="comment-box">';
            echo '<div class="row">';
            echo '<div class="col-md-2 text-center"><img class="img-responsive img-thumbnail img-circle" src="img.jpg" />
'

                .$comment['userName'].
                '</div>';
            echo '<div class="col-md-10"><p class="lead">'.$comment['content'].'</p></div>';
            echo '</div>';
            echo '</div>';

        }
        ?>


            <!-- End comments section -->

        </div>

        <?php
    }else
    {
        echo '<div class="container"><div class="text-center alert alert-danger">There is no <b>'.$itemID. '</b> ID in our system or the item is not approved yet</div></div>';
    }



require $temps.'footer.php';
?>