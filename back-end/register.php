<?php
include "../connection.php";

$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$mobile = $_POST["mobile"];
$email = $_POST["email"];
$password = $_POST["password"];
$agree = $_POST["agree"];

if (empty($first_name)) {
    echo ("Please enter your first name");
} else if (strlen($first_name) > 20) {
    echo ("First name must contain 20 characters");
} else if (empty($last_name)) {
    echo ("Please enter your last name");
} else if (strlen($last_name) > 20) {
    echo ("Last name must contain 20 characters");
} else if (empty($mobile)) {
    echo ("Please enter your mobile number");
} else if (strlen($mobile) > 10) {
    echo ("Mobile number must contain 10 characters");
} else if (!preg_match("/07[0,1,2,4,5,6,7,8]{1}[0-9]{7}/", $mobile)) {
    echo ("Invalid mobile number");
} else if (empty($email)) {
    echo ("Please enter your email address");
} else if (strlen($email) > 100) {
    echo ("Email address must contain 100 characters");
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Invalid email address");
} else if (empty($password)) {
    echo ("Please enter your password");
} else if (strlen($password) < 5 || strlen($password) > 16) {
    echo ("Password must contain between 5 to 16 characters");
} else if ($agree == "false") {
    echo ("Please agree to the terms of services.");
} else {
    $result = Database::search("SELECT* FROM `users` WHERE `email` = '" . $email . "' OR `mobile` = '" . $mobile . "' ");
    $rows = $result->num_rows;

    if ($rows > 0) {

        echo ("This email eddress or mobile number already exists");
    } else {

        $createDate = new DateTime();
        $timeZone = new DateTimeZone("Asia/Colombo");
        $createDate->setTimezone($timeZone);
        $currentDate = $createDate->format("Y-m-d H:i:s");

        Database::insert("INSERT INTO `users` (`first_name` , `last_name` , `mobile` , `email` , `password`,`joined_date`,`active_id`) 
                VALUES ('" . $first_name . "' , '" . $last_name . "' , '" . $mobile . "' , '" . $email . "' , '" . $password . "' , '" . $currentDate . "' , '1' )
            ");

        echo 200;
    }
}
