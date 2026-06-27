<?php
session_start();
include 'connection.php';
if (!isset($_SESSION['user'])) header('location:index.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="./css/bootstrap.css" />
    <link rel="stylesheet" href="./css/index.css" />
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    <title>Readhaven | Cart</title>
</head>

<body>

    <?php

    include 'components/header.php';

    if (isset($_SESSION['user'])) {

        $email = $_SESSION['user']['email'];
        $cart_srch = Database::search("SELECT * FROM `cart` INNER JOIN `books` ON `cart`.`cart_book_id` = `books`.`book_id` INNER JOIN `book_category` ON `books`.`book_category_id` =  `book_category`.`category_id` INNER JOIN  `author_name` ON `books`.`author_name_id` = `author_name`.`id` WHERE `cart_users_email` = '" . $email . "'");
        $cart_num = $cart_srch->num_rows;

        $total = 0;
        $subtotal = 0;
        $shipping = 0;

    ?>
        <div class="container">

            <div class="row py-4">
                <div class="col-12">

                    <nav class="pt-4 pb-3" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Shopping Cart</li>
                        </ol>
                    </nav>

                    <h4>My cart on ReadHaven.lk </h4>
                    <div class="row">
                        <div class="col-12 d-none" id="msgBox">
                            <div class="alert alert-success border-0 rounded-0" role="alert" id="response_msg" style="font-size: 13px; margin-bottom: 0;margin-top: 10px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($cart_num == 0) { ?>

                <div class="row">
                    <div class="col-12 d-flex flex-column gap-3 justify-content-between align-items-center">
                        <p>Your cart is waiting for something to be added...</p>
                        <a href="index.php" class="rounded-1 px-4 py-2 text-black" style="background-color: #f6f6f6;">Shop now</a>
                    </div>
                </div>

            <?php } else { ?>

                <div class="row">
                    <div class="col-lg-8 d-none d-md-block">
                        <table class="g-3 mb-4 ">

                            <tr class="horizontal-line-table" style="width: 100%;">
                                <th style="width: 480px;">
                                    <h6>BOOK</h6>
                                </th>
                                <th style="width: 150px;" class="text-start">
                                    <h6>PRICE</h6>
                                </th>
                                <th style="width: 200px;" class="text-center">
                                    <h6>QUANTITY</h6>
                                </th>
                                <th style="width: 200px;" class="text-end">
                                    <h6>SUBTOTAL</h6>
                                </th>
                            </tr>

                            <?php

                            for ($x = 0; $x < $cart_num; $x++) {

                                $cart_data = $cart_srch->fetch_assoc();

                                $total = $total + ($cart_data['price'] * $cart_data['cart_qty']);
                                $address_srch = Database::search("SELECT `district_id` FROM `users_has_address` INNER JOIN `city` ON `users_has_address`.`city_id` = `city`.`city_id` INNER JOIN `district` ON `city`.`district_district_id` = `district`.`district_id`  WHERE `users_email` = '" . $email . "'");

                                $address_data = $address_srch->fetch_assoc();

                                $ship = 0;

                                if ($address_data['district_id'] == 22) {
                                    $ship = $cart_data['delivery_fee_colombo'];
                                    $shipping = $shipping + $ship;
                                } else {
                                    $ship = $cart_data['delivery_fee_other'];
                                    $shipping = $shipping + $ship;
                                }

                            ?>
                                <tr class="horizontal-line">

                                    <td class="d-flex align-items-center my-2 px-3 gap-2">

                                        <span style="cursor: pointer;" onclick="book_remove_from_cart(<?= $cart_data['cart_book_id'] ?>);"><i class="bi bi-x-circle fs-5"></i></span>

                                        <?php

                                        $cart_img_srch = Database::search("SELECT * FROM book_img WHERE book_id = '" . $cart_data['book_id'] . "'");
                                        $img_data = $cart_img_srch->fetch_assoc();
                                        $image_url = (isset($img_data['img_path'])) ? $img_data['img_path'] : './img/not-found.png';

                                        ?>

                                        <img onclick="window.location.href='./book.php?id=<?= $cart_data['cart_book_id']; ?>'" style="cursor: pointer;" src="<?= $image_url; ?>" width="90px" alt="book-image" />

                                        <div onclick="window.location.href='./book.php?id=<?= $cart_data['cart_book_id']; ?>'" style="cursor: pointer;" class="d-flex ps-1 flex-column wl_data">
                                            <h6 class="text-black"><?= $cart_data['title']; ?></h6>
                                            <p style="font-size: 13px;" class="text-dark">Author , <?= $cart_data['author_name']; ?></p>
                                            <p style="font-size: 13px;" class="text-dark">Category <?= $cart_data['category_name']; ?></p>
                                            <p style="font-size: 13px;" class="text-dark">Quantity : <?= $cart_data['qty']; ?> items left </p>
                                        </div>

                                    </td>

                                    <td class="fw-medium">Rs.<?= $cart_data['price']; ?>.00</td>

                                    <td class="d-flex justify-content-center">
                                        <div class="row pt-2">
                                            <div class="col-12 crt">
                                                <div>
                                                    <div class="d-flex justify-content-start align-items-center">
                                                        <button onclick="quantity_decrease(<?= $cart_data['book_id'] ?>);"><i class="bi bi-dash"></i></button>
                                                        <input class="text-center" type="text" value="<?= $cart_data['cart_qty'] ?>">
                                                        <button onclick="quantity_increase(<?= $cart_data['qty'] ?> , <?= $cart_data['book_id'] ?> );"><i class="bi bi-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-medium text-end">
                                        Rs.<?= $cart_data['price'] * $cart_data['cart_qty']; ?>.00
                                    </td>
                                </tr>

                            <?php } ?>

                        </table>
                        <div class="row mb-4">
                            <div class="col-12">
                                <a href="index.php" class="buy rounded-5 px-3">CONTINUE SHOPPING</a>
                            </div>
                        </div>
                    </div>

                    <?php

                    $cart_srch_mbl = Database::search("SELECT * FROM `cart` INNER JOIN `books` ON `cart`.`cart_book_id` = `books`.`book_id` INNER JOIN `book_category` ON `books`.`book_category_id` =  `book_category`.`category_id` INNER JOIN  `author_name` ON `books`.`author_name_id` = `author_name`.`id` WHERE `cart_users_email` = '" . $email . "'");

                    for ($y = 0; $y < $cart_srch_mbl->num_rows; $y++) {

                        $cart_data_mbl = $cart_srch_mbl->fetch_assoc();

                    ?>
                        <!-- Mobile size screen design -->
                        <div class="container d-md-none">
                            <div class="col-12">

                                <div class="row">
                                    <div class="d-flex">
                                        <div class="col-4 col-md-3">

                                            <?php

                                            $cart_img_srch_mbl = Database::search("SELECT * FROM book_img WHERE book_id = '" . $cart_data_mbl['book_id'] . "'");
                                            $img_data_mbl = $cart_img_srch_mbl->fetch_assoc();
                                            $image_url = (isset($img_data_mbl['img_path'])) ? $img_data_mbl['img_path'] : './img/not-found.png';

                                            ?>

                                            <img onclick="window.location.href='./book.php?id=<?= $cart_data_mbl['cart_book_id']; ?>'" style="cursor: pointer;" src="<?= $image_url; ?>" width="100px" alt="book-image" />

                                        </div>

                                        <div class="col-8 ccl-md-9">

                                            <div onclick="window.location.href='./book.php?id=<?= $cart_data_mbl['book_id'] ?>'">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="text-black"><?= $cart_data_mbl['title'] ?></h5>
                                                    </div>
                                                </div>
                                                <div class="row pb-1">
                                                    <div class="col-12">
                                                        <div class="d-flex flex-column">
                                                            <span style="font-size: 12px;">Author , <?= $cart_data_mbl['author_name'] ?></span>
                                                            <span style="font-size: 12px;">Category , <?= $cart_data_mbl['category_name']; ?></span>
                                                            <span style="font-size: 12px;">Quantity , <?= $cart_data_mbl['qty']; ?> Items left</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-between align-items-center">
                                                    <div class="crt">
                                                        <div>
                                                            <div class="d-flex justify-content-start align-items-center  ">
                                                                <button onclick="quantity_decrease(<?= $cart_data['book_id'] ?>);"><i class="bi bi-dash"></i></button>
                                                                <input class="text-center" type="text" value="<?= $cart_data['cart_qty'] ?>">
                                                                <button onclick="quantity_increase(<?= $cart_data['qty'] ?> , <?= $cart_data['book_id'] ?> );"><i class="bi bi-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span style="cursor: pointer;" onclick="book_remove_from_cart(<?= $cart_data_mbl['cart_book_id'] ?>);">
                                                            <i class="bi bi-trash3 text-danger"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 pt-1 d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <span style="font-size: 12px;">Unit Price</span>
                                            <span class="text-dark">Rs.<?= $cart_data_mbl['price'] ?>.00</span>
                                        </div>
                                        <div class="d-flex flex-column text-end">
                                            <span style="font-size: 12px;">Subtotal</span>
                                            <span class="text-dark">Rs.<?= $cart_data_mbl['price'] * $cart_data_mbl['cart_qty']; ?>.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="horizontal-line w-100 pt-1 my-2"></div>
                            </div>
                        </div>

                    <?php } ?>

                    <div class="col-lg-4 px-1 mt-2">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <span class="text-uppercase fs-5 fw-medium">Cart totals</span>
                                </div>
                            </div>
                            <div class="row pt-3 pb-1">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <span>Items(<?= $cart_num; ?>)</span>
                                    <span>Rs.<?= $total; ?>.00</span>
                                </div>
                            </div>
                            <div class="horizontal-line w-100"></div>
                            <div class="row pt-2 pb-1">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <span>Shipping</span>
                                    <span>Rs.<?= $shipping; ?>.00</span>
                                </div>
                            </div>
                            <div class="horizontal-line w-100"></div>
                            <div class="row pt-2 pb-1">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <span class="text-black fs-6 fw-medium">Total</span>
                                    <span class="text-black fs-6 fw-medium">Rs.<?= $total + $shipping ?>.00</span>
                                </div>
                            </div>
                            <div class="horizontal-line w-100"></div>
                            <div class="row pt-4">
                                <div class="col-12 d-grid">
                                    <button class="cart rounded-0" onclick="checkout(<?= $shipping ?> , <?= $total + $shipping ?> , <?= $total ?> )">
                                        PROCEED TO CHECKOUT
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>

        <?php include 'components/toast.php'; ?>

        <?php include 'components/footer.php'; ?>

    <?php } ?>

    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <script src="./js/script.js"></script>
    <script src="./js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>