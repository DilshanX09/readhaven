<?php
include "../connection.php";

if (isset($_GET['id'])) {

    $book_id = $_GET['id'];
    $current_qty = $_GET['qty'];

    $book_srch = Database::search("SELECT * FROM `cart` WHERE `cart_book_id` = '" . $book_id . "' ");

    if ($book_srch->num_rows == 1) {

        $book_data = $book_srch->fetch_assoc();
        $new_qty = $book_data['cart_qty'] + 1;

        if ($current_qty < $new_qty) {
            return null;
        } else {
            Database::insert("UPDATE `cart` SET `cart_qty` = '" . $new_qty . "' WHERE `cart_book_id` = '" . $book_id . "' ");
            echo 'success';
        }
    }
}
