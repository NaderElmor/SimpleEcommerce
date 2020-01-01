<?php
session_start();

$pageTitle = 'Create new ad';
require 'init.php';



if(isset($_SESSION['userSession'])) {



    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
       $formerrors = array();

        // Upload variables
        $Imagearray = $_FILES['image'];


        $ImageName = $_FILES['image']['name'];
        $ImageSize = $_FILES['image']['size'];
        $ImageTmp  = $_FILES['image']['tmp_name'];
        $ImageType = $_FILES['image']['type'];

        // extensions allowed to be uploaded
        $allowedExtensions = array("jpeg", "jpg", "png", "gif");

        //get image extension
        $seprate = explode('.', $ImageName);
        $imgExtension = strtolower(end($seprate));

        // Recieve all post params and filter them
        $itemName     = filter_var($_POST['itemName'],FILTER_SANITIZE_STRING);
        $description  = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
        $country      = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
        $price        = filter_var($_POST['price'],FILTER_VALIDATE_FLOAT);
        $status       = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
        $category     = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
        $tags         = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);





        if (empty($itemName) || empty($description) || empty($description) || empty($country) || empty($price))
        {
            $formerrors [] = "You have to fill out all fields";
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

        //put some numbers to the photo name to ensure that is impossible to be duplicated
        $userImage = rand(0, 10000000)  . '_' . $ImageName;

        //start uploading
        move_uploaded_file($ImageTmp, "admin\uploads\itemsphotos\\" . $userImage);

        // Check if there is no errors start to insert
        if(empty($errors))
        {
            // Start insert in the database
            $stmt = $con->prepare("insert into
                                     items(itemName ,description ,price,country,status,date,userID,categoryID,tags,image) 
                                                        VALUES(:itemName,:description,
                                                              :price,:country,:status,
                                                              now(),:userID,:categoryID,:tags,:image)");

            $stmt->execute(array(

                ':itemName'    =>  $itemName,
                ':description' =>  $description,
                ':price'       =>  $price,
                ':country'     =>  $country,
                ':status'      =>  $status,
                ':userID'      =>  $_SESSION['userIDSession'],  // I get it from the user when he logs in (login.php)
                ':categoryID'  =>  $category,
                ':tags'        =>  $tags,
                ':image'       => $userImage


            ));

            if($stmt) // to guarantee that that the item is added in the database (No server crash or any error has occurred)
            {
                // Success msg
                $successMsg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." item added successfully</h3>";
            }


        }



    }



    ?>

    <div class="myads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Create New ad</div>
                <div class="panel-body">
                    <div class="row">

                        <!-- Start ads form -->
                        <div class="col-md-8 ad-form">
                            <form class="form-horizontal text-center"  action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">

                                <!-- Start name field -->
                                <div class="form-group">
                                    <label class=" col-xs-3 control-label">Name</label>
                                    <div class="col-xs-8 ">
                                        <input pattern=".{4,}" title="Must be at leat 4 charachters" type="text" class="form-control" name="itemName" required="required"  data-class=".live-name">
                                    </div>
                                </div>
                                <!-- End name field -->


                                <!-- Start desription field -->
                                <div class="form-group">
                                    <label class=" col-xs-3 control-label">Desription</label>
                                    <div class="col-xs-8 ">
                                        <input type="text" pattern=".{4,}" title="Must be at leat 10 charachters" class="form-control" name="description" required="required" data-class=".live-desc" >
                                    </div>
                                </div>
                                <!-- End desription field -->


                                <!-- Start price field -->
                                <div class="form-group">
                                    <label class=" col-xs-3 control-label">Price</label>
                                    <div class="col-xs-8 ">
                                        <input type="text" class="form-control" name="price" required="required" data-class=".live-price" >
                                    </div>
                                </div>
                                <!-- End price field -->

                                <!-- Start country field -->
                                <div class="form-group">
                                    <label class=" col-xs-3 control-label">Country</label>
                                    <div class="col-xs-8 ">
                                        <input type="text" class="form-control" name="country"  required="required">
                                    </div>
                                </div>
                                <!-- End country field -->

                                <!-- Start status field -->
                                <div class="form-group">
                                    <label class=" col-xs-3 control-label">Status</label>
                                    <div class="col-xs-8 ">
                                        <select class="form-control" name="status" required="required">
                                            <option value="">Choose the status</option>
                                            <option value="1">New</option>
                                            <option value="2">Used</option>
                                            <option value="3">Old</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- End status field -->


                                <!-- Start categories field -->
                                <div class="form-group">
                                    <label class=" col-xs-3 control-label">category</label>
                                    <div class="col-xs-8 ">
                                        <select class="form-control" name="category" required="required">
                                            <option value="" >Choose the category</option>
                                            <?php
                                            $categories = getAllFrom('*','categories',null,'categoryID');

                                            foreach($categories as $category)
                                            {
                                                echo "<option value='".$category['categoryID']."'>".$category['categoryName']."</option>";
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>

                                <!-- Start tags field -->
                                <div class="form-group">
                                    <label class=" col-xs-3 control-label">Tags</label>
                                    <div class="col-xs-8 ">
                                        <input  type="text" placeholder="ex: Discounts, Quality, Guarantee" class="form-control" name="tags">
                                    </div>
                                </div>
                                <!-- End tags field -->

                                <div class="custom-file btn btn-danger">
                                    Choose your photo <i class="fas fa fa-upload"></i>
                                    <input type="file"  name="image" autocomplete="off">
                                </div>
                                <input type="submit" class="btn btn-primary " value="Add Item"  />

                            </form>



                        <!-- End ads form-->

                        <!--Start looping form errors-->
                        <?php
                        if(!empty($formerrors))
                        {
                            foreach ($formerrors as $error)
                            {
                                echo "<div class='alert alert-danger'>". $error ."</div>";
                            }
                        }else if
                        (isset($successMsg))
                        {
                            echo $successMsg;
                        }


                        ?>
                        <!--End looping form errors-->
                        </div>

                        <div class="col-md-4">
                                <div class="thumbnail item-box">
                                    <div class="price-label">
                                        $<span class="price-tag live-price ">0</span>
                                    </div>

                                    <img class="img-responsive" src="admin/uploads/itemsphotos/defaultItem.jpg" />
                                  <div class="caption">
                                       <h3 class="live-name">Item Name</h3>
                                       <p class="live-desc">Description</p>
                                        </div>
                                   </div>


                        </div>
                    </div>


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