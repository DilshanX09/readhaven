<?php

session_start();

include "./connection.php";
require_once './class/StringExploder.php';

if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {

    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];

    $user_data = Database::search("SELECT * FROM `users` WHERE `email` = '" . $email . "' AND `password` = '" . $password . "' ");

    if ($user_data->num_rows == 1)  $_SESSION['user'] = $user_data->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    <link rel="stylesheet" href="./css/bootstrap.css" />
    <link rel="stylesheet" href="./css/index.css" />
    <title>ReadHaven</title>
</head>

<body>

    <div class="position-relative">

        <?php require_once "./components/header.php"; ?>

        <div class="mt-2 mt-sm-5" id="result">

            <?php require_once './components/carousel.php'; ?>

            <div class="container">

                <?php require_once './components/newly-added-books.php'; ?>

                <div class="toast-container position-fixed bottom-0 end-0 cstm-toast p-3">
                    <div id="liveToast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex align-items-center">
                            <div class="toast-body">
                                <span style="padding-left: 5px;color: black; font-size: 14px;" id="response-text"></span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">

                    <?php

                    $category_result = Database::search("SELECT * FROM book_category");

                    for ($i = 0; $i < $category_result->num_rows; $i++) {
                        $category_data = $category_result->fetch_assoc();

                    ?>
                        <div class="row pt-4">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <h6 class="fw-medium"><span class="txt-primary fs-4"></span><?= $category_data["category_name"]; ?></h6>
                                <a href="./view-more.php?category=<?= $category_data["category_id"] ?>">
                                    <div class="d-flex flex-row align-items-center gap-2">
                                        <span class="text-secondary" style="font-size: 13px;">View More</span>
                                        <span class="pt-1"> <i class="bi bi-arrow-right-circle text-secondary"></i></span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <?php

                        $books_in_category_result = Database::search("SELECT * FROM books 
                            INNER JOIN book_category ON books.book_category_id =  book_category.category_id
                            WHERE book_category_id = '" . $category_data["category_id"] . "' ");

                        $book_result = Database::search("SELECT * FROM books  
                            INNER JOIN author_name ON books.author_name_id = author_name.id
                            WHERE book_category_id = '" . $category_data["category_id"] . "' AND active_active_id = '1' ORDER BY datetime_added DESC LIMIT 20 OFFSET 0 ");

                        $books = $book_result->num_rows;
                        $category_name_result_data = $books_in_category_result->fetch_assoc();

                        if ($books > 0) {

                            for ($y = 0; $y < $books; $y++) {

                                $book_data = $book_result->fetch_assoc();

                                $maxLength = 25;
                                $text = $book_data['title'];

                                if (strlen($text) > $maxLength) {
                                    $shortenedText = substr($text, 0, $maxLength) . '...';
                                } else {
                                    $shortenedText = $text;
                                }

                        ?>
                                <!-- Single Book view -->

                                <div class="col-6 col-md-4 col-xl-3 col-xxl-2 py-2 d-flex flex-column" role="button">

                                    <div class="row product_img position-relative">

                                        <?php

                                        if (isset($_SESSION['user'])) {
                                            $email = $_SESSION['user']['email'];
                                            $watchlist = Database::search("SELECT * FROM `watchlist`  WHERE `books_book_id` = '" . $book_data['book_id'] . "' AND `users_email` = '" . $email . "'");
                                            $isAddedWatchlist = ($watchlist->num_rows == 1) ? true : false;
                                        }

                                        ?>


                                        <span <?php if (!empty($email)): ?> onclick="book_add_to_wishlist(<?= $book_data['book_id'] ?>);" <?php else: ?> onclick="visible_auth_model();" <?php endif; ?> class="hrt-icn shadow-lg" style="right: 20px;top: 10px; cursor: pointer;">
                                            <i id="heart<?= $book_data['book_id'] ?>" class="bi bi-heart <?= $isAddedWatchlist ? 'text-danger' : '' ?>"></i>
                                        </span>

                                        <?php if ($book_data['qty'] > 0) { ?>

                                            <span <?php if (!empty($email)): ?> onclick="book_add_cart(<?= $book_data['book_id'] ?> , <?= $book_data['qty']; ?>);" <?php else: ?> onclick="visible_auth_model();" <?php endif; ?> style="position: absolute;top: 55px;right: 20px; cursor: pointer;" class="bg-white cart-icon shadow-lg">
                                                <i class="bi bi-bag"></i>
                                            </span>

                                        <?php } ?>

                                        <div class="col-12 text-center">

                                            <?php
                                            $book_image_result = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $book_data["book_id"] . "' ");
                                            $book_image_data = $book_image_result->fetch_assoc();
                                            $issetImage = ($book_image_result->num_rows > 0) ? $book_image_data["img_path"] : "./img/not-found.png";
                                            ?>

                                            <img onclick="window.location='./book.php?id=<?= $book_data['book_id'] ?>' ;"
                                                src="<?= $issetImage ?>" alt="book-image" width="100%" height="100%" class="rounded-2 normal-border-gray">
                                        </div>

                                    </div>

                                    <div class="pt-2" onclick="window.location='./book.php?id=<?= $book_data['book_id'] ?>' ;">
                                        <p class="fw-medium" style="font-size: 16px;"><?= $shortenedText ?></p>
                                        <p class="author_display">Author, <?= $book_data['author_name'] ?></p>
                                        <h6 style="margin:5px 0;">RS.<?= $book_data['price'] ?>.00</h6>
                                        <p style="font-size: 13px;" class="text-muted"><?= ($book_data['qty'] == 0) ? "Out Of Stock" : "" ?></p>
                                    </div>

                                </div>

                            <?php
                            }
                        } else {
                            ?>
                            <div class="col-12 d-flex justify-content-center align-items-center" style="height: 200px;">
                                <span class="text-secondary" style="font-size: 14px;">No Books Available In <?= $category_data["category_name"]; ?> Category</span>
                            </div>

                    <?php

                        }
                    }

                    ?>
                </div>

                <?php include "./components/faq.php"; ?>

            </div>

        </div>

        <?php include "./components/news-subscription.php"; ?>

        <?php include "./components/footer.php"; ?>

    </div>

    <?php include "./components/chat.php"; ?>


    <div onclick="chat_bot_visible();" class="position-fixed" style="bottom: 13px;right: 10px;">
        <i class="bi bi-chat fs-3 chat-icon"></i>
    </div>

    <script src="./js/script.js"></script>
    <script src="./js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/yesiamrocks/cssanimation.io@1.0.3/letteranimation.min.js"></script>
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
</body>

</html>