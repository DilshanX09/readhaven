<?php

session_start();

include '../connection.php';

if (isset($_SESSION['user'])) {

    if (isset($_GET['bid'])) {

        $email = $_SESSION['user']['email'];
        $book_id = $_GET['bid'];
        $book_qty = $_GET['qty'];

        $cart_result = Database::search("SELECT * FROM `cart` WHERE `cart_users_email` = '" . $email . "' AND `cart_book_id` = '" . $book_id . "' ");

        if ($cart_result->num_rows == 1) {

            $cart_data = $cart_result->fetch_assoc();
            $current_qty = $cart_data['cart_qty'];
            $new_qty = (int) $current_qty + 1;

            if ($book_qty >= $new_qty) {

                Database::insert("UPDATE `cart` SET `cart_qty` = '" . $new_qty . "' WHERE `cart_users_email` = '" . $email . "' AND `cart_book_id` = '" . $book_id . "'");
                echo 'updated';
            } else {
                echo 'invalid quantity';
            }
        } else {

            Database::insert("INSERT INTO `cart` (`cart_qty`,`cart_users_email`,`cart_book_id`) VALUES ('1' , '" . $email . "','" . $book_id . "') ");
            echo 'added';
        }
    } else {
        echo 'Somthing went wrong';
    }
}
