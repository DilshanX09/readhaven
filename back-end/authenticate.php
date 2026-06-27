<?php

session_start();

include "../connection.php";

$email = $_POST["email"];
$password = $_POST["password"];
$rememberMe = $_POST["rememberMe"];

if (empty($email)) {
    echo ("Please enter your email address");
    exit;
}
if (strlen($email) > 100) {
    echo ("Email address must contain 100 characters");
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Invalid email address");
    exit;
}
if (empty($password)) {
    echo ("Please enter your password");
    exit;
}
if (strlen($password) < 5 || strlen($password) > 16) {
    echo ("Password must contain between 5 to 16 characters");
    exit;
}

$user_result = Database::search("SELECT * FROM `users` WHERE `email` = '" . $email . "' AND `password` = '" . $password . "'");
$user_count = $user_result->num_rows;

if ($user_count == 1) {

    $data = $user_result->fetch_assoc();
    $_SESSION["user"] = $data;

    if ($rememberMe === "true") {
        setcookie("email", $email, time() + (60 * 60 * 24 * 365), "/");
        setcookie("password", $password, time() + (60 * 60 * 24 * 365), "/");
    }

    echo 200;
    
} else {
    echo "Invalid email address or password";
}
