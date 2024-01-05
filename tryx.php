<?php
    include "libraryx.php";

    $obmail= new mail_to_send("","");
    $pdf="";
    try{
        $id = $obmail->send_email("","subject","message0000");
    }
    catch(PHPMailer\PHPMailer\Exception $e)
    {
        $errorArr = explode(":",$e->getMessage());
        echo $errorArr[0];
    }
?>
