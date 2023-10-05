<?php
    $con = mysqli_connect("host name","user name","password","db_name");
    if($con->connect_error)
    {
        echo mysqli_connect_error();
    }
?>
