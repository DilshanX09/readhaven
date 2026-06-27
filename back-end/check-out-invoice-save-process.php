<?php

session_start();
require_once '../connection.php';

$email = $_SESSION['user']['email'];
$order_id = $_POST['order_id'];

$cart = Database::search("SELECT * FROM cart LEFT JOIN books ON books.book_id = cart.cart_book_id WHERE cart_users_email = '$email'");

if ($cart->num_rows > 0) {
     
     $d = new DateTime();
     $t = new DateTimeZone('Asia/Colombo');
     $d->setTimezone($t);
     $sold_date = $d->format("Y-m-d H:i:s");

     while ($cart_data = $cart->fetch_assoc()) {

          $qty = $cart_data['cart_qty'];
          $book_id = $cart_data['cart_book_id'];

          $price = $cart_data['price'];

          Database::insert("INSERT INTO invoice (
            `order_id`,
            `date`,
            `total`,
            `invoice_qty`,
            `status`,
            `book_id`,
            `users_email`
        ) VALUES (
            '$order_id',
            '$sold_date',
            '$price',
            '$qty',
            '0',
            '$book_id',
            '$email'
        )");

          $book = Database::search("SELECT * FROM books WHERE book_id = '$book_id'");
          $book_data = $book->fetch_assoc();

          $old_qty = $book_data['qty'];
          $sold_qty = $cart_data['cart_qty'];
          $new_qty = $old_qty - $sold_qty;


          Database::insert("UPDATE books SET qty = '$new_qty' WHERE book_id = '$book_id'");
     }


     Database::insert("DELETE FROM cart");

     echo 200;
} else {
     echo 'Cart is empty.';
}
