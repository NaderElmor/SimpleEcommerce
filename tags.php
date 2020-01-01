<?php
    session_start();
    require 'init.php';
?>

    <div class="container">
        <div class="row">
            <?php


            if(isset($_GET['tagName']))
            {
                $tagName = $_GET['tagName'];

                echo "<h1 class='text-center'>". $tagName."</h1>";


                $items =  getAllFrom("*","items","where tags like '%$tagName%'  AND approve = 1","categoryID");

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

                    echo '</div>';

                    echo '</div>';
                    echo '</div>';
                }
            }else
            {
                echo "<div class='alert alert-danger'>You havn't choose a category</div>";
            }

            ?>

        </div>

    </div>

<?php require $temps.'footer.php'; ?>