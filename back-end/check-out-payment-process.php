<?php

session_start();
require_once '../connection.php';

if (!empty($_SESSION['user'])) {
     $email = $_SESSION['user']['email'];
     $loged_user_data = Database::search("SELECT * FROM users WHERE email = '$email'");
     $user_data = $loged_user_data->fetch_assoc();

     $address_result = Database::search("SELECT * FROM users_has_address INNER JOIN city ON users_has_address.city_id = city.city_id INNER JOIN district ON city.district_district_id = district.district_id WHERE users_has_address.users_email = '$email' ");

     $address_data = $address_result->fetch_assoc();

     $address = $address_data['line1'] . "," . $address_data['line2'];

     $items = '';
     $product_total = 0;
     $delivery_total = 0;
     $order_id = uniqid();
     $cart_result = Database::search("SELECT * FROM cart INNER JOIN books ON cart.cart_book_id = books.book_id WHERE cart_users_email = '$email' ");

     for ($i = 0; $i < $cart_result->num_rows; $i++) {
          $cart_data = $cart_result->fetch_assoc();
          $unit_price = $cart_data['price'];
          $cart_qty = $cart_data['cart_qty'];

          $product_total += $unit_price * $cart_qty;

          if ($address_data['district_id'] == 22) {
               $delivery_total += $cart_data['delivery_fee_colombo'];
          } else {
               $delivery_total += $cart_data['delivery_fee_other'];
          }

          $items .= $cart_data['title'] . ", ";
     }

     $merchant_id = '<YOUR_MERCHANT_ID>';
     $merchant_secret = '<YOUR_MERCHANT_SECRET>';
     $currency = 'LKR';

     $amount = $product_total + $delivery_total;
     $hash = strtoupper(
          md5(
               $merchant_id .
                   $order_id.
                    number_format($amount, 2, '.', '') .
                    $currency .
                    strtoupper(md5($merchant_secret))
          )
     );

     $array;

     $array['email'] = $email;
     $array['fname'] = $user_data['first_name'];
     $array['lname'] = $user_data['last_name'];
     $array['items'] = $items;
     $array['mobile'] = $user_data['mobile'];
     $array['address'] = $address;
     $array['city'] = $address_data['city_name'];
     $array['order_id'] = $order_id;
     $array['amount'] = $amount;
     $array['merchant_id'] = $merchant_id;
     $array['merchant_secret'] = $merchant_secret;
     $array['currency'] = $currency;
     $array['hash'] = $hash;

     echo json_encode($array);
} else {
     echo 'Somthing went wrong!';
}
