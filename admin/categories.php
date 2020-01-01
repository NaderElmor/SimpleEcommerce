<?php

// Manage members page (add - Edit - update - delete)
session_start();
if(isset($_SESSION['username']))
{
    $pageTitle = 'Categories';
    require 'init.php';

    $actionName = isset($_GET['actionName'])? $_GET['actionName'] : 'Main';
   
    switch ($actionName)
    {
        /*****************
         *   Add page    *
         *****************/
        case  'add' :
            ?>
            <div class="container ">
                <h1 class="text-center">Add Category</h1>
                <form class="form-horizontal req" action="categories.php?actionName=insert" method="POST">
                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Category Name</label>
                        <div class="col-xs-6 ">
                            <input type="text" class="form-control" name="categoryName"  autocomplete="off" required="required">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Description</label>
                        <div class="col-xs-6 ">
                            <input type="text" class="form-control" name="description">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class=" col-xs-3 control-label">Arrange</label>
                        <div class="col-xs-6 ">
                            <input type="text" class="form-control" name="ordering"  autocomplete="off" placeholder="you can give a number to order">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class=" col-xs-3 control-label">Category Type</label>
                        <div class="col-xs-6 ">
                           <select class="form-control" name="parent">
                               <option value="0">Main Category</option>
                               <?php
                                    $mainCategories = getAllFrom('*','categories','where parent = 0','categoryName','ASC');

                                    foreach ($mainCategories as $category)
                                    {
                                        echo "<option value='".$category['categoryID']."'>".$category['categoryName']."</option>";
                                    }

                               ?>
                           </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Visibile</label>
                        <div class="col-xs-9 ">
                            <div class="radiocat">
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                                <label for="vis-yes">Yes</label>

                                <input id="vis-no" type="radio" name="visibility" value="1">
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Allow commenting</label>
                        <div class="col-xs-9 ">
                            <div class="radiocat">
                                <input id="com-yes" type="radio" name="comment" value="0" checked>
                                <label for="com-yes">Yes</label>

                                <input id="com-no" type="radio" name="comment" value="1">
                                <label for="com-no">No</label>
                            </div>
                    </div>

                        </div>

                    <div class="form-group">
                        <label class=" col-xs-3 control-label">Allow Ads</label>
                        <div class="col-xs-9 ">
                            <div class="radiocat">
                                <input id="ads-yes" type="radio" name="ads" value="0" checked>
                                <label for="ads-yes">Yes</label>

                                <input id="ads-no" type="radio" name="ads" value="1">
                                <label for="ads-no">No</label>
                            </div>


                        </div>


                    </div>




                    <div class="form-group">
                        <div class="col-xs-12 text-center">
                            <input class="btn btn-primary" type="submit" name="edit" value="Add Category"/>
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

            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {

                echo " <h1 class=\"text-center\">Insert Category</h1>";
                echo "<div class='container'>";

                $categoryName   = $_POST['categoryName'];
                $description    = $_POST['description'];
                $ordering       = $_POST['ordering'];
                $visibility     = $_POST['visibility'];
                $comment        = $_POST['comment'];
                $ads            = $_POST['ads'];
                $mainCategories = $_POST['parent'];




                    // check first if there is a category has the same name or not
                    $check = checkInDB('categoryName','categories',$categoryName);

                    if($check == 1)
                    {
                        $msg = "<div class='alert alert-danger'>There is a category has the same name. try another one</div>";
                        redirectTo($msg,'back');
                    }
                    else
                    {
                        // Start insert in the database
                        $stmt = $con->prepare('insert into categories(categoryName ,description ,parent,ordering,visibility,allowComment,allowAds) 
                                                                   values    (:catName,:descr, :parent,:ordering,:vis,:comment,:ads)');
                        /* Notice that u can put any name :anything */

                        $stmt->execute(array(

                            ':catName'  =>  $categoryName,
                            ':descr'    =>  $description,
                            ':parent'    => $mainCategories,
                            ':ordering' =>  $ordering,
                            ':vis'      =>  $visibility,
                            ':comment'  =>  $comment,
                            ':ads'     =>  $ads
                        ));

                        $msg = "<h3 class='text-center alert alert-success'><b>".$categoryName."</b> Category has inserted</h3>";
                        redirectTo($msg,'back');
                    }





            }
            else
            {
                $msg = '<div class="alert alert-danger">You can\'t access directly to this page</div>';
                echo '<div class="container">';
                    redirectTo($msg);
                echo '</div>';

            }

            break;




        /*****************
         *  Edit page  *
         *****************/
        case  'edit' :

        $categoryID = isset($_GET['categoryID'] )&& is_numeric($_GET['categoryID'])? intval($_GET['categoryID']):0;

        // check if the user exists in the database
        $stmt = $con->prepare ("select * from categories  where categoryID = ?");

        $stmt->execute(array($categoryID));
        $categ = $stmt->fetch(); //get the first row in the database (array)
        $count = $stmt->rowCount();

        if($count > 0 ) {?>

    <div class="container ">
                    <h1 class="text-center">Edit Category</h1>
                    <form class="form-horizontal req" action="categories.php?actionName=update" method="POST">
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Category Name</label>
                            <div class="col-xs-6 ">
                            <input type="hidden" name="categoryID" value="<?php echo $categ['categoryID']?>" />
                                <input type="text" class="form-control" name="categoryName" required="required" value="<?php echo $categ['categoryName']?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Description</label>
                            <div class="col-xs-6 ">
                                <input type="text" class="form-control" name="description" value="<?php echo $categ['description']?>">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <label class=" col-xs-3 control-label">Arrange</label>
                            <div class="col-xs-6 ">
                                <input type="text" class="form-control" name="ordering"   placeholder="you can give a number to order" value="<?php echo $categ['ordering']?>">
                            </div>
                        </div>

                        <!-- Start categories field -->
                        <div class="form-group">
                            <label class=" col-xs-3 control-label">category</label>
                            <div class="col-xs-8 ">
                                <select class="form-control" name="category" required="required">
                                    <option value="0" >Main Category</option>
                                  <?php
                                    $categories = getAllFrom('*','categories',null,'categoryID');
                                    foreach($categories as $category)
                                    {
                                        echo "<option value='".$category['categoryID']."'";

                                        if( $category['categoryID'] == $categ['parent'])
                                        {
                                            echo ' selected';
                                        }

                                       echo ">".$category['categoryName']."</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <!-- End categories field -->

                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Visibile</label>
                            <div class="col-xs-9 ">
                                <div class="radiocat">
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($categ['visibility'] == 0){echo 'checked';}?>>
                                    <label for="vis-yes">Yes</label>

                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if($categ['visibility'] == 1){echo 'checked';}?>>
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Allow commenting</label>
                            <div class="col-xs-9 ">
                                <div class="radiocat">
                                    <input id="com-yes" type="radio" name="comment" value="0" <?php if($categ['allowComment'] == 0){echo 'checked';}?>>
                                    <label for="com-yes">Yes</label>

                                    <input id="com-no" type="radio" name="comment" value="1" <?php if($categ['allowComment'] == 1){echo 'checked';}?>>
                                    <label for="com-no">No</label>
                                </div>
                        </div>

                            </div>

                        <div class="form-group">
                            <label class=" col-xs-3 control-label">Allow Ads</label>
                            <div class="col-xs-9 ">
                                <div class="radiocat">
                                    <input id="ads-yes" type="radio" name="ads" value="0"  <?php if($categ['allowAds'] == 0){echo 'checked';}?>>
                                    <label for="ads-yes">Yes</label>

                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if($categ['allowAds'] == 1){echo 'checked';}?>>
                                    <label for="ads-no">No</label>
                                </div>


                            </div>


                        </div>




                        <div class="form-group">
                            <div class="col-xs-12 text-center">
                                <input class="btn btn-primary" type="submit" name="edit" value="Edit Category"/>
                            </div>
                        </div>
                    </form>
            </div>
        <?php }
            else
            {
                echo '<div class="container">';
                    $msg = '<div class="alert alert-danger">Sorry, this ID '. $_GET['categoryID']. ' is not exist</div>';
                    redirectTo($msg);
                 echo '</div>';

            }

            break;


            /*****************
             *  Update page  *
             *****************/
            case 'update':

                echo " <h1 class='text-center'>Update Category</h1>";

                echo "<div class='container'>";
                if ($_SERVER['REQUEST_METHOD'] == 'POST')
                {

                    $categoryID    = $_POST['categoryID'];
                    $categoryName  = $_POST['categoryName'];
                    $description   = $_POST['description'];
                    $ordering      = $_POST['ordering'];
                    $visibility    = $_POST['visibility'];
                    $comment       = $_POST['comment'];
                    $ads           = $_POST['ads'];
                    $parent        = $_POST['category'];



                  
                        // Start update the data from edit form
                        $stmt = $con->prepare (
                                                "UPDATE categories 
                                                 SET categoryName = ?,
                                                     description  = ?,
                                                     ordering     = ?,
                                                     visibility   = ?,
                                                     allowComment = ?,
                                                     allowAds     = ?,
                                                     parent       = ?
                                                      where categoryID =?
                                              ");

                        $stmt->execute(array($categoryName,$description,$ordering,$visibility,$comment,$ads,$parent,$categoryID));  //the elements must be in the same order of the sql statment
                        $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." row has updated</h3>";
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

            $categoryID = isset($_GET['categoryID'] )&& is_numeric($_GET['categoryID'])? intval($_GET['categoryID']):0;

            // check if the user exists in the database
            $check = checkInDB('categoryID','categories',$categoryID);

            if($check > 0 ) {

                $stmt = $con->prepare ("DELETE  from categories where categoryID = :categoryID ");
                $stmt->bindParam('categoryID',$categoryID); //to make the id from get related to the id im the database
                $stmt->execute();
                $msg = "<h3 class='text-center alert alert-success'>".$stmt->rowCount()." row has deleted</h3>";
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
            default: 
                    if(isset($_GET['page'])  && $_GET['page'] == 'manage')
                     
                        $sort='';
                        $sort = 'asc';

                        $sort_array = array('asc','desc');
                        if(isset($_GET['sort']) && in_array( $_GET['sort'],$sort_array))
                        {
                            $sort = $_GET['sort'];
                        }

                            // Get the data from Data base
                            $stmt='';
                            $stmt = $con->prepare("SELECT * FROM categories where parent = 0 ORDER BY ordering $sort");
                            $stmt->execute();
                            $categories = $stmt->fetchAll();?>
                            <div class="container cats">
                            <h1 class="text-center">Manage Categories</h1>

                            <div class="panel panel-default">
                                            <div class="panel-heading">
                                            <i class="fas fa-edit"></i> Manage Categories
                                                <div class='option pull-right'>
                                                    <b style="color:#6c5ce7;"><i class="fas fa-sort"></i> Ordering: </b>[
                                                    <a class="<?php if($sort == 'asc'){echo 'active';} ?>" href="categories.php?sort=asc"> Asc</a> |
                                                    <a class="<?php if($sort == 'desc'){echo 'active';}?>" href='categories.php?sort=desc'>Desc </a>]    
                                                    <b style="color:#6c5ce7;"><i class="fas fa-eye"></i>   View: </b> [<span class='active' data-view='full'> Full</span> |
                                                          <span data-view='classic'>Classic</span> ]
                                                          
                                                </div>
                                            </div>


                                            <div class="panel-body">
                                            <?php
                                            foreach ($categories as $category)
                                            {
                                                echo '<div class="cat">';
                                                echo "<div class='hidden-buttons'>";
                                                    echo "<a href='categories.php?actionName=edit&categoryID=". $category['categoryID'] ."'  class='btn btn-xs btn-primary'><i class='fas fa-edit'> </i> Edit</a>";
                                                    echo "<a  href='categories.php?actionName=delete&categoryID=". $category['categoryID'] ."'   class='btn btn-xs btn-danger confirm'><i class='fas fa-trash-alt'> </i> Delete</a>";
                                                    echo "</div>";
                                                    echo "<h3>". $category['categoryName'] . "</h3>";
                                                    echo'<div class="full-view">';
                                                        if(empty($category['description']))
                                                            {echo '<p>No description</p>';}
                                                        else
                                                            {echo "<p>" .$category['description'] . "</p>";}
                                                        if($category['visibility'] == 1){echo '<span class="vis"><i class="fas fa-eye"></i> Hidden</span>';}
                                                        if($category['allowComment'] == 1){echo '<span class="comment"><i class="fas fa-times-circle"></i> Comment disabled</span>';}
                                                        if($category['allowAds'] == 1){echo '<span class="ad"><i class="fas fa-times-circle"></i> Ads disabled</span>';}

                                                        // Get sub Categories
                                                        $subCategories = getAllFrom("*","categories","where parent = {$category['categoryID']}","categoryID");

                                                        if(!empty($subCategories))
                                                        {
                                                            echo "<h4 class='sub-head'>Sub Categories</h4>";
                                                            echo "<uL class='list-unstyled sub-cats'>";
                                                            foreach ($subCategories as $subCategory)
                                                            {
                                                                echo "<li><a title='Edit' href='categories.php?actionName=edit&categoryID=". $subCategory['categoryID'] ."'>". $subCategory['categoryName'] ."</li>";
                                                                echo "<a  title='Delete'  href='categories.php?actionName=delete&categoryID=". $subCategory['categoryID'] ."'   class='btn btn-xs btn-danger confirm sub-icon '><i class='fas fa-trash-alt'> </i></a>";

                                                            }
                                                            echo "</ul>";
                                                        }
                                                     echo'</div>';
                                                echo '</div>';



                                                echo "<hr>";




                                            }?>

                                            </div> 
                                        </div>
                                        <a class="btn btn-primary" href="categories.php?actionName=add"><i class="fas fa-plus"></i> Add new member</a>

                                        </div><?php break;

            }
            require $temps.'footer.php';
    }
    else
    {
        header('location:index.php');
        exit();
    }
