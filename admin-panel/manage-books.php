<?php include '../connection.php';
session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <title>ReadHaven | Manage Books</title>
</head>

<body>

    <?php include '../components/admin-sidebar.php'; ?>

    <div class="home-section">
        <?php include '../components/admin-header.php'; ?>

        <div class="container-fluid py-3">

            <nav class="pt-4 pb-3 ps-3" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="./main-panel.php">Main Panel</a></li>
                    <li class="breadcrumb-item" aria-current="page">Book Management</li>
                    <li class="breadcrumb-item" aria-current="page">Manage Book</li>
                </ol>
            </nav>

            <div class="row ps-3">
                <div class="col">
                    <h4>Books Management</h4>
                </div>
            </div>

            <div class="card" style="border: none;">
                <div class="card-body">
                    <div class="row">

                        <div class="col-12">
                            <h6>Filters</h6>
                        </div>

                        <div class="col-12 py-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="srt-select p-2">
                                    <select id="category">
                                        <option value="0">Books Category</option>
                                        <?php
                                        $category_srch = Database::search("SELECT * FROM `book_category`");
                                        for ($i = 0; $i < $category_srch->num_rows; $i++) {
                                            $ct_data = $category_srch->fetch_assoc();
                                        ?>
                                            <option value="<?= $ct_data['category_id'] ?>"><?= $ct_data['category_name'] ?></option>
                                        <?php
                                        }
                                        ?>

                                    </select>
                                </div>
                                <div class="srt-select p-2">
                                    <select id="author">
                                        <option value="0">Books Author</option>
                                        <?php $author_srch = Database::search("SELECT * FROM `author_name`");
                                        for ($n = 0; $n < $author_srch->num_rows; $n++) {
                                            $author_data = $author_srch->fetch_assoc(); ?>
                                            <option value="<?= $author_data['id'] ?>"><?= $author_data['author_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="srt-select p-2">
                                    <select id="status">
                                        <option value="0">Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">In-Active</option>
                                    </select>
                                </div>

                                <div class="srt-select p-2">
                                    <select id="stock">
                                        <option value="0">Stock</option>
                                        <option value="1">In stock</option>
                                        <option value="2">Out of stock</option>
                                    </select>
                                </div>


                            </div>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center">
                                <input class="ap-input" id="keyword" type="text" placeholder="Search Books.." />
                                <button class="ap-btn-clr" onclick="search_books(0);">Find Books</button>
                                <button onclick=" window.location.reload();" class="bg-transparent pt-1 d-flex align-items-center" style="outline: none;border: none;"><i class="bi bi-arrow-clockwise fs-4 fw-bold"></i></button>
                            </div>
                        </div>

                        <div id="rslt">
                            <div class="row">
                                <?php
                                $admin_panel_view_books = Database::search("SELECT * FROM `books` INNER JOIN `author_name` ON `books`.`author_name_id` = `author_name`.`id` INNER JOIN `book_category` ON `books`.`book_category_id` = `book_category`.`category_id` INNER JOIN `active` ON `books`.`active_active_id` = `active`.`active_id`  ORDER BY `datetime_added` DESC LIMIT 50 OFFSET 0");
                                for ($x = 0; $x < $admin_panel_view_books->num_rows; $x++) {
                                    $bookData = $admin_panel_view_books->fetch_assoc();
                                ?>
                                    <div class="col-6 col-md-4  col-lg-3 col-xl-2 col-xxl-2 mt-4 d-flex flex-column product_item added_items">
                                        <div class="row product_img position-relative">

                                            <div class="col-12 text-center">
                                                <?php

                                                $selected_book_image = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $bookData['book_id'] . "' ");

                                                if ($selected_book_image->num_rows > 0) {
                                                    $bookImage = $selected_book_image->fetch_assoc();
                                                    $image_url = (isset($bookImage['img_path'])) ?  $bookImage['img_path'] : 'img/not-found.png';
                                                }

                                                ?>

                                                <img src="<?= '../' . $image_url; ?>" alt="book-image" width="100%" height="100%" class="rounded-2">

                                            </div>

                                        </div>

                                        <div class="mt-2 p-3 rounded-2" style="background-color: #f6f6f6;">

                                            <?php

                                            $maxLength = 15;
                                            $text = $bookData['title'];
                                            $shortenedText;

                                            if (strlen($text) > $maxLength) $shortenedText = substr($text, 0, $maxLength) . '...';
                                            else $shortenedText = $text;

                                            ?>

                                            <p style="font-size: 15px;margin-bottom: 2px;" class="fw-medium"><?= $shortenedText; ?></p>
                                            <p style="margin-bottom: 2px;">RS.<?= $bookData['price']; ?>.00</p>
                                            <p style="font-size: 13px;margin-bottom: 2px;">Author , <?= $bookData['author_name']; ?></p>
                                            <p class="category_display fs-13">Category, <?= $bookData['category_name']; ?></p>

                                            <div class="row pt-1">

                                                <div class="col-6 fw-medium">
                                                    <?php $isAvilable = ($bookData['qty'] > 0) ? 'in stock' : 'out of stock'; ?>
                                                    <p style="font-size: 13px;"><?= $isAvilable; ?></p>
                                                </div>

                                                <div class="col-6 text-end">
                                                    <span style="font-size: 13px;">qty : <?= $bookData['qty']; ?></span>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-check mt-1 form-switch">

                                            <?php $isActive = ($bookData['active_active_id'] == 1) ? true : false ?>
                                            <?php $isActiveText = ($bookData['active_active_id'] == 2) ? 'Switch on to Book Active' : 'Switch off to Book Deactive' ?>

                                            <input class="form-check-input" onchange="book_status_toggle(<?= $bookData['book_id']; ?>);" type="checkbox" role="switch" id="toggle<?= $bookData['book_id']; ?>"
                                                <?= ($isActive) ? 'checked' : null ?>>

                                            <label class="form-check-label" for="toggle<?= $bookData['book_id']; ?>">
                                                <?= $isActiveText ?>
                                            </label>

                                        </div>

                                        <div class="mt-1 text-danger d-flex align-items-center px-2 py-1 rounded-1" style="cursor: pointer;background-color: #fef2f2;" onclick="window.location='book-update.php?id=<?= $bookData['book_id']; ?>';">
                                            <p style="font-size: 14px;">Edit book</p>
                                            <i class="bi bi-arrow-right-short fs-5"></i>
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




    </div>



    <script src="../js/script.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>


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
        console.log(sidebarBtn);
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    </script>
</body>

</html>