<?php

session_start();

include '../connection.php';
require '../mail/SMTP.php';
require '../mail/PHPMailer.php';
require '../mail/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$username = $_POST['a_username'];
$password = $_POST['a_password'];

if (empty($username)) {
    echo 'Enter your username';
} else if (strlen($username) > 16 || strlen($username) < 8) {
    echo 'Username must contain 8 to 16 characters';
} else if (!preg_match('/^[a-zA-Z0-9]{8,}$/', $username)) {
    echo 'Enter valid username';
} else if (empty($password)) {
    echo 'Please enter your password';
} else if (strlen($password) > 16 || strlen($password) < 8) {
    echo 'Username must contain 8 to 16 characters';
} else {

    $admin = Database::search("SELECT * FROM `admin` WHERE `username` = '$username' AND `password` = '$password'");

    if ($admin->num_rows == 1) {

        $admin_data = $admin->fetch_assoc();
        $admin_email = $admin_data['email'];

        $code = random_int(100000, 999999);

        Database::insert("UPDATE `admin` SET `vcode` = '" . $code . "' WHERE `email` = '" . $admin_email . "' ");

        try {
            $mail = new PHPMailer;
            $mail->IsSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '<YOUR_EMAIL_HERE>';
            $mail->Password = '<YOUR_APP_PASSWORD_HERE>';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('<YOUR_EMAIL_HERE>', 'Readhaven');
            $mail->addReplyTo('<YOUR_EMAIL_HERE>', 'Readhaven Support');
            $mail->addAddress($admin_email);

            $mail->isHTML(true);

            $mail->Subject = 'ReadHaven - Your Admin Account Verification OTP Code';
            $template = file_get_contents(__DIR__ . '/../email-templates/admin-verification.html');
            $template = str_replace('{{CODE}}', $code, $template);
            $template = str_replace('{{USERNAME}}', $admin_data['username'], $template);

            $mail->Body = $template;

            if (!$mail->send()) {
                echo "Verification sending Failed";
            } else {
                $_SESSION['ADMIN_UID'] = $admin_data;
                echo 'success';
            }
        } catch (Exception $e) {
            echo "Somthing wen't wrong, please try again.....!";
        }
    } else {
        echo 'Invalid username or password';
    }
}
