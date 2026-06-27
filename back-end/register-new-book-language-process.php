<?php
    include "../connection.php";

    $language = $_POST['lang'];

    if(empty($language)){

        echo "Please type name of language";

    } else {

        $lng_count = Database::search("SELECT * FROM `language` WHERE `language` LIKE '%".$language."%' ");

        if($lng_count->num_rows == 1){

            echo "This language already used";

        } else {

            Database::insert("INSERT INTO `language` (`language`) VALUE ('".$language."') ");
            echo 'success';
        }

    }

    
