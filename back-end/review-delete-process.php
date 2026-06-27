<?php
session_start();
include '../connection.php';

if (isset($_POST['id'])) {
     $id = $_POST['id'];
     $email = $_SESSION['user']['email'];

     $feedback_data = Database::search("SELECT * FROM feedback WHERE feed_id = '$id' AND users_email = '$email'");
     if ($feedback_data->num_rows == 1) {
          Database::insert("DELETE FROM feedback WHERE feed_id = '$id' AND users_email = '$email'");
          echo 'success';
     }
} else {
     echo 'Somthing went wrong.. try again...';
}
