<?php
ob_start();
    session_start();
    // if I go to index.php and there is a session go to the dashboard of this user
    if(isset($_SESSION['username']))
    {
        header('Location: dashboard.php');
    }
    $nonavbar='';
    $pageTitle = 'Login';
    require 'init.php';


if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        //put the data from the form in these vars
        $username = $_POST['user'];
        $password = $_POST['pass'];

        $hashedPassword =sha1($password);

        // check if the user exists in the database

         $stmt = $con->prepare
                             ("select userID, username, password
                                        from users 
                                        where username = ? AND password= ? AND groupID= 1"
                             );

         $stmt->execute(array($username,$hashedPassword));
         $row = $stmt->fetch(); //get the first row in the database (array)
         $count = $stmt->rowCount();

         // if records are more than 0 then the user with this data is exist
        if ($count > 0 )
        {
            $_SESSION['username'] = $username; //register session name
            $_SESSION['userID']   = $row['userID']; //register session id
            header('location:dashboard.php');
            exit();
        }
        else
        {
            echo "<div class='alert alert-danger'>Sorry, there is no admin has this data in our system !</div>";

        }


    }

?>
    <div class="container">
        <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
            <h4 class="text-center"><i class="fas fa-user-lock"></i>   Admin Login</h4>
            <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" required/>
            <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" required />
            <input class="btn btn-primary btn-block" type="submit" value="login" />
        </form>
    </div>

<?php require $temps.'footer.php';
ob_end_flush();
?>