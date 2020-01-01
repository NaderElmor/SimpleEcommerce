<?php
ob_start();
    $nonavbar='';
    $pageTitle = 'Login';
    session_start();
    // if I go to login.php and there is a session go to the home
    if(isset($_SESSION['userSession']))
    {
     header('Location: index.php');
     exit();
    }
    require 'init.php';


    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login'])) { // if post from login form

            //put the data from the form in these vars
            $username = $_POST['user'];
            $password = $_POST['pass'];

            $hashedPassword = sha1($password);


            // check if the user exists in the database
            $stmt = $con->prepare
            ("select userID,username,password from users 
                                            where username = ? AND password= ?"
            );

            $stmt->execute(array($username, $hashedPassword));
            $getuserData = $stmt->fetch(); // I will add it as a session
            $count = $stmt->rowCount();

            // if records are more than 0 then the user with this data is exist
            if ($count > 0) {
                $_SESSION['userSession'] = $username; //register session name

                /* notice the session id must be different from the session id in admin
                    for security purposes
                */
                $_SESSION['userIDSession'] = $getuserData['userID'];

                header('location:index.php');
                exit();


            } else {
                echo "<div class='alert alert-danger'>Sorry, there is no admin has this data in our system !</div>";

            }
        } else { // if post from sign up form

            //make validation on signup form
            $formErrors = array();

            $userName = $_POST['username'];
            $password1 = $_POST['password1'];
            $password2 = $_POST['password2'];
            $email = $_POST['email'];

            if (isset($_POST['username'])) {
                $filteredUserName = filter_var($userName, FILTER_SANITIZE_STRING);

                if (strlen($filteredUserName) < 4) {
                    $formErrors[] = "Username is too short !";
                }
            }


            if (isset($password1) && isset($password2)) {
                if (empty($_POST['password1'])) {
                    $formErrors[] = "Passord can't be empty";

                }

                $pass1 = sha1($password1);
                $pass2 = sha1($password2);

                if ($pass1 !== $pass2) {
                    $formErrors[] = "The two password don't match";
                }

            }

            if (isset($email)) {
                $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

                if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
                    $formErrors[] = "This email is not valid !";
                }
            }

            if (empty($errors)) {
                // check first if there a user has the same name or no

                $statement = $con->prepare("SELECT userName from users where userName = ?");
                $statement->execute(array($userName));

                $check = $statement->rowCount();

                if ($check == 1) {
                    $formErrors[] = "This user is exist !";
                } else {
                    // Start insert in the database
                    $stmt = $con->prepare('insert into users(userName ,password ,email,regStatus,date) 
                                                                   values    (:userName,:password,:email,0,now())');
                    /* Notice that u can put any name :anything */

                    $stmt->execute(array(

                        ':userName' => $userName,
                        ':password' => $pass1,
                        ':email' => $email
                    ));


                }

            }
        }
    }



?>

<div class="container login-page">
    <h2 class="text-center"> <span class="log-in active">Login</span> | </span class="signup"> <span>Sign UP</span></h2>

    <!-- start Login form  -->
    <form class="login log-in" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

        <div class="input-container">
         <input class="form-control" type="text" name="user" autocomplete="off" placeholder="UserName" required/>
            <i class="fas fa-user fuser"></i>
        </div>



        <div class="input-container">
            <input class="form-control" type="password" name="pass" autocomplete="new-password" placeholder="Password" />
            <i class="fas fa-lock fpass"></i>
        </div>
        <input class="btn btn-primary btn-block" name="login"  type="submit" value="login" />
    </form>
    <!-- end Login form  -->



    <!-- signup form  -->
    <form class="login signup hide" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
        <input pattern=".{4,}" title="Username must be at least 4 chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="UserName"/>
        <input minlength="4" class="form-control" type="password" name="password1" autocomplete="new-password" placeholder="Password"  />
        <input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Retype the Password"  />
        <input class="form-control" type="email" name="email"  placeholder="Email"  />
        <input class="btn btn-success btn-block" name="signup" type="submit" value="Sign Up" />
    </form>
    <!-- signup form  -->

    <div class="the-error text-center">
        <?php
        if(!empty($formErrors))
        {

            foreach ($formErrors as $error)
            {
                echo $error . "</br>";
            }
        }
        ?>

    </div>

</div>
<?php require $temps.'footer.php';
ob_end_flush();
?>