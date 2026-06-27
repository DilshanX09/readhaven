<?php
include "./connection.php";

$book_title = $_POST["title"];
$pages = $_POST["pageCount"];
$current_author = $_POST["currentAuthor"];
$current_category = $_POST["currentCategory"];
$sku = $_POST["SKU"];
$isbn = $_POST["ISBN"];
$description = $_POST["description"];
$qty = $_POST["qty"];
$price = $_POST["price"];
$delivery_colombo = $_POST["deliveryColombo"];
$delivery_other = $_POST["deliveryOther"];
$published_date = $_POST["publishedDate"];
$language = $_POST["language"];

// Data Validate 
if (empty($book_title)) {

    echo "Please enter book title";
} else if (strlen($book_title) > 100) {

    echo "Book title must contain 100 characters";
} else if (empty($pages)) {

    echo "Book page count enter";
} else if (strlen($pages) > 50) {

    echo "pages must contain 50 characters";
} else if (empty($current_author)) {

    echo "Please select book author";
} else if (empty($current_category)) {

    echo "Please select book category";
} else if (empty($sku)) {

    echo "Enter book stock keeping unit number";
} else if (strlen($sku) > 15) {

    echo "Stock keeping unit number must contain 15 characters";
} else if (empty($isbn)) {

    echo "International standard book number enter";
} else if (strlen($isbn) > 15) {

    echo "International standard book number must contain 15 characters";
} else if (empty($price)) {

    echo "Please enter book price";
} else if (empty($delivery_colombo)) {

    echo "Please enter delivery fee in colombo area";
} else if (empty($_FILES["image"])) {

    echo "select image";
} else {
    // Current date get
    $create_date = new DateTime();
    $time_zone = new DateTimeZone("Asia/Colombo");
    $create_date->setTimezone($time_zone);
    $date = $create_date->format("Y-m-d H:i:s");

    // Book status
    $status = 1;

    // Book table
    Database::insert(" INSERT INTO `books` ( `title`,`price`,`qty`,`description`,`datetime_added`,`delivery_fee_colombo`,`delivery_fee_other`,`active_active_id`,`author_name_id`,`book_category_id`,`sku`,`isbn`,`pages`, `language_id` , `published_date` ) 
        VALUES (
            '" . $book_title . "',
            '" . $price . "',
            '" . $qty . "',
            '" . $description . "',
            '" . $date . "',
            '" . $delivery_colombo . "',
            '" . $delivery_other . "',
            '" . $status . "',
            '" . $current_author . "',
            '" . $current_category . "',
            '" . $sku . "',
            '" . $isbn . "',
            '" . $pages . "',
            '" . $language . "',
            '" . $published_date . "'
            
        ) ");

    $book_id_un = Database::$connection->insert_id;

    if (sizeof($_FILES) == 1) {
        $image = $_FILES["image"];
        $extention = $image["type"];
        $allowed_image_extention = array("image/jpeg", "image/png", "image/svg+xml", "image/webp");

        if (in_array($extention, $allowed_image_extention)) {
            $new_extention;
            if ($extention == "image/jpeg") {
                $new_extention = ".jpeg";
            } else if ($extention == "image/png") {
                $new_extention = ".png";
            } else if ($extention == "image/svg") {
                $new_extention = ".svg";
            } else if ($extention == "image/webp") {
                $new_extention = ".webp";
            }
            $file_path = "book_image/" . "_" . uniqid() . $new_extention;
            move_uploaded_file($image["tmp_name"], $file_path);
            Database::insert("INSERT INTO `book_img` ( `img_path` , `book_id` ) VALUES ('" . $file_path . "' , '" . $book_id_un . "') ");
        } else if (sizeof($_FILES) == 0) {
            echo "Select book image";
        }
    }

    echo "success";
}
