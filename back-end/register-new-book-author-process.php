<?php
require "../connection.php";

$author_name = $_POST["author_name"];
$author_about = $_POST["author_about"];

if (empty($author_name)) {
    echo "Please enter your author name";
} else if (!empty($author_name)) {
    $author_search = Database::search("SELECT * FROM `author_name` WHERE `author_name` LIKE '%" . $author_name . "%' ");
    if ($author_search->num_rows > 0) {
        echo "This author already exits.. please select this author";
    } else {
        if (!empty($author_about)) {
            Database::insert("INSERT INTO `author_name` (`author_name`,`author_about`) VALUES ('" . $author_name . "','" . $author_about . "')");
        } else {
            Database::insert("INSERT INTO `author_name` (`author_name`) VALUES ('" . $author_name . "')");
        }

        echo "success";
    }
}
