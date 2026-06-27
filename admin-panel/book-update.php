<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
    <title>ReadHaven | Update book</title>
</head>

<body>
    <?php

    include "../connection.php";

    if (isset($_GET['id'])) {

        $book_id = $_GET['id'];
        $book_srch = Database::search("SELECT * FROM `books` INNER JOIN `author_name` ON `books`.`author_name_id` = `author_name`.`id` INNER JOIN `book_category` ON `books`.`book_category_id` =  `book_category`.`category_id`  INNER JOIN `language` ON `books`.`language_id` = `language`.`l_id`  WHERE `book_id` = '" . $book_id . "'");
        $books_data = $book_srch->fetch_assoc();

    ?>
        <?php include '../components/admin-sidebar.php'; ?>

        <section class="home-section">

            <?php include '../components/admin-header.php'; ?>

            <div class="container pt-4 pb-5">
                <span class="nwaddbk">Store books Update</span>
                <div class="row pt-4">
                    <div class="col-12">
                        <div class="row" style="overflow-y: scroll;">
                            <div class="col-12 d-flex flex-column justify-content-start align-items-center col-md-5 col-xl-4">
                                <div class="card border-0 position-relative w-75" style="height: 400px; border-radius: 0;">
                                    <div class="card-body border-0 d-flex justify-content-center align-items-center">
                                        <label for="imageUpload" onclick="upload_image();">
                                            <?php
                                            $book_img_srch = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $books_data['book_id'] . "'");
                                            $image_count = $book_img_srch->num_rows;
                                            $img_data = $book_img_srch->fetch_assoc();
                                            $img_url = (isset($img_data['img_path'])) ?  $img_data['img_path'] : 'img/not-found.png';

                                            ?>
                                            <img src="<?= '../' . $img_url; ?>" width="300px" class="rounded-3 shadow-lg" alt="book-image">
                                        </label>
                                        <input type="file" hidden id="imageUpload" />
                                    </div>
                                </div>

                            </div>
                            <div class="col-12 col-md-7 pt-md-0 col-xl-8 pt-3">
                                <div class="row">
                                    <div class="col-12 d-none " id="msgBox">
                                        <div class="alert alert-danger border-0 rounded-0" role="alert" id="response_msg" style="font-size: 13px; margin-bottom: 0;margin-top: 10px;">

                                        </div>
                                    </div>
                                </div>
                                <div class="row py-2">
                                    <div class="col-12 d-grid">
                                        <span class="hint-txt">This section used to book details update</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12 userProfileInput">
                                        <label for="" class="form-label">| Book title</label>
                                        <input type="text" value="<?= $books_data['title']; ?>" id="title" placeholder="Enter your book title" />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6 userProfileInput">
                                        <label for="" class="form-label">| Book pages count</label>
                                        <input type="text" value="<?= $books_data['pages']; ?>" id="pages" placeholder="Enter your Book page count" />
                                    </div>
                                    <div class="col-6 userProfileInput">
                                        <label for="" class="form-label">| Published Date</label>
                                        <input type="date" value="<?= $books_data['published_date']; ?>" id="published_date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 mt-4 userProfileInput">
                                        <label for="">| Select author name</label>
                                        <div class="select_border">
                                            <select name="" id="author_value">
                                                <option value="">Select author</option>
                                                <?php
                                                $author_result = Database::search("SELECT * FROM `author_name`");
                                                for ($x = 0; $x < $author_result->num_rows; $x++) {
                                                    $author_data = $author_result->fetch_assoc();
                                                ?>
                                                    <option value="<?= $author_data["id"]; ?>"
                                                        <?php
                                                        if (!empty($books_data['author_name_id'])) {
                                                            if ($books_data['author_name_id'] == $author_data['id']) {
                                                        ?>
                                                        selected
                                                        <?php
                                                            }
                                                        }
                                                        ?>>
                                                        <?= $author_data["author_name"]; ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4 mt-4 userProfileInput">
                                        <label for="">| Select category</label>
                                        <div class="select_border">
                                            <select name="" id="category_value">
                                                <option value="0">Select category</option>
                                                <?php
                                                $category_result = Database::search("SELECT * FROM `book_category`");
                                                for ($x = 0; $x < $category_result->num_rows; $x++) {
                                                    $category_data = $category_result->fetch_assoc();
                                                ?>
                                                    <option value="<?= $category_data["category_id"]; ?>"
                                                        <?php
                                                        if (!empty($books_data['book_category_id'])) {
                                                            if ($books_data['book_category_id'] == $category_data['category_id']) {
                                                        ?>
                                                        selected
                                                        <?php
                                                            }
                                                        }
                                                        ?>>
                                                        <?= $category_data["category_name"]; ?>
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4 mt-4 userProfileInput">
                                        <label for="">| Select language</label>
                                        <div class="select_border">
                                            <select name="" id="language_value">
                                                <option value="0">Select language</option>
                                                <?php

                                                $language_result = Database::search("SELECT * FROM `language`");

                                                for ($x = 0; $x < $language_result->num_rows; $x++) {
                                                    $language_data = $language_result->fetch_assoc();
                                                ?>
                                                    <option value="<?= $language_data["l_id"]; ?>"
                                                        <?php
                                                        if (!empty($books_data['language_id'])) {
                                                            if ($books_data['language_id'] == $language_data['l_id']) {
                                                        ?>
                                                        selected
                                                        <?php
                                                            }
                                                        } ?>>
                                                        <?= $language_data["language"]; ?>
                                                    </option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6 userProfileInput position-relative">
                                        <label for="" class="form-label">| SKU</label>
                                        <input type="text" value="<?= $books_data['sku'] ?>" placeholder="Enter stock keeping unit" id="sku" />
                                    </div>
                                    <div class="col-6 userProfileInput">
                                        <label for="" class="form-label">| ISBN</label>
                                        <input type="text" value="<?= $books_data['isbn'] ?>" placeholder="International Standard Book Number" id="isbn" />
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 userProfileInput position-relative">
                                        <label for="" class="form-label">| Description</label>
                                        <textarea class="p-2" name="" id="description"><?= $books_data['description'] ?></textarea>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6 userProfileInput position-relative">
                                        <label for="" class="form-label">| Quantity</label>
                                        <input type="number" min="1" value="<?= $books_data['qty'] ?>" id="qty" />
                                    </div>
                                    <div class="col-6 userProfileInput">
                                        <label for="" class="form-label">| Price</label>
                                        <input type="text" value="<?= $books_data['price'] ?>" placeholder="Enter book price EX - Rs.1500" id="book_price" />
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6 userProfileInput position-relative">
                                        <label for="" class="form-label">| Delivery fee colombo</label>
                                        <input type="text" value="<?= $books_data['delivery_fee_colombo'] ?>" placeholder="Colombo area delivery fee" id="delivery_colombo" />
                                    </div>
                                    <div class="col-6 userProfileInput">
                                        <label for="" class="form-label">| Delivery fee other location</label>
                                        <input type="text" value="<?= $books_data['delivery_fee_other'] ?>" placeholder="Other area delivery fee" id="delivery_other" />
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-12 d-grid">
                                        <button class="primary-btn py-3 rounded-1" onclick="update_book(<?= $books_data['book_id'] ?>);">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php } ?>

    <script src="../js/script.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement;
                arrowParent.classList.toggle("showMenu");
            });
        }
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    </script>
</body>

</html>