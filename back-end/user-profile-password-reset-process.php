<?php

include "../connection.php";

session_start();

$vcode = $_POST["code"];
$email = $_POST["email"];

if (empty($email)) {
    header('location:reset-password.php');
} else if (empty($vcode)) {
    echo "* Please enter your verification code";
} else {

    $user = Database::search("SELECT * FROM `users` WHERE `email` = '" . $email . "' AND `v_code` = '" . $vcode . "' ");

    if ($user->num_rows == 1) {
        echo 200;
    } else {
        echo "* Invalid verification code";
    }
}
