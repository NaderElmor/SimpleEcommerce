<?php

    /*
    * Get all data in table
    */
    function  getAllFrom($column,$table,$where = null,$orderbyColumn,$orderASCorDESC = 'DESC')
    {
        global $con;


        $getAll = $con->prepare("SELECT $column from $table  $where order by $orderbyColumn $orderASCorDESC ");
        $getAll->execute();

        $all = $getAll->fetchAll();

        return $all;

    }



    /*
     * Type the title of the page based on $pageTitle variable
     */
    function getTitle()
    {
         global $pageTitle;
        if(isset($pageTitle))
        {
            echo $pageTitle;
        }
        else
        {
            echo 'Default';
        }
    }


    /*
     * Redirect function  $Seconds (seconds before redirecting)
     */
    function redirectTo ($msg,$url =null, $seconds=5)
    {
        if ($url === null)
        {
            $url = 'index.php';
            $link = 'Home Page';
        }
        else
        {
            $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''?$_SERVER['HTTP_REFERER']:'index.php';
            $link = ' the previous page ';
        }

        echo $msg;

        echo "<div class='alert alert-success' >You will be redirected to $link in $seconds seconds</div>";

        header("REFRESH:$seconds;URL=$url");
        exit();
    }



    /*
     * Select function to make sql statements by it
    */

        function checkInDB($column,$table,$value)
        {
            global $con;

            $statement = $con->prepare("SELECT $column  from $table where $column =?");
            $statement->execute(array($value));

            $count = $statement->rowCount();

            return $count;

        }

    /*
     * Select function to make sql statements by it
     */
    function isExistInDB($table,$column1,$value1,$column2= null ,$value2 =null )
    {
        global $con;

        $statement = $con->prepare("SELECT $column1,$column2 from $table where $column1 = ? OR $column2= ?");
        $statement->execute(array($value1,$value2));

        $count = $statement->rowCount();

        return $count;
    }


    /*
     * Count n umber of any items we want
     */

    function countItems ($column,$table)
    {
      global $con;

      $stmt= $con->prepare("SELECT COUNT($column) FROM $table ");
      $stmt->execute();

      return $stmt->fetchColumn();

    }


    /*
     * Get latest items
     */
    function  getLatest($column,$table,$order,$limit = 5)
    {
        global $con;

        $stmt = $con->prepare("SELECT $column from $table ORDER BY $order DESC LIMIT $limit");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return $rows;

    }

/*
*  Check the status of the user
*/
function checkStatus($userName)
{
    global $con;

    $stat = $con->prepare("SELECT userName,regStatus from users  WHERE userName = ? AND regStatus = 0");
    $stat->execute(array($userName));


    $status = $stat->rowCount();

    return $status;

}