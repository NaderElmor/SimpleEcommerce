<?php
    ob_start();
session_start();

$pageTitle = 'Home';
require 'init.php';

?>

    <div class="container">
    <div class="row" style="height: 20px;"></div>
        <div class="row">
<?php
    $items = getAllFrom('*','items','where approve = 1',null,'itemID');

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

    require $temps.'footer.php';
  ob_end_flush();
