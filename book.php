<?php
session_start();
include "./connection.php";

if (isset($_GET['id'])) {

    $book_id = $_GET['id'];

    $book_search = Database::search("SELECT * FROM `books` INNER JOIN `language` ON `books`.`language_id` = `language`.`l_id`  INNER JOIN `author_name` ON `books`.`author_name_id` = `author_name`.`id` INNER JOIN `book_category`  ON `books`.`book_category_id` = `book_category`.`category_id`  WHERE `book_id` = '" . $book_id . "'");

    if ($book_search->num_rows == 1) {
        $books_data = $book_search->fetch_assoc(); ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
            <link rel="stylesheet" href="./css/bootstrap.css" />
            <link rel="stylesheet" href="./css/index.css" />
            <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
            <title><?= $books_data['title']; ?></title>
        </head>

        <body>

            <?php include "./components/header.php";  ?>

            <?php include './models/sign-in-model.php'; ?>

            <div class="container" id="result">
                <nav class="pt-4 pb-3" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page"><?= $books_data['category_name'] ?></li>
                        <li class="breadcrumb-item" aria-current="page"><?= $books_data['title'] ?></li>
                    </ol>
                </nav>

                <div class="row mb-5">

                    <div class=" col-12 d-flex justify-content-center align-items-start col-lg-4 col-xxl-3 col-xl-4 px-lg-2 pb-3">

                        <?php

                        $book_img_search = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $book_id . "'");
                        if ($book_img_search->num_rows == 1) $img_data = $book_img_search->fetch_assoc();

                        ?>

                        <img src="<?= $img_data['img_path']; ?>" width="280px" class="rounded-2" alt="BookImage">

                    </div>

                    <div class="col-12 col-lg-8 col-xxl-9 col-xl-8 d-flex flex-column gap-1">

                        <div class="row">
                            <div class="col-12">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <h2><?= $books_data['title']; ?></h2>
                                    </div>

                                    <div class="text-end">

                                        <?php

                                        if (isset($_SESSION['user'])) {

                                            $single_add_watchlist = Database::search("SELECT * FROM `watchlist` WHERE `books_book_id` = '" . $books_data['book_id'] . "'");

                                            if ($single_add_watchlist->num_rows ==  1) {

                                        ?>
                                                <span role="button" class="me-5" onclick="book_add_to_wishlist(<?= $books_data['book_id'] ?>);"> <i class="bi bi-bookmark-plus text-danger fs-5" id="heart<?= $books_data['book_id'] ?>"></i></span>

                                            <?php

                                            } else {

                                            ?>
                                                <span role="button" class="me-5" onclick="book_add_to_wishlist(<?= $books_data['book_id'] ?>);"> <i class="bi bi-bookmark-plus fs-5" id="heart<?= $books_data['book_id'] ?>"></i></span>

                                        <?php

                                            }
                                        }

                                        ?>
                                    </div>

                                </div>

                                <div class="d-flex flex-column gap-2">

                                    <span class="fs-6"><span style="font-size: 13px;" class="text-dark">Author</span>, <?= $books_data['author_name']; ?></span>
                                    <span class="fs-5"><span style="font-size: 13px;color: black;">Rs. </span><?= $books_data['price']; ?> <span style="font-size: 13px;color: black;"> .00</span></span>
                                    <textarea class="des" readonly style="font-size: 14px;"><?= $books_data['description']; ?></textarea>

                                    <span class="txt-primary fw-medium">
                                        <?= ($books_data['qty'] > 0) ? "Only {$books_data['qty']} left in stock " : "This Book is Out of Stock"; ?>
                                    </span>

                                </div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-12 pb-1 d-flex align-items-center gap-5 counter">
                                <div>
                                    <div class="d-flex gap-1 justify-content-start align-items-center ">
                                        <button onclick="book_quantity_decrease();"><i class="bi bi-dash"></i></button>
                                        <input type="text" id="qty_cnt" onkeyup="check_quantity(<?= $books_data['qty']; ?>);" value="1">
                                        <button onclick="book_quantity_increase(<?= $books_data['qty']; ?>);"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                                <div class="text-end">

                                    <?php

                                    $user_session = (isset($_SESSION['user'])) ? true : false;
                                    $status = ($books_data['qty'] > 0) ? true : false;

                                    ?>

                                    <button style="height: 50px;width: 150px; border-radius: 100px;font-size: 15px;" type="submit" <?= ($status) ? "id='payhere-payment'" : null ?>
                                        <?= ($status && $user_session) ? "onclick='payment($book_id);'" : (!$user_session ? "onclick='visible_auth_model();'" : null) ?> class=" px-3 <?= ($status) ? "bg-orange text-white border-0" : "bg-dark text-white border-0" ?> ">

                                        <?= ($status) ? "Buy now" : "Out Of Stock" ?>
                                    </button>

                                    <button <?= $status ? "id='response-text{$books_data["book_id"]}'" : null ?> class="buy mb-1 text-white  <?= (!$status) ? "d-none" : null; ?> px-4" style="height: 50px;width: 150px; border-radius: 100px;font-size: 15px;" <?= ($status && $user_session) ? "onclick='book_add_cart({$books_data['book_id']} , {$books_data['qty']});'" : null; ?>>

                                        <?= ($status) ? "Add to cart" : null ?>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <span style="font-size: 13px;color: red;" id="cnt_err"></span>

                        <div class="row line mt-3">
                            <div class="col-12 ">
                                <span style="font-size: 13px;">SKU : <?= $books_data['sku']; ?></span>
                            </div>
                        </div>

                        <div class="row line">
                            <div class="col-12">
                                <span style="font-size: 13px;">Category : <?= $books_data['category_name']; ?></span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row tabs d-flex justify-content-center mb-3 pt-5">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-uppercase active px-0 mx-0" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <?php $review = Database::search("SELECT * FROM `feedback` WHERE `feed_book_id` = '$book_id'"); ?>
                                <button class="nav-link text-uppercase px-3 mx-2" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Reviews (<?= $review->num_rows ?>)</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-12  d-flex flex-column">
                                        <span class="fs-4 pb-3 fw-medium">About the Author</span>
                                        <span style="font-size: 16px;">
                                            <span class="fw-medium text-black">
                                                <?= $books_data['author_name']; ?>
                                            </span>
                                            <?= $books_data['author_about']; ?>
                                        </span>
                                    </div>
                                    <div class="col-12  mt-5  py-3" style="border-bottom: none;">
                                        <h5 class="fw-normal">Book Details</h5>
                                        <ul class="d-flex flex-column gap-2">

                                            <li><span class="fw-medium">ISBN-13 :</span> <?= $books_data['isbn']; ?></li>

                                            <?php if (!empty($books_data['published_date'])) {
                                                $currentDate = new DateTime($books_data['published_date']);
                                                $formattedDate = $currentDate->format('F Y');
                                            ?>
                                                <li><span class="fw-medium">Published Date :</span> <?= $formattedDate; ?></li>
                                            <?php }

                                            if (!empty($books_data['language'])) { ?>
                                                <li><span class="fw-medium">Language :</span> <?= $books_data['language']; ?></li>
                                            <?php } ?>

                                            <li><span class="fw-medium">Pages :</span> <?= $books_data['pages']; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane my-3 fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                <div class="row">

                                    <?php

                                    $review_srch = Database::search("SELECT * FROM `feedback` WHERE `feed_book_id` = '" . $books_data['book_id'] . "' ORDER BY `feed_date` DESC");

                                    if ($review_srch->num_rows > 0) {
                                        for ($x = 0; $x < $review_srch->num_rows; $x++) {
                                            $feed_data = $review_srch->fetch_assoc();
                                    ?>
                                            <?php
                                            $review_send_user = Database::search("SELECT * FROM `users`  WHERE `email` = '" . $feed_data['users_email'] . "'");
                                            $user_data = $review_send_user->fetch_assoc();
                                            $img_data = Database::search("SELECT * FROM `profile_img` WHERE `users_email` = '" . $user_data['email'] . "' ");
                                            $img = $img_data->fetch_assoc();
                                            ?>
                                            <div class="review-card text-start me-2">
                                                <div class="d-flex align-items-center mb-2">

                                                    <img src="<?= (isset($img['img_path'])) ? $img['img_path'] : "./img/profile.png"; ?>" alt="customer-profile-images" height="50" width="50" />

                                                    <div class="ms-3">
                                                        <div class="name d-flex flex-column">

                                                            <span style="font-size: 14px;">

                                                                <?php

                                                                $customer_name = $user_data['first_name'] . " " . $user_data['last_name'];
                                                                $last_characters = substr($customer_name, -7);
                                                                $masked_name = str_repeat("*", strlen($customer_name) - 7) . $last_characters;
                                                                echo $masked_name;

                                                                ?>

                                                            </span>

                                                            <span style="font-size: 12px;">
                                                                Customer Mood :
                                                                <span>
                                                                    <?= ($feed_data['customer_mode'] == 1) ? 'Good' : (($feed_data['customer_mode'] == 2) ? 'Normal' : (($feed_data['customer_mode'] == 3) ? 'Bad' : null)) ?>
                                                                </span>
                                                            </span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="description">
                                                    <span>"<?= $feed_data['feedback_msg'] ?>"</span>
                                                </div>


                                                <div class="stars mt-2">

                                                    <?php
                                                    $rating = intval($feed_data['rating']);
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= $rating) {
                                                            echo '<i class="bi bi-star-fill text-warning"></i>';
                                                        } else {
                                                            echo '<i class="bi bi-star text-warning"></i>';
                                                        }
                                                    }
                                                    ?>

                                                </div>

                                            </div>

                                        <?php }
                                    } else { ?>

                                        <div class="row">
                                            <div class="col-12 py-4 text-center">
                                                <span>Not review yet!</span>
                                            </div>
                                        </div>

                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="toast-container position-fixed bottom-0 end-0 p-3">
                        <div id="liveToast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex align-items-center">
                                <div class="toast-body">
                                    <span style="padding-left: 5px;color: black; font-size: 14px;" id="response-text"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                        <p class="fs-4" style="margin-bottom: 0;">Related books</p>
                        <div>
                            <button class="rounded-5 scroll-left" style="z-index: 1;">
                                <i class="bi bi-arrow-left"></i>
                            </button>
                            <button class="rounded-5 scroll-right" style="z-index: 1;">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 position-relative">
                            <div class="row rounded-4 flex-nowrap overflow-auto nwl-box">
                                <?php

                                $related_books = Database::search("SELECT * FROM `books` INNER JOIN `author_name` ON `books`.`author_name_id` = `author_name`.`id` INNER JOIN `book_category` ON `books`.`book_category_id` = `book_category`.`category_id`  WHERE `category_id` = '" . $books_data['category_id'] . "' LIMIT 12");

                                for ($x = 0; $x < $related_books->num_rows; $x++) {

                                    $related_books_data = $related_books->fetch_assoc();

                                    $maxLength = 15;
                                    $text = $related_books_data['title'];

                                    if (strlen($text) > $maxLength)
                                        $shortenedText = substr($text, 0, $maxLength) . '...';
                                    else
                                        $shortenedText = $text;
                                ?>

                                    <div class="col-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2 mt-4 d-flex flex-column justify-content-between product_item added_items">


                                        <div class="row product_img">


                                            <div onclick="window.location='./book.php?id=<?= $related_books_data['book_id'] ?>' ;">
                                                <div class="col-12 text-center">
                                                    <?php
                                                    $related_books_img = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $related_books_data['book_id'] . "'");
                                                    if ($related_books_img->num_rows == 1) {
                                                        $related_books_img_data = $related_books_img->fetch_assoc();
                                                    ?>
                                                        <img src="<?= $related_books_img_data['img_path']; ?>" width="200px" height="280px" class="rounded-3" alt="Books">
                                                    <?php
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3" onclick="window.location='./book.php?id=<?= $related_books_data['book_id'] ?>' ;">
                                            <div class="pt-1">
                                                <p class="fw-medium text-black" style="font-size: 18px;"><?= $shortenedText; ?></p>
                                                <p class="fw-medium">Rs.<?= $related_books_data['price'] ?>.00</p>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>

                            </div>

                        </div>
                    </div>

                </div>

                <?php include "./components/footer.php"; ?>

                <?php include './models/profile-address.php'; ?>

                <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
                <script src="./js/script.js"></script>
                <script src="./js/bootstrap.bundle.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                    function scrollLeft() {
                        document.querySelector('.nwl-box').scrollBy({
                            left: -300,
                            behavior: 'smooth'
                        });
                    }

                    function scrollRight() {
                        document.querySelector('.nwl-box').scrollBy({
                            left: 300,
                            behavior: 'smooth'
                        });
                    }

                    document.querySelector('.scroll-left').addEventListener('click', scrollLeft);
                    document.querySelector('.scroll-right').addEventListener('click', scrollRight);
                </script>
        </body>

        </html>

<?php

    } else {
        header("location:index.php");
    }
}
