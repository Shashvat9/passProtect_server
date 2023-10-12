<?php
    include "libraryx.php";

    $api_key_value="lock123";

    $con = mysqli_connect("localhost","id20839964_root","Images@123","id20839964_images");
    if($con->connect_error)
    {
        echo mysqli_connect_error();
    }

    if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
        $json_st = base64_decode($_POST["json"]);
        $json = json_decode($json_st, true);
        $api_key = $json["api_key"];
        
        if (getdata($api_key) == $api_key_value) {
            if (isset($_POST["validate"])) {
                validate(base64_encode($json["email"]), base64_encode($json["password"]));
            }

            if (isset($_POST["add_user"])) {
                add_user(base64_encode($json["name"]), base64_encode($json["email"]), base64_encode($json["mobile"]), base64_encode($json["password"]));
            }

            if(isset($_POST["send_mail_otp"]))
            {
                send_mail_otp($json["email"]);
            }
            if(isset($_POST["add_pass"]))
            {
                set_user_pass(base64_encode($json["user_id"]),base64_encode($json["pass"]),base64_encode($json["name"]),base64_encode($json["email"]));
            }
            if(isset($_POST["get_pass"]))
            {
                get_pass(base64_encode($json["email"]));
            }
        } else {
            echo "wrong api key";
        }
    } 
    else {
        echo "wrong request. error code = ". http_response_code();
        echo error_get_last();
    }

    function getdata($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function validate($email,$password)
    {
        //  code = 1 > success > print a email
        //  code = 2 > wrong password
        //  code = 3 > no rows or no user with that email
        //  code = 4 > db connection problame
        
        
        include "conn.php";
        $select = "SELECT * FROM user WHERE email = '$email';";
        $select_fire = mysqli_query($con,$select);
        if(isset($select_fire))
        {
            if(mysqli_num_rows($select_fire)>0)
            {
                $select_arr = mysqli_fetch_assoc($select_fire);
                if($password==$select_arr["password"])
                {
                    //success
                    json_send(1,$select_arr["email"]);
                }
                else
                {
                    //wrong password
                    json_send(2,"wrong password");
                }
            }
            else
            {
                // no rows found
                json_send(3,mysqli_error($con));

            }
        }
        else
        {
            // error in db connection
            json_send(4,mysqli_error($con));
        }
    }

    function add_user($name, $email, $mobile, $password)
    {
        // code = 5 > success
        // code = 6 > user added
        // code = 7 > error

        include "conn.php";

        $select = "SELECT * FROM user WHERE email = '$email';";
        $result = mysqli_query($con, $select);

        if ($result) {
            $count = mysqli_num_rows($result);
            if ($count > 0) {
                json_send(5, "This email already exists");
            } else {
                $insert = "INSERT INTO user (name, email, mobile, password) VALUES ('$name', '$email', '$mobile', '$password');";
                $fire_insert = mysqli_query($con, $insert);
                if ($fire_insert) {
                    // Success
                    json_send(6, "User added");
                } else {
                    // Error in query execution
                    json_send(7, "Error in query execution: " . mysqli_error($con));
                }
            }
        } else {
            // Error in SELECT query
            json_send(200, "Error in SELECT query: " . mysqli_error($con));
        }
    }

function send_mail_otp($email)
{
    $otp = rand(100000,999999);
    $message = "This is your otp : ".$otp;
    $obmail= new mail_to_send("vidya.gmit@gmail.com","uwrxrdoyqcrbgecb");
    try{
        $obmail->send_email($email,"OTP",$message);
        json_send(8,$otp);
    }
    catch(PHPMailer\PHPMailer\Exception $e)
    {
        $errorArr = explode(":",$e->getMessage());
        if($errorArr[0]=="Invalid address")
        {
            json_send(9,"wrong email");
        }
        else
        {
            json_send(10,"cant send email");
        }
    }
}
function set_user_pass($userId,$pass,$name,$email){   
    include "conn.php";

    $insert = "INSERT INTO saved_by_user (name, userId, pass, email) VALUES ('$name', '$userId','$pass','$email');";
    $result = mysqli_query($con, $insert);
    if($result){
        json_send(11,"password added sucessfully");
    }
    else{
        json_send(12,"there is an problem in server ".$result);
    }

}

function get_pass($email){
    include "conn.php";
    $json = array();
    $select = "SELECT * FROM saved_by_user WHERE email = '$email';";
    $result = mysqli_query($con, $select);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $json_arr = array("id" => $row["id"], "password" => $row["pass"], "name" => $row["name"], "uid"=>$row["userId"]);
            array_push($json, $json_arr);
        }
        
        $json_with_code = array("code" => "130", "data" => $json);
        $jsonstring = json_encode($json_with_code);
        echo base64_encode($jsonstring);
    } else {
        json_send(140, "error in query execution ".mysqli_error($cod));
    }
}


function json_send($code,$message)
{
    $json_arr=array("code"=>$code,"message"=>$message);
    $json=json_encode($json_arr);
    echo base64_encode($json);
}
?>