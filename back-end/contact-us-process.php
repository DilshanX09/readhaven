<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../connection.php';
include "../mail/SMTP.php";
include "../mail/PHPMailer.php";
include "../mail/Exception.php";

$name = $_POST['name'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$subject = $_POST['subject'];
$msg = $_POST['msg'];

$env_email = "<YOUR_EMAIL_HERE>";
$env_app_password = "<YOUR_APP_PASSWORD_HERE>";

if (empty($name)) {
    echo 'Please provide your name.. ! e.g. John Doe';
} else if (empty($email)) {
    echo 'Please provide your email.. ! e.g. john@example.com';
} else if (empty($subject)) {
    echo 'Please provide your mail subject.. ! e.g. Inquiry about your services';
} else if (empty($msg)) {
    echo 'Please enter your message.. !';
} else {

    $mail = new PHPMailer(true);

    try {
        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = "{$env_email}";
        $mail->Password = "{$env_app_password}";
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom($email, $name);
        $mail->addAddress("{$env_email}", 'ReadHaven.lk');
        $mail->addReplyTo("{$env_email}", 'ReadHaven.lk');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "<p><strong>Name:</strong> $name</p>
                          <p><strong>Email:</strong> $email</p>
                          <p><strong>Mobile:</strong> $mobile</p>
                          <p><strong>Message:</strong> $msg</p>";
        $mail->send();
        echo 'success';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
