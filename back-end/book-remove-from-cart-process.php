<?php

include '../connection.php';

if (isset($_GET['bid'])) {

    $book_id = $_GET['bid'];
    Database::insert("DELETE FROM `cart` WHERE `cart_book_id` = '" . $book_id . "' ");

    echo 'removed';

} else {

    echo 'Somthing went wrong';
}
