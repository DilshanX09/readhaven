<?php
session_start();
include '../connection.php';

if (isset($_POST['otp'])) {

    if (empty($_POST['otp'])) {
        echo 'Please enter your verification code';
    } else if (strlen($_POST['otp']) > 6 || strlen($_POST['otp']) < 6) {
        echo 'Please enter valid verification code';
    } else {
        $otp = $_POST['otp'];
        $admin_srch = Database::search("SELECT * FROM `admin` WHERE `vcode` = '$otp'");

        if ($admin_srch->num_rows == 1) {
            echo 'success';
        } else {
            echo 'invalid verification code';
        }
    }
} else {
    echo 'Please enter your verification code';
}
