<?php

session_start();
include  'connection.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/bootstrap.css" />
    <link rel="stylesheet" href="./css/index.css" />
    <link rel="icon" type="image/png" href="./img/favicon.png" />
    <title>ReadHaven | Order History</title>
</head>

<body>

    <?php

    include './components/header.php';

    if (isset($_SESSION['user'])) {
        $user_email = $_SESSION['user']['email'];
        $invoice_srch = Database::search("SELECT * FROM `invoice` INNER JOIN `books` ON invoice.book_id = books.book_id  WHERE `users_email` = '$user_email' ORDER BY `date` ASC");
    ?>
        <div class="container py-4">
            <div class="row">
                <div class="col-12">
                    <nav class="pt-4 pb-3" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Order History</li>
                        </ol>
                    </nav>

                    <h4>My Order history on ReadHaven.lk </h4>
                    <div class="row">
                        <div class="col-12 d-none" id="msgBox">
                            <div class="alert alert-success border-0 rounded-0" role="alert" id="response_msg" style="font-size: 13px; margin-bottom: 0;margin-top: 10px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="toast-container position-fixed bottom-0 end-0 cstm-toast p-3">
                <div id="liveToast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex align-items-center">
                        <div class="toast-body">
                            <span style="padding-left: 5px;color: black; font-size: 14px;" id="response-text"></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php

            if ($invoice_srch->num_rows == 0) {

            ?>
                <div class="row">
                    <div class="col-12 d-flex flex-column gap-3 justify-content-between align-items-center">
                        <img src="./img/not-result-found.jpg" width="300px" alt="#not-found">
                        <span class="fs-4">Explore Our Products and Order!</span>
                        <a href="index.php" class="primary-btn rounded-1 px-4">Shop now</a>
                    </div>
                </div>

            <?php

            } else {

            ?>
                <div class="row pt-3">
                    <div class="col-12">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active px-2" id="pill-tab-0" data-bs-toggle="pill" href="#pill-tabpanel-0" role="tab" aria-controls="pill-tabpanel-0" aria-selected="true">Order History</a>
                            </li>
                            <li class="nav-item">
                                <?php

                                $review_books = Database::search("SELECT * FROM `feedback` INNER JOIN `books` ON feedback.feed_book_id = books.book_id  WHERE `users_email` = '$user_email'");

                                $feedback_count = $review_books->num_rows;

                                ?>
                                <a class="nav-link px-2" id="pill-tab-1" data-bs-toggle="pill" href="#pill-tabpanel-1" role="tab" aria-controls="pill-tabpanel-1" aria-selected="false">Reviewed(<?= $feedback_count ?>)</a>

                            </li>

                        </ul>
                        <div class="tab-content py-3 mt-2">
                            <div class="tab-pane active" id="pill-tabpanel-0" role="tabpanel" aria-labelledby="pill-tab-0">
                                <div class="row">
                                    <div class="col-12">
                                        <table style="width: 100%;" class="ord-tbl">
                                            <tr class="horizontal-line-table">
                                                <th style="width: 10%;">
                                                    <h6 class="mt-2">ORDER ID</h6>
                                                </th>
                                                <th style="width: 30%;">
                                                    <h6 class="mt-2">BOOK</h6>
                                                </th>
                                                <th style="width: 15%;" class="text-center">
                                                    <h6 class="mt-2">SUBTOTAL</h6>
                                                </th>
                                                <th style="width: 15%;" class="text-center">
                                                    <h6 class="mt-2">ORDER DATE</h6>
                                                </th>
                                                <th class="mt-2 text-center" style="width: 30%;">
                                                    <h6 class="mt-2">ACTION</h6>
                                                </th>
                                            </tr>

                                            <?php

                                            $limit = 10;
                                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                            $start = ($page - 1) * $limit;

                                            $total_results = $invoice_srch->num_rows;
                                            $total_pages = ceil($total_results / $limit);

                                            $invoice_srch_paginated = Database::search("SELECT * FROM `invoice` INNER JOIN `books` ON invoice.book_id = books.book_id WHERE `users_email` = '$user_email' ORDER BY `date` ASC LIMIT $start, $limit");

                                            for ($x = 0; $x < $invoice_srch_paginated->num_rows; $x++) {
                                                $invoice_data = $invoice_srch_paginated->fetch_assoc();
                                            ?>

                                                <tr class="horizontal-line">
                                                    <td>
                                                        <span class="text-black text-uppercase fw-medium" style="font-size: 13px;">#<?= $invoice_data['order_id'] ?></span>
                                                    </td>
                                                    <td class="d-flex align-items-center gap-2 my-2">
                                                        <?php
                                                        $book_img = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $invoice_data['book_id'] . "'");
                                                        $book_data = $book_img->fetch_assoc();
                                                        ?>
                                                        <img src="<?= $book_data['img_path'] ?>" width="40px" />
                                                        <span style="font-size: 14px;color: black;"><?= $invoice_data['title'] ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-black" style="font-size: 14px;">RS. <?= $invoice_data['total'] ?> .00</span>
                                                    </td>
                                                    <th class="text-center">
                                                        <span class="text-black fw-normal" style="font-size: 14px;"><?= $invoice_data['date'] ?></span>
                                                    </th>
                                                    <th>
                                                        <div class="d-flex justify-content-center gap-5">

                                                            <a onclick="save_return_url();" style="cursor: pointer;" href='review-order.php?bid=<?= $invoice_data['book_id'] ?>&uid=<?= $user_email ?>' class="hv-icon d-flex justify-content-center align-items-center px-3 py-2 rounded-5">
                                                                <p style="font-size: 13px;" class="fw-normal text-black">Write a Review</p>
                                                            </a>

                                                            <div style="cursor: pointer;" onclick="window.location.href='check-out-invoice.php?id=<?= $invoice_data['order_id'] ?>'" class="p-2 rounded-5">
                                                                <img src="./img/preview-invoice.png" alt="preview-invoice" width="21px" height="21px" />
                                                            </div>
                                                            <span onclick="delete_order_history_book(<?= $invoice_data['invoice_id'] ?>);" style="cursor: pointer;" class="p-2 rounded-5 "> <img src="./img/remove.png" alt="move-to-trash" width="21px" height="21px" /></span>

                                                        </div>
                                                    </th>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </table>
                                        <div class="row py-3">
                                            <div class="col-12">
                                                <span class="text-black fs-13">items <?= $total_results; ?> </span>
                                            </div>
                                        </div>
                                        <nav class="d-flex justify-content-center" aria-label="Page navigation">
                                            <ul class="pagination justify-content-center gap-1">
                                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                                    <a class="page-link page-link-prnv" href="<?php if ($page > 1) echo '?page=' . ($page - 1); ?>" aria-label="Previous">
                                                        <i class="bi bi-arrow-left-short fs-6"></i>
                                                    </a>
                                                </li>
                                                <?php
                                                for ($x = 1; $x <= $total_pages; $x++) {
                                                ?>
                                                    <li class="page-item">
                                                        <a class="page-link <?php if ($page == $x) echo 'page-active';
                                                                            else echo 'bg-none-active'; ?>" href="?page=<?= $x; ?>">
                                                            <?= $x; ?>
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                                                    <a class="page-link page-link-prnv" href="<?php if ($page < $total_pages) echo '?page=' . ($page + 1); ?>" aria-label="Next">
                                                        <i class="bi bi-arrow-right-short fs-6"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane rw-sc-sc" id="pill-tabpanel-1" role="tabpanel" aria-labelledby="pill-tab-1">
                                <div class="row">
                                    <div class="col-12 d-flex flex-row gap-2">
                                        <?php
                                        if ($review_books->num_rows > 0) {
                                            for ($x = 0; $x < $review_books->num_rows; $x++) {
                                                $review_data = $review_books->fetch_assoc();
                                        ?>

                                                <div class="review-card-review position-relative">
                                                    <div class="d-flex">
                                                        <?php
                                                        $feedBookCover = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $review_data['feed_book_id'] . "' ");
                                                        $cover = $feedBookCover->fetch_assoc();
                                                        ?>
                                                        <img src="<?= $cover['img_path'] ?>" alt="Book cover image" width="60" height="100">
                                                        <div class="ms-3 ">
                                                            <div class="review-date-review">
                                                                <span class="fs-13"> Reviewed Date : <?= $review_data['feed_date'] ?></span>
                                                            </div>
                                                            <div class="book-title pt-1 fs-13">
                                                                <?= $review_data['title'] ?>
                                                            </div>
                                                            <div class="stars mt-2">
                                                                <?php
                                                                $rating = intval($review_data['rating']);
                                                                for ($i = 1; $i <= 5; $i++) {
                                                                    if ($i <= $rating) {
                                                                        echo '<i class="bi bi-star-fill text-warning"></i>';
                                                                    } else {
                                                                        echo '<i class="bi bi-star text-warning"></i>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="review-comment">
                                                                <span style="font-size: 13px;">
                                                                    <?= $review_data['feedback_msg'] ?>
                                                                </span>
                                                            </div>

                                                        </div>
                                                        <div onclick="delete_review(<?= $review_data['feed_id'] ?>)" class="position-absolute w-25 top-2 end-0">
                                                            <div class="text-center rounded-pill">
                                                                <span class="text-white" style="font-size: 13px;cursor: pointer;"><i class="bi bi-trash text-black hv-icon px-1 py-1 rounded-5"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="row">
                                                <div class="col 12 d-flex justify-content-center">
                                                    <h6>Your not add to reviewed.. !</h6>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
    <?php
    } else {
        header("Location:sign-in.php");
    }
    ?>



    <?php include './components/footer.php'; ?>
    <script src="./js/script.js"></script>
    <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>