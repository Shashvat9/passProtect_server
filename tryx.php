<?php
    include "libraryx.php";

    $obmail= new mail_to_send("vidya.gmit@gmail.com","uwrxrdoyqcrbgecb");
    $pdf="C:/Users/rajya/Downloads/DI-SEM5REGULARWINTEREXAMINATION-2022_204520307017.pdf";
    try{
        $id = $obmail->send_email("","subject","message0000");
    }
    catch(PHPMailer\PHPMailer\Exception $e)
    {
        $errorArr = explode(":",$e->getMessage());
        echo $errorArr[0];
    }
?>