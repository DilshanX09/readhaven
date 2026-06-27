<?php
session_start();
include "../connection.php";
require '../mail/SMTP.php';
require '../mail/PHPMailer.php';
require '../mail/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['ADMIN_UID'])) {
    $srch = Database::search("SELECT * FROM `admin` WHERE `email` = '" . $_POST['ADMIN_UID'] . "'");
    if ($srch->num_rows == 1) {
        $email = $_POST['ADMIN_UID'];
        $code = random_int(100000, 999999);

        Database::insert("UPDATE `admin` SET `vcode` = '" . $code . "' WHERE `email` = '" . $_POST['ADMIN_UID'] . "' ");

        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '<YOUR_EMAIL_HERE>';
        $mail->Password = '<YOUR_APP_PASSWORD_HERE>';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('<YOUR_EMAIL_HERE>', 'OTP Verification Code');
        $mail->addReplyTo('<YOUR_EMAIL_HERE>', 'OTP Verification Code');
        $mail->addAddress($_POST['ADMIN_UID']);
        $mail->isHTML(true);
        $mail->Subject = 'ReadHaven Admin Sign Up Verification Code';
        $bodyContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReadHaven | Admin Verification</title>
</head>
<body style="font-family: 'Trebuchet MS', Arial, sans-serif;">
    <div style="display: flex; justify-content: center; height: 100vh; margin-top: 20px;">
        <div style="width: 600px; height: 600px;">
            <div>
                <h1 style="font-weight: bold; color: #FF7777;">ReadHaven</h1>
                <span style="color: gray; font-size: 20px;">Admin Sign in Verification One Time Passcode</span>
                <p><span style="color:black;">Hello</span>, <span style="text-decoration: underline; color: blue;">$email</span></p>
                <p style="color:black;">Welcome! To ensure the safety and security of your account, we need to verify your email address.</p>
                <p>Here's your One Time Password (OTP): <span style="color: #FF7777; font-weight: 700; font-size: 18px;">$code</span></p>
                <p style="color:black;">Please enter this OTP within 10 minutes of receiving this email to complete your verification process.</p>
                <p style="color:black;">Thank you for your cooperation,</p>
                <p style="background-color: rgba(0, 0, 0, 0.03); padding: 5px 10px; border-radius: 5px;">
                    <span style="color:black;">www.ReadHaven.lk</span><br>
                    <span style="color:black;">Colombo Road, Avissawella</span><br>
                    <span style="color:black;">+94 71 917 8824</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;

        $mail->Body = $bodyContent;

        if (!$mail->send()) {
            echo "Verification sending Failed";
        } else {
            echo 'success';
        }
    }
} else {
    echo 'invalid username or password!';
}
