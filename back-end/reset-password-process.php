<?php

require '../connection.php';

$email = $_POST['email'];
$password = $_POST['password'];
$retypedPassword = $_POST['retypedPassword'];

if (empty($email)) {
     header('location:reset-password.php');
     exit;
} else if (empty($password)) {
     echo '* Enter your new password';
} else if (empty($retypedPassword)) {
     echo '* Enter retyped password';
} else if (!$password == $retypedPassword) {
     echo '* Password do not match. Please check your password.';
} else {

     Database::insert("UPDATE `users` SET `password` = '" . $password . "' WHERE `email` = '" . $email . "' ");
     echo 200;
}
