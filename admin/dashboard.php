<?php

session_start();

if(isset($_SESSION['username']))
{
    $pageTitle = 'Dashboard';
    require 'init.php';

    $latestMembers = getLatest('*','users','userID'); //latest members array

    $latestItems = getLatest('*','items','itemID'); //latest items array

    ?>

    <!-- start Dashboard -->
    <div class="stats_container">
        <div class="container text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="stats st-members text-center">
                        <i class="fas fa-users ico"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"><?php echo countItems('userID','users')?></a></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="stats st-pending">
                        <i class="fas fa-user-plus ico"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?actionName=mange&page=pending">
                                    <?php echo checkInDB('regStatus','users',0)?>
                                </a></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="stats st-items">
                        <i class="fas fa-tags ico"></i>
                        <div class="info">
                            Total Items
                            <span><a href="items.php"><?php echo countItems('itemID','items')?></a></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="stats st-comments ">
                        <i class="fas fa-comments ico"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comments.php"><?php echo countItems('commentID','comments')?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start second section [1- Latest users] -->
    <div class="latest_container">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-xs-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fas fa-users"></i>  Latest  users
                            <spna class="toggleplusminus pull-right"><i class="fas fa-minus-circle"></i></spna>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-members">
                            <?php

                                foreach ($latestMembers as $latestMember)
                                {
                                    echo "<li>". $latestMember['userName'] ."
                                    <a href='members.php?actionName=edit&userID=". $latestMember['userID']."'  class='btn btn-success pull-right latest-btn'><i class='fas fa-edit'></i> Edit</a>
                                </li></br>";
                                }
                             ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End second section [1- Latest users] -->

                <!-- Start second section [2- Latest items] -->
                <div class="col-sm-4 col-xs-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fas fa-tags"></i>
                            <spna class="toggleplusminus pull-right"><i class="fas fa-minus-circle"></i></spna>
                            Latest Items</div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-members">
                                <?php

                                foreach ($latestItems as $latestItem)
                                {
                                    echo "<li>". $latestItem['itemName'] ."
      
                                    <a href='items.php?actionName=edit&itemID=". $latestItem['itemID']."'  class='btn btn-success pull-right latest-btn'><i class='fas fa-edit'></i> Edit</a>";

                                    if($latestItem['approve'] == 0)
                                    {
                                        echo "<a href='items.php?actionName=approve&itemID=". $latestItem['itemID']."'  class='btn btn-info pull-right latest-btn'><i class='fas fa-check'></i> Approve</a>";

                                     }

                                echo "</li></br>";
                                }

                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End second section [2- Latest items] -->

                <div class="col-sm-4 col-xs-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fas fa-comments"></i>  Latest  comments
                            <span class="toggleplusminus pull-right"><i class="fas fa-minus-circle"></i></span>
                        </div>
                        <div class="panel-body">
                            <?php

                            // Get the data from Data base
                            $stmt = $con->prepare("SELECT comments.*,users.userName   from comments 
                                                    inner join users ON comments.userID = users.userID
                                                     ");
                            $stmt->execute();
                            $comments = $stmt->fetchAll();

                            foreach ($comments as $comment)
                            {
                                echo '<div class="commentBox">';
                                echo '<span class="member-n"><a href="members.php?actionName=manage">'.$comment['userName'] .'</a></span>';
                                echo '<p class="member-c">'.$comment['content'] .'</p>';
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <!-- End second section [3- Latest comments] -->
                    </div>
            </div>

    <!-- Start second section [3- Latest comments] -->



    </div>
    <!-- End second section -->
    <!-- end Dashboard -->

    <?php

    require $temps.'footer.php';
}
else
{
   header('location:index.php');
   exit();
}