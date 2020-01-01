<?php

    ob_start();
  
    session_start();

    $pageTitle = 'Items';


    if(isset($_SESSION['username']))
    {
        require 'init.php';

        $actionName = isset($_GET['actionName']) ? $_GET['actionName'] : 'Main';

        // Manage page
        if ($actionName == 'Main')
        { ?>
           <div class="container">

               <h1 class="text-center">Manage Items</h1>

               <table class="table manage_table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">#ID</th>
                      <th scope="col">Item Name</th>
                      <th scope="col">Photo</th>
                      <th scope="col">Description</th>
                      <th scope="col">Price</th>
                      <th scope="col">User Name</th>
                      <th scope="col">Category Name</th>
                      <th scope="col">Added Date</th>
                      <th scope="col" class="control">Control</th>
                    </tr>
                  </thead>
                  <tbody>

                  <?php

                  // Get the data from Data base
                  $istmt = $con->prepare(
                                  "SELECT
                                                  items.*,users.userName as userName ,categories.categoryName as categoryName
                                            FROM 
                                                  items
                                            INNER JOIN users      on users.userID = items.userID
                                            INNER JOIN categories on categories.categoryID = items.categoryID
                                           ");
                  $istmt->execute();
                  $items = $istmt->fetchAll();

                foreach ($items as $item)
                    {
                        echo '<tr>';
                        echo '<td>'.$item['itemID'].  '</td>';
                        echo '<td>'.$item['itemName'].'</td>';
                        if(empty($item['image'])) {
                            echo '<td><img src="uploads/itemsphotos/defaultItem.png"/></td>';
                        } else {
                            echo '<td><img src="uploads/itemsphotos/'.$item['image'].'"/></td>';
                        }
                        echo '<td>'.$item['description'].   '</td>';
                        echo '<td>'.$item['price'].'</td>';
                        echo '<td>'.$item['userName'].'</td>';
                        echo '<td>'.$item['categoryName'].'</td>';
                        echo '<td>'.$item['date'].'</td>';

                        echo "
                                <td>
                                <a href='items.php?actionName=edit&itemID=". $item['itemID']."'  class='btn btn-success'><i class='fas fa-edit'></i> Edit</a>
                                <a href='items.php?actionName=delete&itemID=". $item['itemID']."'  class='btn btn-danger confirm'><i class='fas fa-trash-alt'></i> Delete</a>
                                
                             ";

                        if($item['approve'] == 0)
                        {
                            echo "<a href='items.php?actionName=approve&itemID=". $item['itemID']."'  class='btn btn-info'><i class='fas fa-check'></i> Approve</a></td>
                                             ";
                        }

               echo '</tr>';
                    }

                ?>

              </tbody>
         </table>

    <a class="btn btn-primary" href="items.php?actionName=add"><i class='fas fa-edit'></i>  Add new Item</a>
    </div>


        <?php } //add page
        elseif ($actionName == 'add') { ?>

                <div class="container">
                <h2 class="text-center">Add Item</h2>

                <form class="form-horizontal req"  action="items.php?actionName=insert" method="POST" enctype="multipart/form-data">

                    <!-- Start name field -->
                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Name</label>
                        <div class="col-xs-6 ">
                            <input type="text" class="form-control" name="itemName"  required="required">
                        </div>
                    </div>
                    <!-- End name field -->


                    <!-- Start desription field -->
                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Desription</label>
                        <div class="col-xs-6 ">
                            <input type="text" class="form-control" name="description" required="required"  >
                        </div>
                    </div>
                    <!-- End desription field -->


                    <!-- Start price field -->
                     <div class="form-group">
                        <label class=" col-xs-3 control-label">Price</label>
                        <div class="col-xs-6 ">
                            <input type="text" class="form-control" name="price" required="required" >
                        </div>
                    </div>
                    <!-- End price field -->

                    <!-- Start country field -->
                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Country</label>
                        <div class="col-xs-6 ">
                            <input type="text" class="form-control" name="country"  required="required">
                        </div>
                    </div>
                    <!-- End country field -->

                    <!-- Start status field -->
                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Status</label>
                        <div class="col-xs-6 ">
                            <select class="form-control" name="status">
                                <option value="0">Choose the status</option>
                                <option value="1">New</option>
                                <option value="2">Used</option>
                                <option value="3">Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End status field -->

                    <!-- Start users field -->
                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Members</label>
                        <div class="col-xs-6 ">
                            <select class="form-control" name="member">
                                <option value="0">Choose the member</option>
                                <?php
                                $users =  getAllFrom("*","users","","userID");


                                foreach($users as $user)
                                {
                                   echo "<option value='".$user['userID']."'>".$user['userName']."</option>";
                                }
                                 ?>
                            </select>
                        </div>
                    </div>
                    <!-- End users field -->

                    <!-- Start categories field -->
                    <div class="form-group">
                        <label class=" col-xs-3 control-label">category</label>
                        <div class="col-xs-6 ">
                            <select class="form-control" name="category">
                                <option value="0">Choose the category</option>
                                <?php
                                $categories =  getAllFrom("*","categories","where parent = 0","categoryID");

                                 foreach($categories as $category)
                                 {
                                     echo "<option  value='".$category['categoryID']."'>".$category['categoryName']."</option>";
                                     $subCats =  getAllFrom("*","categories","where parent = {$category['categoryID']}","categoryID");

                                     foreach ($subCats as $subCat)
                                     {
                                         echo "<option class='' value='".$subCat['categoryID']."'>--->".$subCat['categoryName']."</option>";

                                     }


                                 }
                                ?>

                            </select>
                        </div>
                    </div>
                    <!-- End categories field -->

                    <!-- Start tags field -->
                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Tags</label>
                        <div class="col-xs-6 ">
                            <input type="text" placeholder="ex: Discounts, Quality, Guarantee" class="form-control" name="tags">
                        </div>
                    </div>
                    <!-- End tags field -->

                    <div class="form-group">
                        <label class=" col-xs-3 control-label"></label>
                        <div class="col-xs-6 ">
                            <div class="custom-file btn btn-danger">
                                Choose your photo <i class="fas fa fa-upload"></i>
                                <input type="file"  name="image" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                    <input type="submit" class="btn btn-primary text-center" value="Add an item" >
                    </div>
                </form>
            </div>

            <?php
        }

        //insert page
        elseif($actionName == 'insert')
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                echo " <h1 class=\"text-center\">Insert Item</h1>";
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

                $itemName       = $_POST['itemName'];
                $description    = $_POST['description'];
                $price          = $_POST['price'];
                $country        = $_POST['country'];
                $status         = $_POST['status'];
                $memberID       = $_POST['member'];
                $categoryID     = $_POST['category'];
                $tags           = $_POST['tags'];


                $errors = array();

                if (empty($itemName))
                {
                    $errors[] = "ItemName field can't be empty";
                }
                if (empty($description))
                {
                    $errors[] = "Desription field can't be empty";
                }
                if (empty($price))
                {
                    $errors[] = "Price field can't be empty";
                }

                if (empty($country))
                {
                    $errors[] = "Country field can't be empty";
                }

                if (empty($status))
                {
                    $errors[] = "You have to choose a status";
                }

                if (empty($memberID))
                {
                    $errors[] = "You have to choose a member";
                }

                if (empty($categoryID))
                {
                    $errors[] = "You have to choose a category";
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


                //if errors are founded show it
                foreach ($errors as $error)
                {
                    echo '<div class="alert alert-danger">'.$error. '</div>';
                }


                //put some numbers to the photo name to ensure that is impossible to be duplicated
                $itemImage = rand(0, 10000000)  . '_' . $ImageName;

                //start uploading
                move_uploaded_file($ImageTmp, "uploads\itemsphotos\\" . $itemImage);

                // Check if there is elements in array  errors or not
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
                        ':userID'      =>  $memberID,
                        ':categoryID'  =>  $categoryID,
                        ':tags'  =>  $tags,
                        ':image'  =>  $itemImage

                    ));

                    // Success msg
                     $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." row has inserted</h3>";
                     redirectTo($msg,'back');

                }

            }
             else
            {
                $msg = '<div class="alert alert-danger">You can\'t access directly to this page</div>';
                redirectTo($msg);
            }
        }


        //edit page
        elseif ($actionName == 'edit') {
            $itemID = isset($_GET['itemID']) && is_numeric($_GET['itemID']) ? intval($_GET['itemID']) : 0;


            // check if the user exists in the database
            $stmt = $con->prepare("select * from items where itemID = ? ");

            $stmt->execute(array($itemID));
            $item = $stmt->fetch(); //get the first row in the database (array)
            $count = $stmt->rowCount();

            if ($count > 0) { ?>

                <div class="container text-center">
                    <h1>Edit Item</h1>

                    <form class="form-horizontal" action="items.php?actionName=update" method="POST">

                        <!-- Start id field (hidden)-->
                        <input type="hidden" name="itemID" value="<?php echo $item['itemID'] ?>"/>
                        <!-- end id field (hidden)-->

                        <!-- Start name field -->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Name</label>
                            <div class="col-xs-6 ">
                                <input type="text" class="form-control" name="itemName"
                                       value="<?php echo $item['itemName'] ?>">
                            </div>
                        </div>
                        <!-- End name field -->


                        <!-- Start desription field -->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Desription</label>
                            <div class="col-xs-6 ">
                                <input type="text" class="form-control" name="description"
                                       value="<?php echo $item['description'] ?>">
                            </div>
                        </div>
                        <!-- End desription field -->


                        <!-- Start price field -->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Price</label>
                            <div class="col-xs-6 ">
                                <input type="text" class="form-control" name="price"
                                       value="<?php echo $item['price'] ?>">
                            </div>
                        </div>
                        <!-- End price field -->

                        <!-- Start country field -->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Country</label>
                            <div class="col-xs-6 ">
                                <input type="text" class="form-control" name="country"
                                       value="<?php echo $item['country'] ?>">
                            </div>
                        </div>
                        <!-- End country field -->

                        <!-- Start status field -->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Status</label>
                            <div class="col-xs-6 ">
                                <select class="form-control" name="status">
                                    <option value="1" <?php if ($item['status'] == 1) {
                                        echo "selected";
                                    } ?> >New
                                    </option>
                                    <option value="2" <?php if ($item['status'] == 2) {
                                        echo "selected";
                                    } ?> >Used
                                    </option>
                                    <option value="3" <?php if ($item['status'] == 3) {
                                        echo "selected";
                                    } ?> >Old
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!-- End status field -->

                        <!-- Start members field -->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Members</label>
                            <div class="col-xs-6 ">
                                <select class="form-control" name="member">
                                    <?php
                                    $mstmt = $con->prepare("select * from users");
                                    $mstmt->execute();
                                    $members = $mstmt->fetchAll();

                                    foreach ($members as $member) {
                                        echo "<option value='" . $member['userID'] . "'";
                                        if ($item['userID'] == $member['userID']) {
                                            echo 'selected';
                                        }
                                        echo ">" . $member['userName'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End members field -->

                        <!-- Start categories field -->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">category</label>
                            <div class="col-xs-6 ">
                                <select class="form-control" name="category">
                                    <?php
                                    $cstmt = $con->prepare("select * from categories");
                                    $cstmt->execute();
                                    $categories = $cstmt->fetchAll();

                                    foreach ($categories as $category) {
                                        echo "<option value='" . $category['categoryID'] . "'";
                                        if ($item['categoryID'] == $category['categoryID']) {
                                            echo 'selected';
                                        }
                                        echo ">" . $category['categoryName'] . "</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <!-- End categories field -->

                        <!-- Start tags field -->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Tags</label>
                            <div class="col-xs-6 ">
                                <input type="text" placeholder="ex: Discounts, Quality, Guarantee" class="form-control" name="tags" value="<?php echo $item['tags']?>">
                            </div>
                        </div>
                        <!-- End tags field -->

                        <input type="submit" class="btn btn-primary" value="Edit the item">
                    </form>



                        <?php

                        // Get the data from Data base
                        $stmt = $con->prepare("SELECT comments.*,users.userName from comments 
                                                       inner join users ON comments.userID = users.userID
                                                       where itemid = $itemID
                                                 ");
                        $stmt->execute(array($itemID));
                        $comments = $stmt->fetchAll();

                        if(! empty($comments))
                        { ?>
                               <!-- Start manage comments -->
                         <h1 class="text-center">Manage <span style='color:#800;' ><?php echo $item['itemName']; ?> </span> Comments</h1>

                         <table class="table manage_table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Comment</th>
                            <th scope="col">Commenter</th>
                            <th scope="col">Item</th>
                            <th scope="col">Registered Date</th>
                            <th scope="col">Control</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($comments as $comment)
                            {
                                echo '<tr>';
                                echo '<td>'.$comment['commentID'].  '</td>';
                                echo '<td>'.$comment['content'].'</td>';
                                echo '<td>'.$comment['userName'].   '</td>';
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
                            <!-- End manage comments -->
                            </div>
                        <?php }

                        }

        }

        //update page
        elseif ($actionName == 'update')
        {

            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $itemID         = $_POST['itemID'];
                $itemName       = $_POST['itemName'];
                $description    = $_POST['description'];
                $price          = $_POST['price'];
                $country        = $_POST['country'];
                $status         = $_POST['status'];
                $memberID       = $_POST['member'];
                $categoryID     = $_POST['category'];
                $tags           = $_POST['tags'];


                $errors = array();

                if (empty($itemName))
                {
                    $errors[] = "ItemName field can't be empty";
                }
                if (empty($description))
                {
                    $errors[] = "Description field can't be empty";
                }
                if (empty($price))
                {
                    $errors[] = "Price field can't be empty";
                }

                if (empty($country))
                {
                    $errors[] = "Country field can't be empty";
                }

                if (empty($status))
                {
                    $errors[] = "You have to choose a status";
                }

                if (empty($memberID))
                {
                    $errors[] = "You have to choose a member";
                }

                if (empty($categoryID))
                {
                    $errors[] = "You have to choose a category";
                }


                //if errors are founded show it
                foreach ($errors as $error)
                {
                    echo '<div class="alert alert-danger">'.$error. '</div>';
                }


                // Check if there
                if(empty($errors))
                {

                    // Start update the data from edit form
                    $stmt = $con->prepare ("UPDATE items SET itemName = ?,description = ?, price = ?,country = ?,status = ?,userID = ?,categoryID = ?,tags =? where itemID=?");
                    $stmt->execute(array($itemName,$description,$price,$country,$status,$memberID,$categoryID,$tags,$itemID));  //the elements must be in the same order of the sql statment
                    $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." row has updated</h3>";
                    redirectTo($msg,'back');

                }

            }
            else // Direct without post request
            {
                $msg = "<h3 class='text-center alert alert-danger'>You can't access directly to this page</h3>";
                redirectTo($msg);

            }

        }


        //delete page
        elseif($actionName == 'delete')
        {
            echo "<div class='container'>";

            $itemID = isset($_GET['itemID'] )&& is_numeric($_GET['itemID'])? intval($_GET['itemID']):0;

            // check if the user exists in the database
            $check = checkInDB('itemID','items',$itemID);

            if($check > 0 )
            {

                $stmt = $con->prepare ("DELETE  from items where itemID = :itemID ");
                $stmt->bindParam('itemID',$itemID); //to make the id from get related to the id im the database
                $stmt->execute();
                $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." row has deleted</h3>";
                redirectTo($msg);

            }
            else // if we don't find this id
            {
                $msg = "<h3 class='text-center alert alert-danger'>Sorry, this person doesn't exist</h3>";
                redirectTo($msg);

            }
        }


        //approve page
        elseif($actionName == 'approve')
        {
            echo "<div class='container'>";

            $itemID = isset($_GET['itemID'] )&& is_numeric($_GET['itemID'])? intval($_GET['itemID']):0;

            // check if the user exists in the database
            $check = checkInDB('itemID','items',$itemID);

            if($check > 0 ) {

                $stmt = $con->prepare ("UPDATE items SET approve = 1 where itemID = :itemID ");
                $stmt->bindParam('itemID',$itemID); //to make the id from get related to the id im the database
                $stmt->execute();
                $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." item has approved </h3>";
                redirectTo($msg,'back');

            }
            else // if we don't find this id
            {
                $msg = "<h3 class='text-center alert alert-danger'>Sorry, this person doesn't exist</h3>";
                redirectTo($msg);

            }


        }


        require $temps.'footer.php';
    }
    else
    {
        header('location:index.php');
        exit();
    }

    ob_end_flush();

?>