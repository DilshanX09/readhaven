<?php

session_start();

include "../connection.php";

if (isset($_SESSION['user'])) {

    if (isset($_GET['id'])) {

        $loged_in_user = $_SESSION['user']['email'];
        $book_id = $_GET['id'];

        $watchlist_books_result = Database::search("SELECT * FROM `watchlist` WHERE `users_email` = '" . $loged_in_user . "' AND `books_book_id` = '" . $book_id . "' ");

        if ($watchlist_books_result->num_rows == 1) {
            $watchlist_data = $watchlist_books_result->fetch_assoc();
            $watchlist_id = $watchlist_data['w_id'];

            Database::insert("DELETE FROM `watchlist` WHERE `w_id` = '" . $watchlist_id . "'");
            echo "Removed";
        } else {

            $createDate = new DateTime();
            $timeZone = new DateTimeZone("Asia/Colombo");
            $createDate->setTimezone($timeZone);
            $currentDate = $createDate->format("Y-m-d");

            Database::insert("INSERT INTO `watchlist` (`books_book_id`,`users_email`,`added_date`) VALUES ('" . $book_id . "','" . $loged_in_user . "','" . $currentDate . "') ");
            echo "Added";
        }
    } else {

        echo "Somthing went wrong, Try again later";
    }
} else {
    header("location:sign-in.php");
}
