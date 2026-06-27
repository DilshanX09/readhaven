<?php
session_start();
include "../connection.php";

if (isset($_GET['id'])) {

    $book_id = $_GET['id'];

    $book_srch = Database::search("SELECT * FROM `cart` WHERE `cart_book_id` = '" . $book_id . "' AND `cart_users_email` = '" . $_SESSION['user']['email'] . "' ");

    if ($book_srch->num_rows == 1) {
        $book_data = $book_srch->fetch_assoc();
        $new_qty = $book_data['cart_qty'] - 1;

        if ($new_qty != 0) {
            Database::insert("UPDATE `cart` SET `cart_qty` = '" . $new_qty . "' WHERE `cart_book_id` = '" . $book_id . "' AND `cart_users_email` = '" . $_SESSION['user']['email'] . "' ");
            echo 'success';
        }
    }
}
