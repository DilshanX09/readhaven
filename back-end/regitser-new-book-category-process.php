<?php
    require "../connection.php";

    $category = $_POST["category"];

    if(empty($category)){
        echo "Please enter your category name";
    } else if (!empty($category)){
       
        $category_search = Database::search("SELECT * FROM `book_category` WHERE `category_name` LIKE '%".$category."%' ");
        if($category_search->num_rows > 0){
            echo "This categiry already exits.. please select this category";
        } else {
            Database::insert("INSERT INTO `book_category` (`category_name`) VALUES ('".$category."')");
            echo "success";
        }
          
    }
