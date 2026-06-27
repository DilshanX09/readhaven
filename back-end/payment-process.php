<?php

session_start();
include "../connection.php";

if (isset($_SESSION['user'])) {

    $book_id = $_GET['id'];
    $book_qty = $_GET['qty'];
    $user = $_SESSION['user']['email'];

    $array;

    $order_id = uniqid();

    $book_rslt = Database::search("SELECT * FROM `books` WHERE `book_id` = '" . $book_id . "'");
    $book_data = $book_rslt->fetch_assoc();

    $address_rslt = Database::search("SELECT * FROM `users_has_address` INNER JOIN `city` ON `users_has_address`.`city_id` = `city`.`city_id` INNER JOIN `district` ON `city`.`district_district_id` = `district`.`district_id` WHERE `users_email` = '" . $user . "' ");

    if ($address_rslt->num_rows == 1) {

        $address_data = $address_rslt->fetch_assoc();

        if (!empty($address_data['line1']) || !empty($address_data['line2'])) {

            $address = $address_data['line1'] . ", " . $address_data['line2'];
            $delivery = 0;

            if ($address_data['district_id'] == 22) {
                $delivery = $book_data["delivery_fee_colombo"];
            } else {
                $delivery = $book_data["delivery_fee_other"];
            }

            $book_name = $book_data['title'];
            $amount = ((int)$book_data['price'] * (int)$book_qty) + (int)$delivery;

            $fname = $_SESSION['user']['first_name'];
            $lname = $_SESSION['user']['last_name'];
            $mobile = $_SESSION['user']['mobile'];
            $user_address = $address;
            $city = $address_data['city_name'];

            $merchant_id = "<YOUR_MERCHANT_ID>";
            $merchant_secret = "<YOUR_MERCHANT_SECRET>";
            $currency = "LKR";

            $hash = strtoupper(
                md5(
                    $merchant_id .
                        $order_id .
                        number_format($amount, 2, '.', '') .
                        $currency .
                        strtoupper(md5($merchant_secret))
                )
            );

            $array["id"] = $order_id;
            $array["name"] = $book_name;

            $array["fname"] = $fname;
            $array["lname"] = $lname;
            $array["mobile"] = $mobile;
            $array["address"] = $user_address;
            $array["city"] = $city;
            $array["email"] = $user;
            $array["mid"] = $merchant_id;
            $array["currency"] = $currency;
            $array["hash"] = $hash;

            $array["subtotal"] = (int)$book_data['price'] * (int)$book_qty;
            $array["shipping"] = (int)$delivery;
            $array["amount"] = $amount;

            echo json_encode($array);
        } else {
            echo 2;
        }
    }
} else {

    echo 1;
}
