<?php

    session_start();

    include "./connection.php";

    $email = $_SESSION["user"]["email"];

    $first_name = $_POST["fname"];
    $last_name = $_POST["lname"];
    $mobile = $_POST["mobile"];
    $line1 = $_POST["line01"];
    $line2 = $_POST["line02"];
    $city = $_POST["city"];
    $postal_code = $_POST["pcode"];

    if (empty($first_name)){
        echo "Please enter your first name";
    } else if (strlen($first_name)>20){
        echo "First name must contain 20 characters";
    } else if (empty($last_name)) {
        echo "Please enter your last name";
    } else if (strlen($last_name)>20){
        echo "Last name must contain 20 characters";
    } else if (empty($mobile)) {
        echo "Please enter your mobile number";
    } else if (strlen($mobile)>10){
        echo "Mobile number must contain 10 characters";
    } else if(empty($city)){
        echo "Please select your city";
    } else if (!preg_match("/07[0,1,2,4,5,6,7,8]{1}[0-9]{7}/",$mobile)){
        echo "Invalid mobile number";
    } else if (strlen($postal_code)>10){
        echo "Invalid Postal Code";
    } else {
        
        $user = Database::search("SELECT * FROM `users` WHERE `email` = '".$email."' ");

        if($user->num_rows == 1){
            Database::insert("UPDATE `users` SET `first_name` = '".$first_name."' , `last_name` = '".$last_name."' , `mobile` = '".$mobile."' WHERE `email` = '".$email."' ");
            $address = Database::search("SELECT * FROM `users_has_address` WHERE `users_email` = '".$email."' ");
            if($address->num_rows == 1){
                Database::insert("UPDATE `users_has_address` SET `line1` = '".$line1."' , `line2` = '".$line2."' , `postal_code` = '".$postal_code."' , `city_id` = '".$city."' WHERE `users_email` = '".$email."' ");
            } else {
                Database::insert("INSERT INTO `users_has_address` (`users_email` , `city_id` , `line1`, `line2`,`postal_code`) VALUES ('".$email."' , '".$city."' , '".$line1."', '".$line2."' , '".$postal_code."' ) ");
            }

            if(sizeof($_FILES) == 1){
                $image = $_FILES["image"];
                $extention = $image["type"];
                $allowed_image_extention = array("image/jpeg" , "image/png" , "image/svg+xml");

                if(in_array($extention,$allowed_image_extention)){
                    $new_extention;
                    if($extention == "image/jpeg"){
                        $new_extention = ".jpeg";
                    } else if ($extention == "image/png"){
                        $new_extention = ".png";
                    } else if ($extention == "image/svg"){
                        $new_extention = ".svg";
                    }

                    $file_path = "./img/profile_images/".$first_name."_".uniqid().$new_extention;
                    move_uploaded_file($image["tmp_name"],$file_path);

                    $imageResult = Database::search("SELECT * FROM `profile_img` WHERE `users_email` = '".$email."' ");

                    
                    if($imageResult->num_rows == 1){
                        // update
                        Database::insert("UPDATE `profile_img` SET `img_path` = '".$file_path."' WHERE `users_email` = '".$email."'  ");
                        echo "update";
                    } else {
                        // insert
                        Database::insert("INSERT INTO `profile_img` ( `img_path` , `users_email`) VALUES ('".$file_path."' , '".$email."') ");
                        echo "insert";
                    }
                    
                    
                }
            } 
        } else {
            echo "Invalid user";
        }

    }

