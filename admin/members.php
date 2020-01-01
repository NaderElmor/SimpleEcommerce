<?php
ob_start();
    // Manage members page (add - Edit - update - delete)
    session_start();
    if(isset($_SESSION['username'])) {
        $pageTitle = 'Members';
        require 'init.php';

        $actionName = isset($_GET['actionName']) ? $_GET['actionName'] : 'Main';

        switch ($actionName) {
            /*****************
             *   Add page    *
             *****************/
            case  'add' :
                ?>
                <div class="container ">
                    <h1 class="text-center">Add Member</h1>
                    <!-- when we upload files we use this encryption-->
                    <form class="form-horizontal req" action="members.php?actionName=insert" method="POST"
                          enctype="multipart/form-data">
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Username</label>
                            <div class="col-xs-6 ">
                                <input type="text" class="form-control" name="userName" autocomplete="off"
                                       required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Password</label>
                            <div class="col-xs-6 ">
                                <input type="password" name="password" class="password form-control" required="required"
                                       placeholder="">
                                <i class="showPass fas fa-eye "></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Email</label>
                            <div class="col-xs-6 ">
                                <input type="email" class="form-control" name="email" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Full Name</label>
                            <div class="col-xs-6 ">
                                <input type="text" class="form-control" name="fullName" autocomplete="off"
                                       required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=" col-xs-3 control-label"></label>
                            <div class="col-xs-6 ">
                                    <div class="custom-file btn btn-danger">
                                        Choose your photo <i class="fas fa fa-upload"></i>
                                        <input type="file"  name="image" autocomplete="off">
                                    </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 text-center">
                                <input class="btn btn-primary" type="submit" name="edit" value="Add Member"/>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                break;


            /*****************
             *   Insert page *
             *****************/
            case 'insert' :

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    echo " <h1 class=\"text-center\">Insert Profile</h1>";
                    echo "<div class='container'>";

                    // Upload variables
                    $Imagearray = $_FILES['image'];


                    $ImageName = $_FILES['image']['name'];
                    $ImageSize = $_FILES['image']['size'];
                    $ImageTmp  = $_FILES['image']['tmp_name'];
                    $ImageType = $_FILES['image']['type'];

                    // extensions allowed to be uploaded
                    $allowedExtensions = array("jpeg", "jpg", "png", "gif");

                    //get image extension
                    $imgExtension = strtolower(end(explode('.', $ImageName)));


                    //get variables from add form
                    $userName = $_POST['userName'];
                    $password = $_POST['password'];
                    $email = $_POST['email'];
                    $fullName = $_POST['fullName'];

                    $hashedPass = sha1($password);
                    // Validate the form

                    $errors = array();

                    if (empty($userName)) {
                        $errors[] = "UserName field can't be empty";
                    }

                    if (strlen($userName) > 10) {
                        $errors[] = "UserName field can't be more than 10 letters";
                    }

                    if (strlen($userName) < 4) {
                        $errors[] = "Username field can't be less than 4 letters";
                    }

                    if (empty($password)) {
                        $errors[] = "Password field can't be empty";
                    }


                    if (empty($email)) {
                        $errors[] = "Email field can't be empty";
                    }

                    if (empty($fullName)) {
                        $errors[] = "Full Name field can't be empty";
                    }

                    if(!empty($imgExtension))
                    {
                        // if the user upload a strange extension
                        if (!in_array($imgExtension, $allowedExtensions)) {
                            $errors[] = "This extension Not allowed";
                        }
                    }


                    if ($ImageSize > 4194304) //bytes
                    {
                        $errors[] = "The photo is too large, it is must be less than 4MB";
                    }

                    $check = isExistInDB("users","userName",$userName,"email",$email);
                    if($check > 0)
                    {
                        $errors[] = "There is a user has the same name or email. try another one";
                    }


                    //if errors are founded show it
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>" . $error . "</div>";
                    }


                //put some numbers to the photo name to ensure that is impossible to be duplicated
                $userImage = rand(0, 10000000)  . '_' . $ImageName;

                //start uploading
                move_uploaded_file($ImageTmp, "uploads\usersphotos\\" . $userImage);


                // Check if there is elements in array  errors or not
                if (empty($errors))
                    {
                        // Start insert in the database
                        $stmt = $con->prepare('insert into users(userName ,password ,email,fullName,regStatus,date,userImage)
                                                                                   values    (:userName,:password,:email,:fullName,1,now(),:userImage)');
                        //Notice that u can put any name :anything

                        $stmt->execute(array(

                            ':userName' => $userName,
                            ':password' => $hashedPass,
                            ':email'    => $email,
                            ':fullName' => $fullName,
                            ':userImage' => $userImage
                        ));

                        $msg = "<h3 class='text-center alert alert-success'>" . $stmt->rowCount() . " row has inserted</h3>";
                        redirectTo($msg, 'back');

                     }
                }else

                     {
                          $msg = '<div class="alert alert-danger">You can\'t access directly to this page</div>';
                          redirectTo($msg);
                     }
                     break;
            /*****************
             *  Edit page  *
             *****************/
            case  'edit' :

                $userID = isset($_GET['userID'] )&& is_numeric($_GET['userID'])? intval($_GET['userID']):0;

                // check if the user exists in the database
                $stmt = $con->prepare ("select * from users where userID = ? ");

                $stmt->execute(array($userID));
                $row = $stmt->fetch(); //get the first row in the database (array)
                $count = $stmt->rowCount();

                if($count > 0 ) {?>

                    <div class="container ">
                        <h1 class="text-center">Edit Profile</h1>
                        <form class="form-horizontal req" action="members.php?actionName=update" method="POST">
                            <input type="hidden" name="userID" value="<?php echo $row['userID']?>" />
                            <div class="form-group">
                                <label class=" col-xs-3 control-label">Username</label>
                                <div class="col-xs-6 ">
                                    <input type="text" class="form-control" name="userName" value="<?php echo $row['userName']?>" autocomplete="off" required="required">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class=" col-xs-3 control-label">Password</label>
                                <div class="col-xs-6 ">
                                    <input type="hidden" name="oldPassword" value="<?php echo $row['password']?>">
                                    <input type="password" name="newPassword" value="" class="form-control password" placeholder="Leave it empty if You don't wanna change your password">
                                    <i class="fas fa-eye showPass pull-right"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class=" col-xs-3 control-label">Email</label>
                                <div class="col-xs-6 ">
                                    <input type="email" class="form-control" name="email" value="<?php echo $row['email']?>" autocomplete="off" required="required">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class=" col-xs-3 control-label">Full Name</label>
                                <div class="col-xs-6 ">
                                    <input type="text" class="form-control" name="fullName" value="<?php echo $row['fullName']?>" autocomplete="off" required="required">
                                </div>
                            </div>

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

                echo " <h1 class=\"text-center\">Update Profile</h1>";

                echo "<div class='container'>";
                if ($_SERVER['REQUEST_METHOD'] == 'POST')
                {

                    $userID    = $_POST['userID'];
                    $userName  = $_POST['userName'];
                    $email     = $_POST['email'];
                    $fullName  = $_POST['fullName'];

                    // password trick
                    $password =empty($_POST['newPassword']) ? $password = $_POST['oldPassword'] : sha1($_POST['newPassword']);


                    // Validate the form

                    $errors = array();

                    if (empty($userName))
                    {
                        $errors[] = "UserName field can't be empty";
                    }

                    if (strlen($userName) > 10)
                    {
                        $errors[] = "UserName field can't be more than 10 letters";
                    }

                    if (strlen($userName) < 4)
                    {
                        $errors[] = "Username field can't be less than 4 letters";
                    }

                    if (empty($email))
                    {
                        $errors[] = "Email field can't be empty";
                    }

                    if (empty($fullName))
                    {
                        $errors[] = "Full Name field can't be empty";
                    }

                    //if errors are founded show it
                    foreach ($errors as $error)
                    {
                        echo "<div class='alert alert-danger'>.$error.</div>";
                    }


                    // Check if there is elements in array  errors or not
                    if(empty($errors))
                    {
                        $stat = $con->prepare("select * from users where userName = ? and userID != ? ");

                        $stat->execute(array($userName,$userID));

                        $count = $stat->rowCount();
                        if($count == 1)
                        {
                            $msg =  "<div class='alert alert-danger'>Sorry, This user is exist try another name</div>";
                            redirectTo($msg,'back');

                        }
                        else{
                            // Start update the data from edit form
                            $stmt = $con->prepare ("UPDATE users SET userName = ?, email = ?,fullName = ?, password = ? where userID=?");
                            $stmt->execute(array($userName,$email,$fullName,$password,$userID));  //the elements must be in the same order of the sql statment
                            $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." row has updated</h3>";
                            redirectTo($msg,'back');
                        }


                    }




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

                $userID = isset($_GET['userID'] )&& is_numeric($_GET['userID'])? intval($_GET['userID']):0;

                // check if the user exists in the database
                $check = checkInDB('userID','users',$userID);

                if($check > 0 ) {

                    $stmt = $con->prepare ("DELETE  from users where userID = :userID ");
                    $stmt->bindParam('userID',$userID); //to make the id from get related to the id im the database
                    $stmt->execute();
                    $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." row has deleted</h3>";
                    redirectTo($msg);

                }
                else // if we don't find this id
                {
                    $msg = "<h3 class='text-center alert alert-danger'>Sorry, this person doesn't exist</h3>";
                    redirectTo($msg,'back');

                }


              break;

            /*****************
             *  Activate page  *
             *****************/
            case  'activate' :
                echo "<div class='container'>";

                $userID = isset($_GET['userID'] )&& is_numeric($_GET['userID'])? intval($_GET['userID']):0;

                // check if the user exists in the database
                $check = checkInDB('userID','users',$userID);

                if($check > 0 ) {

                    $stmt = $con->prepare ("UPDATE users SET regStatus = 1 where userID = :userID ");
                    $stmt->bindParam('userID',$userID); //to make the id from get related to the id im the database
                    $stmt->execute();
                    $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." member has activated </h3>";
                    redirectTo($msg);

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

                     <h1 class="text-center">Manage Members</h1>

                     <table class="table manage_table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">#ID</th>
                              <th scope="col">UserName</th>
                              <th scope="col">Photo</th>
                              <th scope="col">Email</th>
                              <th scope="col">Full Name</th>
                              <th scope="col">Registered Date</th>
                              <th scope="col" class="control">Control</th>
                            </tr>
                          </thead>

                          <tbody>

                          <?php
                            $query = '';
                          if(isset($_GET['page'])  && $_GET['page'] == 'pending')
                          {
                              $query = 'AND regStatus = 0';
                          }
                          // Get the data from Data base
                            $stmt = $con->prepare("SELECT * FROM users where groupID != 1 $query");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();

                            foreach ($rows as $row)
                                {
                                    echo '<tr>';
                                    echo '<td>'.$row['userID'].  '</td>';
                                    echo '<td>'.$row['userName'].'</td>';
                                    if(empty($row['userImage'])) {
                                        echo '<td><img src="uploads/usersphotos/defaultUser.jpg"/></td>';
                                    } else {
                                        echo '<td><img src="uploads/usersphotos/'.$row['userImage'].'"/></td>';
                                    }
                                    echo '<td>'.$row['email'].   '</td>';
                                    echo '<td>'.$row['fullName'].'</td>';
                                    echo '<td>'.$row['date'].'</td>';

                                    echo "
                                            <td>
                                            <a href='members.php?actionName=edit&userID=". $row['userID']."'  class='btn btn-success'><i class='fas fa-edit'></i> Edit</a>
                                            <a href='members.php?actionName=delete&userID=". $row['userID']."'  class='confirm btn btn-danger'><i class='fas fa-trash-alt'></i> Delete</a>
                                            
                                         ";
                                    if($row['regStatus'] == 0)
                                    {
                                        echo "
                                               <a href='members.php?actionName=activate&userID=". $row['userID']."'  class='btn btn-info'><i class='fas fa-check'></i> Activate</a>
                                               </td>
                                             ";
                                    }
                           echo '</tr>';
                                }

                            ?>

                          </tbody>
                     </table>

                <a class="btn btn-primary" href="members.php?actionName=add"><i class="fas fa-plus"></i> Add new member</a>
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
    ob_end_flush();