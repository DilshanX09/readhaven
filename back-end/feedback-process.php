<?php
session_start();
include '../connection.php';

if (isset($_SESSION['user'])) {

    $user_email = $_SESSION['user']['email'];
    $customer_mode = $_POST['customerMode'];
    $rating = $_POST['rating'];
    $features = $_POST['features'];
    $message = $_POST['feedback'];
    $book_id = $_POST['book_id'];

    if ($customer_mode == 0) {
        echo 'Choose your mood!';
    } else if (empty($rating)) {
        echo 'Give the products rating';
    } else if (empty($message)) {
        echo 'Enter your feedback message!';
    } else {
        $create_date = new DateTime();
        $timeZone = new DateTimeZone("Asia/Colombo");
        $create_date->setTimezone($timeZone);
        $date = $create_date->format("Y-m-d");

        Database::insert("INSERT INTO  `feedback` (
            `feed_date`,
            `customer_mode`,
            `rating`,
            `feed _feature`,
            `feedback_msg`,
            `feed_book_id`,
            `users_email` 
        ) VALUES (
            '$date',
            '$customer_mode',
            '$rating',
            '$features',
            '$message',
            '$book_id',
            '$user_email'
        )");

        echo 'success';
    }
} else {
    header("Location:sign-in.php");
}
