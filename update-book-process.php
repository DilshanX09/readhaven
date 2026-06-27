<?php
include "./connection.php";

$title = $_POST['title'];
$pageCount = $_POST['pageCount'];
$publishedDate = $_POST['publishedDate'];
$authorValue = $_POST['authorValue'];
$categoryValue = $_POST['categoryValue'];
$languageValue = $_POST['languageValue'];
$sku = $_POST['sku'];
$isbn = $_POST['isbn'];
$description = $_POST['description'];
$qty = $_POST['qty'];
$bookPrice = $_POST['bookPrice'];
$deliveryColombo = $_POST['deliveryColombo'];
$deliveryOther = $_POST['deliveryOther'];
$book_id = $_POST['book_id'];

if (empty($title)) {
    echo "Please enter book title";
} else if (strlen($title) > 100) {
    echo "Book title must contain 100 characters";
} else if (empty($pageCount)) {
    echo "Please enter book pages count";
} else if (strlen($pageCount) > 50) {
    echo "Page count must contain 50 characters";
} else if (empty($publishedDate)) {
    echo "Please select book published date";
} else if ($authorValue == 0) {
    echo "Please select author";
} else if ($categoryValue == 0) {
    echo "Please slect book category";
} else if (empty($sku)) {
    echo "Please enter stock keeping unit number";
} else if (strlen($sku) > 15) {
    echo "Stock keeping unit number must contain 15 characters";
} else if (empty($isbn)) {
    echo "Please enter international standard book Number";
} else if (strlen($isbn) > 13 || strlen($isbn) < 13) {
    echo "ISBN number must contain 13 characters";
} else if (empty($description)) {
    echo "Please enter book description";
} else if (empty($qty)) {
    echo "Please enter book quantity";
} else if (empty($bookPrice)) {
    echo "Please enter book price";
} else {

    $createDate = new DateTime();
    $timeZone = new DateTimeZone("Asia/Colombo");
    $createDate->setTimezone($timeZone);
    $currentDate = $createDate->format("Y-m-d H:i:s");

    Database::search("UPDATE `books` SET 
            `title` = '" . $title . "',
            `price` = '" . $bookPrice . "',
            `qty` = '" . $qty . "',
            `description` = '" . strtr($description, array("'" => "\'")) . "',
            `datetime_added` = '" . $currentDate . "',
            `delivery_fee_colombo` = '" . $deliveryColombo . "',
            `delivery_fee_other` = '" . $deliveryOther . "',
            `author_name_id` = '" . $authorValue . "',
            `book_category_id` = '" . $categoryValue . "',
            `sku` = '" . $sku . "',
            `isbn` = '" . $isbn . "',
            `pages` = '" . $pageCount . "',
            `language_id` = '" . $languageValue . "',
            `published_date` = '" . $publishedDate . "'

            WHERE `book_id` = '" . $book_id . "'
        ");


    if ($_FILES == 1) {
        echo 's';
    }

    if (sizeof($_FILES) == 1) {
        $image = $_FILES["imge"];
        $extention = $image["type"];
        $allowed_image_extention = array("image/jpeg", "image/png", "image/svg+xml", "image/webp");

        $img_rs = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $book_id . "' ");
        $img_num = $img_rs->num_rows;

        $img_data = $img_rs->fetch_assoc();

        if ($img_num == 0) {
            echo "No image found";
            exit();
        }

        try {
            unlink($img_data["img_path"]);
        } catch (Exception $e) {
            echo "Image not found";
        }
        
        Database::insert("DELETE FROM `book_img` WHERE `book_id` = '" . $book_id . "' "); // Delete Query SQL

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

            Database::insert("INSERT INTO `book_img` ( `img_path` , `book_id` ) VALUES ('" . $file_path . "' , '" . $book_id . "') ");
        } else if (sizeof($_FILES) == 0) {

            echo "Select book image";
        } else {
            echo "Invalid book image type";
        }
    }

    echo 'success';
}
