<?php

session_start();

include "../connection.php";
include "../Mail/SMTP.php";
include "../mail/PHPMailer.php";
include "../mail/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (empty($_POST["email"])) {

     echo "* Please enter your email address";
     exit;
} else if (isset($_POST["email"])) {

     $email = $_POST["email"];

     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          echo '* Please provide valid email address';
          exit;
     }

     $user_rs = Database::search("SELECT * FROM `users` WHERE `email` = '" . $email . "' ");
     $user = $user_rs->num_rows;
     $data = $user_rs->fetch_assoc();
     $fname = $data['first_name'];

     if ($user == 1) {
          $code = random_int(100000, 999999);
          Database::insert("UPDATE `users` SET `v_code` = '" . $code . "' WHERE `email` = '" . $email . "' ");

          $mail = new PHPMailer(true);

          try {

               $mail->isSMTP();
               $mail->Host = 'smtp.gmail.com';
               $mail->SMTPAuth = true;
               $mail->Username = '<YOUR_EMAIL_HERE>';
               $mail->Password = '<YOUR_APP_PASSWORD_HERE>';
               $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
               $mail->Port = 465;

               $mail->setFrom('<YOUR_EMAIL_HERE>', 'ReadHaven');
               $mail->addAddress($email);
               $mail->addReplyTo('<YOUR_EMAIL_HERE>', 'ReadHaven Support');

               $mail->isHTML(true);

               $mail->Subject = 'ReadHaven - Your Password Reset OTP Code';
               $template = file_get_contents(__DIR__ . '/../email-templates/user-password-reset.html');
               $template = str_replace('{{CODE}}', $code, $template);
               $template = str_replace('{{EMAIL}}', $email, $template);

               $mail->Body = $template;
               $mail->AltBody = "Your ReadHaven password reset code is: $code. Use this code within 10 minutes. If you didn't request this, please ignore this email.";

               $mail->send();

               echo 200;
               exit;
          } catch (Exception $e) {
               error_log("Email sending failed: " . $e->getMessage());
               echo "* Verification sending Failed. Please try again later.";
          }
     } else {
          echo "* Invalid Email Address";
     }
} else {
     echo "* First enter your Email Address";
}
