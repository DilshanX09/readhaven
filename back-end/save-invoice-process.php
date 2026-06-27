<?php
    
    session_start();
    include '../connection.php';

    if(isset($_SESSION['user'])){

        $order_id = $_POST["order_id"];
        $book_id = $_POST["book_id"];
        $user = $_POST["user"];
        $amount = $_POST["amount"];
        $qty = $_POST["qty"];

        $book_rslt = Database::search("SELECT * FROM `books` WHERE `book_id` = '".$book_id."'");
        $book_data = $book_rslt->fetch_assoc();

        $current_qty = $book_data['qty'];
        $new_qty = $current_qty - $qty;

        Database::insert("UPDATE `books` SET `qty` = '$new_qty' WHERE `book_id` = '".$book_id."'");

        $create_date = new DateTime();
        $timeZone = new DateTimeZone("Asia/Colombo");
        $create_date->setTimezone($timeZone);
        $date = $create_date->format("Y-m-d");

        Database::insert("INSERT INTO `invoice` (
            `order_id`,
            `date`,
            `total`,
            `invoice_qty`,
            `status`,
            `book_id`,
            `users_email`
        ) VALUES (
            '".$order_id."',
            '".$date."',
            '".$amount."',
            '".$qty."',
            '0',
            '".$book_id."',
            '".$user."'
        ) ");

        echo 'success';


    } else {

        header("Location:sign-in.php");

    }
