<?php session_start();
include "./connection.php"; ?>
<?php if (!isset($_SESSION['user'])) header('location:index.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    <link rel="stylesheet" href="./css/bootstrap.css" />
    <link rel="stylesheet" href="./css/index.css" />
    <title>ReadHaven</title>
</head>

<body>

    <?php if (isset($_SESSION['user'])) {
        $user = $_SESSION['user']['email'];
        $results = Database::search("SELECT * FROM `watchlist` INNER JOIN `books` ON `watchlist`.`books_book_id` = `books`.`book_id` INNER JOIN `author_name` ON `books`.`author_name_id` = `author_name`.`id` INNER JOIN `book_category` ON `books`.`book_category_id` = `book_category`.`category_id` AND `active_active_id` = '1' WHERE `users_email` = '$user'  ORDER BY `added_date` DESC  ");
        $result_count = $results->num_rows; ?>

        <?php include "./components/header.php"; ?>

        <div class="container" id="result">

            <div class="row py-4">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-secondary" href="./index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Wishlist</a></li>
                        </ol>
                    </nav>
                    <h4>My wishlist on ReadHaven.lk </h4>
                    <div class="row">
                        <div class="col-12 d-none" id="msgBox">
                            <div class="alert alert-success border-0 rounded-0" role="alert" id="response_msg" style="font-size: 13px; margin-bottom: 0;margin-top: 10px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($result_count == 0) { ?>
                <div class="row">
                    <div class="col-12 d-flex flex-column gap-3 justify-content-between align-items-center">
                        <p>Your wishlist is empty — start adding favorites....</p>
                        <a href="index.php" class="rounded-1 px-4 py-2 text-black" style="background-color: #f6f6f6;">Explore</a>
                    </div>
                </div>
            <?php } ?>

            <?php if ($result_count > 0) { ?>
                <div class="row d-none d-lg-block">
                    <div class="col-12">

                        <table class="g-3" style="width: 100%;">

                            <tr class="horizontal-line-table">
                                <th style="width: 430px;">
                                    <h6>BOOK</h6>
                                </th>
                                <th style="width: 130px;">
                                    <h6>UNIT PRICE</h6>
                                </th>
                                <th style="width: 150px;">
                                    <h6>STOCK STATUS</h6>
                                </th>
                                <th style="width: 100px;">
                                    <h6>ADDED DATE</h6>
                                </th>
                            </tr>

                            <?php for ($x = 0; $x < $results->num_rows; $x++) {
                                $books_data = $results->fetch_assoc(); ?>
                                <tr class="horizontal-line">
                                    <td class="d-flex align-items-center my-2 px-3 gap-2">

                                        <div class="d-flex align-items-center gap-3" onclick="window.location='./book.php?id=<?= $books_data['book_id'] ?>'">

                                            <?php

                                            $book_image = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $books_data['book_id'] . "'");

                                            if ($book_image->num_rows > 0) {
                                                $image_data = $book_image->fetch_assoc();
                                                $image_url = (isset($image_data['img_path'])) ? $image_data['img_path'] : './img/not-found.png';
                                            }

                                            ?>

                                            <img src="<?= $image_url; ?>" width="50px" alt="book-image" class="rounded-1 normal-border-gray">

                                            <div class="d-flex align-items-center wl_data">
                                                <h6 class="text-black"><?= $books_data['title']; ?></h6>
                                            </div>

                                        </div>

                                    </td>

                                    <td>
                                        <p>Rs.<?= $books_data['price'] ?>.00</p>
                                    </td>

                                    <td style="width: 150px;">
                                        <?= ($books_data['qty'] > 0) ? "<p class='text-success p-2 rounded-1' style='background:#f0fdf4;display:inline-block;font-size:14px'>in stock</p>" : "<p class='text-danger p-2 rounded-1' style='background:#fef2f2;display:inline-block;font-size:14px'>out of stock</p>"; ?></td>

                                    <td><?= $books_data['added_date']; ?></td>

                                    <?php $book_available = ($books_data['qty'] > 0) ? true : false;  ?>
                                    <td style="width: 80px; padding-left: 20px;cursor: pointer;" class="txt-primary bg-transparent border-0 text-center" <?= ($book_available) ? "id='response-text{$books_data['book_id']}'" : null; ?> <?= ($book_available) ? "onclick='book_add_cart({$books_data['book_id']} , {$books_data['qty']})'" : null; ?>>
                                        <?= ($book_available) ? "<i class='bi bi-bag'></i>" : null; ?>
                                    </td>

                                    <td style="width: 150px;" class="text-center">
                                        <i onclick="book_remove_from_wishlist(<?= $books_data['w_id']; ?>);" style="cursor: pointer;" class="bi bi-trash text-danger fs-5"></i>
                                    </td>

                                </tr>

                            <?php } ?>

                        </table>
                    </div>
                </div>
            <?php } ?>


            <?php if ($result_count > 0) { ?>

                <div class="row d-none d-md-block d-lg-none">

                    <div class="col-12">

                        <table style="width: 100%;">

                            <tr class="horizontal-line-table text-uppercase">
                                <th style="width: 320px;">
                                    <h6>BOOK</h6>
                                </th>
                                <th style="width: 130px;">
                                    <h6>PRICE</h6>
                                </th>
                                <th style="width: 100px;">
                                    <h6>STOCK</h6>
                                </th>
                                <th style="width: 120px;">
                                    <h6>ADDED DATE</h6>
                                </th>
                            </tr>

                            <?php $results_second = Database::search("SELECT * FROM `watchlist` INNER JOIN `books` ON `watchlist`.`books_book_id` = `books`.`book_id` INNER JOIN `author_name` ON `books`.`author_name_id` = `author_name`.`id` INNER JOIN `book_category` ON `books`.`book_category_id` = `book_category`.`category_id` AND `active_active_id` = '1' WHERE `users_email` = '$user'  ORDER BY `added_date` DESC ");
                            $results_second_num = $results_second->num_rows;

                            for ($x = 0; $x < $results_second_num; $x++) {
                                $second_book_data = $results_second->fetch_assoc(); ?>

                                <tr class="horizontal-line">

                                    <td>
                                        <div class="d-flex align-items-center my-2 px-2 gap-2">

                                            <?php

                                            $book_image = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $second_book_data['book_id'] . "'");
                                            $image_url = ($book_image->num_rows > 0) ? $book_image->fetch_assoc()['img_path'] : null;

                                            ?>

                                            <div class="d-flex align-items-center gap-3">
                                                <i onclick="book_remove_from_wishlist(<?= $second_book_data['w_id']; ?>);" style="cursor: pointer;" class="bi bi-trash text-danger fs-5"></i>
                                                <img onclick="window.location='book.php?id=<?= $second_book_data['book_id'] ?>'" src="<?= $image_url; ?>" width="50px" alt="book-image" class="rounded-1 normal-border-gray">
                                            </div>

                                            <h6 class="text-black" style="font-size: 14px;"><?= $second_book_data['title'] ?></h6>
                                        </div>
                                    </td>

                                    <td style="font-size: 13px;" class="fw-medium">Rs.<?= $second_book_data['price']; ?>.00</td>

                                    <td style="width: 120px;">
                                        <?= ($second_book_data['qty'] > 0) ? "<p class='text-success p-2 rounded-1' style='background:#f0fdf4;display:inline-block;font-size:14px'>in stock</p>" : "<p class='text-danger p-2 rounded-1' style='background:#fef2f2;display:inline-block;font-size:14px'>out of stock</p>"; ?></td>

                                    <td style="font-size: 13px;"><?= $second_book_data['added_date']; ?></td>

                                    <?php $book_available = ($second_book_data['qty'] > 0) ? true : false;  ?>

                                    <td style="width: 80px; padding-left: 20px;cursor: pointer;" class="txt-primary bg-transparent border-0 text-center" <?= ($book_available) ? "id='response-text{$second_book_data['book_id']}'" : null; ?> <?= ($book_available) ? "onclick='book_add_cart({$second_book_data['book_id']} , {$second_book_data['qty']})'" : null; ?>>
                                        <?= ($book_available) ? "<i class='bi bi-bag'></i>" : null; ?>
                                    </td>

                                    </td>

                                </tr>

                            <?php } ?>

                        </table>
                    </div>
                </div>

            <?php } ?>

            <div class="row d-md-none px-2">

                <?php $results_third = Database::search("SELECT * FROM `watchlist` INNER JOIN `books` ON `watchlist`.`books_book_id` = `books`.`book_id` INNER JOIN `author_name` ON `books`.`author_name_id` = `author_name`.`id` INNER JOIN `book_category` ON `books`.`book_category_id` = `book_category`.`category_id` AND `active_active_id` = '1' AND `users_email` = '$user' ORDER BY `added_date` DESC ");
                $results_third_num = $results_third->num_rows;

                for ($x = 0; $x < $results_third_num; $x++) {
                    $results_third_data = $results_third->fetch_assoc();
                ?>
                    <div class="col-12 my-1">

                        <?php $book_image = Database::search("SELECT * FROM book_img WHERE book_id = '" . $results_third_data['book_id'] . "'");

                        if ($book_image->num_rows > 0) {
                            $image_data = $book_image->fetch_assoc();
                            $image_url = (isset($image_data['img_path'])) ? $image_data['img_path'] : './img/not-found.png';
                        } ?>


                        <div class="d-flex align-items-center">

                            <img src="<?= $image_url ?>" width="120px" height="170px" class="rounded-2" alt="book-image">

                            <div class="row m-2 p-1 rounded-3 w-100 normal-border-gray">

                                <div>
                                    <div class="row pt-2" onclick="location.href='book.php?id=<?= $results_third_data['book_id'] ?>'">
                                        <div class="col-12">
                                            <h5><?= $results_third_data['title'] ?></h5>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-between mb_wt">
                                            <p style="font-size: 13px;">Added on:</p>
                                            <p style="font-size: 13px;"><?= $results_third_data['added_date'] ?></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-between mb_wt">
                                            <p style="font-size: 13px;">Price:</p>
                                            <p style="font-size: 13px;">Rs.<?= $results_third_data['price'] ?>.00</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-between mb_wt">
                                            <p style="font-size: 13px;">Stock:</p>
                                            <?php $stock_status = ($results_third_data['qty'] > 0) ? "in stock" : "out of stock"; ?>
                                            <p style="font-size: 13px;"><?= $stock_status ?></p>
                                        </div>
                                    </div>

                                    <div class="row my-2">

                                        <div class="col d-flex justify-content-end gap-2">

                                            <?php $book_available = ($results_third_data['qty'] > 0) ? true : false;  ?>

                                            <?= ($book_available) ? "<button id='response-text{$results_third_data['book_id']}' onclick='book_add_cart({$results_third_data['book_id']}, {$results_third_data['qty']});' class='outline-none border-0 p-2 rounded-2' style='background-color:#f6f6f6;font-size:13px'><i class='bi bi-bag px-1'></i> Add to cart</button>" : null; ?>

                                            <button onclick="book_remove_from_wishlist(<?= $results_third_data['w_id']; ?>);" class="rounded-2 border-0 text-danger p-2" style="font-size:13px;background-color: #fef2f2;"><i style="cursor: pointer;" class="bi bi-trash text-danger px-1 fs-6"></i>
                                                Remove
                                            </button>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                <?php } ?>

            </div>

        </div>

    <?php } ?>

    <?php include "./components/toast.php"; ?>
    <?php include "./components/footer.php"; ?>

    <script src="./js/script.js"></script>
    <script src="./js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>