<?php
    include '../connection.php';

    if(isset($_POST['email'])){
        $email = $_POST['email'];
        $srch = Database::search("SELECT * FROM `users` WHERE `email` = '".$email."'");
        if($srch->num_rows == 1){
            $data = $srch->fetch_assoc();
            if($data['active_id'] == 1){
                Database::insert("UPDATE `users` SET `active_id` = '2' WHERE `email` = '".$email."'");
                echo 'blocked';
            } else {
                Database::insert("UPDATE `users` SET `active_id` = '1' WHERE `email` = '".$email."'");
                echo 'unblocked';
            }
        }
    }
