<?php

session_start();
if(isset($_SESSION['username']))
{
    $pageTitle = 'Comments';
    require 'init.php';

    $actionName = isset($_GET['actionName'])? $_GET['actionName'] : 'Main';

    switch ($actionName)
    {

        /*****************
         *  Edit page  *
         *****************/
        case  'edit' :

            $commentID = isset($_GET['commentID'] )&& is_numeric($_GET['commentID'])? intval($_GET['commentID']):0;

            // check if the user exists in the database
            $stmt = $con->prepare ("select * from comments where commentID = ? ");

            $stmt->execute(array($commentID));
            $comment = $stmt->fetch(); //get the  row in the database (array)

            $count = $stmt->rowCount();

            if($count > 0 ) {?>

                <div class="container ">
                    <h1 class="text-center">Edit Comment</h1>
                    <form class="form-horizontal" action="comments.php?actionName=update" method="POST">
                        <input type="hidden" name="commentID" value="<?php echo $comment['commentID']?>" />

                        <!-- Start comment content-->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Comment</label>
                            <div class="col-xs-6 ">
                                <textarea class="form-control" name="comment">
                                    <?php echo $comment['content']?>
                                </textarea>
                            </div>
                        </div>
                        <!-- Start comment content-->

                        <div class="form-group">
                            <div class="col-xs-12 text-center">
                                <input class="btn btn-primary" type="submit" name="edit" value="Edit"/>
                            </div>
                        </div>
                    </form>
                </div>
            <?php }
            else
            {
                $msg = '<div class="alert alert-danger">Sorry, this ID '. $_GET['userID']. ' is not exist</div>';
                redirectTo($msg);

            }

            break;


        /*****************
         *  Update page  *
         *****************/
        case 'update':


            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {

                $commentID    = $_POST['commentID'];
                $content      = $_POST['comment'];

                    // Start update the data from edit form
                    $stmt = $con->prepare ("UPDATE comments SET content = ? where commentID=?");
                    $stmt->execute(array($content,$commentID));  //the elements must be in the same order of the sql statment
                    $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." comment has updated</h3>";
                    redirectTo($msg,'back');



            }
            else
            {
                $msg = "<h3 class='text-center alert alert-danger'>You can't access directly to this page</h3>";
                redirectTo($msg);


            }

            break;


        /*****************
         *  Delete page  *
         *****************/
        case  'delete' :
            echo "<div class='container'>";

            $commentID = isset($_GET['commentID'] )&& is_numeric($_GET['commentID'])? intval($_GET['commentID']):0;

            // check if the user exists in the database
            $check = checkInDB('commentID','comments',$commentID);

            if($check > 0 ) {

                $stmt = $con->prepare ("DELETE  from comments where commentID = :commentID ");
                $stmt->bindParam('commentID',$commentID); //to make the id from get related to the id im the database
                $stmt->execute();
                $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." comment has deleted</h3>";
                redirectTo($msg);

            }
            else // if we don't find this id
            {
                $msg = "<h3 class='text-center alert alert-danger'>Sorry, this person doesn't exist</h3>";
                redirectTo($msg);

            }


            break;

        /*****************
         *  Approve page  *
         *****************/
        case  'approve' :
            echo "<div class='container'>";

            $commentID = isset($_GET['commentID'] )&& is_numeric($_GET['commentID'])? intval($_GET['commentID']):0;

            // check if the user exists in the database
            $check = checkInDB('commentID','comments',$commentID);

            if($check > 0 )
            {

                $stmt = $con->prepare ("UPDATE comments SET status = 1 where commentID = :commentID ");
                $stmt->bindParam('commentID',$commentID); //to make the id from get related to the id im the database
                $stmt->execute();
                $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." item has approved </h3>";
                redirectTo($msg,'back');

            }
            else // if we don't find this id
            {
                $msg = "<h3 class='text-center alert alert-danger'>Sorry, this person doesn't exist</h3>";
                redirectTo($msg);

            }

            break;


        /*****************
         *  Manage page  *
         *****************/
        default: ?>

            <div class="container">

                <h1 class="text-center">Manage Comments</h1>

                <table class="table manage_table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Commenter</th>
                        <th scope="col">Item</th>
                        <th scope="col">Registered Date</th>
                        <th scope="col" class="control">Control</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php

                    // Get the data from Data base
                    $stmt = $con->prepare("SELECT comments.*,users.userName ,items.itemName  from comments 
                                                    inner join users ON comments.userID = users.userID
                                                    inner join items ON comments.itemID = items.itemID
                                                     ");
                    $stmt->execute();
                    $comments = $stmt->fetchAll();

                    foreach ($comments as $comment)
                    {
                        echo '<tr>';
                        echo '<td>'.$comment['commentID'].  '</td>';
                        echo '<td class="comment-td">'.$comment['content'].'</td>';
                        echo '<td>'.$comment['userName'].   '</td>';
                        echo '<td>'.$comment['itemName'].'</td>';
                        echo '<td>'.$comment['date'].'</td>';

                        echo "
                                            <td>
                                            <a href='comments.php?actionName=edit&commentID=". $comment['commentID']."'  class='btn btn-success'><i class='fas fa-edit'></i> Edit</a>
                                            <a href='comments.php?actionName=delete&commentID=". $comment['commentID']."'  class='confirm btn btn-danger'><i class='fas fa-trash-alt'></i> Delete</a>
                                            
                                         ";
                        if($comment['status'] == 0)
                        {
                            echo "
                                               <a href='comments.php?actionName=approve&commentID=". $comment['commentID']."'  class='btn btn-info'><i class='fas fa-check'></i> Approve</a>
                                               </td>
                                             ";
                        }
                        echo '</tr>';
                    }

                    ?>

                    </tbody>
                </table>

            </div>

            <?php
            break;

    }
    echo "</div>";
    require $temps.'footer.php';
}
else
{
    header('location:index.php');
    exit();
}