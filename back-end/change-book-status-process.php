<?php
    include "../connection.php";

    $book_id = $_GET['id'];
    $book_srch = Database::search("SELECT * FROM `books` WHERE `book_id` = '".$book_id."'");

    if($book_srch->num_rows == 1){
        $book_data = $book_srch->fetch_assoc();
        $status = $book_data['active_active_id'];
        if($status == 1){
            Database::insert("UPDATE `books` SET `active_active_id` = '2' WHERE `book_id` = '".$book_id."' ");
            echo "Deactive";
        } else {
            Database::insert("UPDATE `books` SET `active_active_id` = '1' WHERE `book_id` = '".$book_id."' ");
            echo "Active";
        }
    } else {
        echo 'Somthing went wrong. Try again later';
    }
