<?php
require 'init.php';
session_start();
?>
  <div class="container">
    <div class="row" style="height: 20px;"></div>

    <div class="container">
        <div class="row">
            <?php
            //check on the get request
            if(!isset($_GET['page']))
            {
                $page = 1;
            }
            else
            {
                $page = $_GET['page'];
            }


            // Determine the number of items in one page
            $itemsINpage= 1;

            $stmt = $con->prepare("select * from items");

            // To pevent search in sql each time I click on a page number, so we store itemsNumber in a  session
            if(!isset($_SESSION['itemsnumber']))
            {
                $stmt->execute();
                $countitems = $stmt->rowCount();
                $_SESSION['itemsnumber'] = $countitems;

            }

            $itemsNumber = $_SESSION['itemsnumber'];

            $numberOfPages = ceil($itemsNumber / $itemsINpage);



            // if the page in get is > max pages make it = the number of pages
             if($page <= 0 || $page > $numberOfPages)
             {
                 $page = $numberOfPages;

             }

             // $page - 1 because sql starts at 0   // * $itemsINpage to increase each time
             $start = ($page - 1 ) * $itemsINpage;
             $end   = $itemsINpage;


            $stmt2 = $con->prepare("select * from items LIMIT $start, $end");
            $stmt2->execute();
            $itemsNumber = $stmt2->rowCount();
            $getItems = $stmt2->fetchAll();

            if($itemsNumber != 0)
            {
                foreach ($getItems as $item)
                {
                    echo $item['itemName']."</br>";

                }
            }

            // Ensure that previous and next buttons in the range of the pages
            $prev = $page - 1;
            if($prev <= 0)
            {
                $prev = 1;
            }

            $next = $page + 1;
            if($prev >= $page)
            {
                $next = $page;
            }

            //print links
            echo "<ul class='pagination'>";
            echo '<li><a href="?page='. $prev .'"> <i class="fas fa fa-arrow-left "></i></a></li>';
            for($i=1;$i<=$numberOfPages;$i++)
            {
                if($i == $page)
                {
                    echo "<li class='active'><a href='#'>".$page."<span class='sr-only'>(current)</span></a></li>";

                }
                else
                {
                    echo '<li><a href="?page='.$i.'">'. $i .'</a></li>';

                }


            }
            echo '<li><a href="?page='. $next .'"> <i class="fas fa fa-arrow-right "></i></a></li>';

            echo "</ul>";






            ?>
        </div>
    </div>

<?php require $temps.'footer.php'; ?>