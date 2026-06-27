<?php
include "../connection.php";

if (isset($_GET['id'])) {

    $list_id = $_GET['id'];

    $watchlist_rs = Database::search("
        SELECT * FROM `watchlist` WHERE `w_id` = '" . $list_id . "' 
    ");

    if ($watchlist_rs->num_rows == 0) {

        echo "Somthing went wrong!";

    } else {

        Database::insert("DELETE FROM `watchlist` WHERE `w_id` = '" . $list_id . "'");

        echo "deleted";
    }
}
