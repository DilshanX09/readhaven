<?php
session_start();
include "../connection.php";
?>
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
    <title>ReadHaven (Admin) BookAdd</title>
</head>

<body>

    <?php include '../components/admin-sidebar.php' ?>

    <section class="home-section">

        <?php include "../components/admin-header.php"; ?>

        <div class="container pt-4 pb-5">

            <nav class="pt-4 pb-3" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="./main-panel.php">Main Panel</a></li>
                    <li class="breadcrumb-item" aria-current="page">Book Management</li>
                    <li class="breadcrumb-item" aria-current="page">Register Book</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col">
                    <h4>Books Register</h4>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12">
                    <div class="row">

                        <?php
                        // 1. Fetch Authors and store in an array
                        $authors = Database::search("SELECT * FROM `author_name`");
                        $author_list = [];
                        while ($row = $authors->fetch_assoc()) {
                            $author_list[] = $row;
                        }
                        $total_authors = count($author_list);
                        ?>

                        <div class="col-6">
                            <span class="txt-primary" style="font-size: 13px;">Registered Authors (<?= $total_authors ?> Authors)</span>
                            <div class="row py-2">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap align-items-center gap-1">
                                        <?php
                                        // Display only up to 5 authors
                                        for ($x = 0; $x < min($total_authors, 5); $x++) {
                                        ?>
                                            <span class="authors-visible px-2" style="font-size: 14px;color: white;">
                                                <?= $author_list[$x]['author_name'] ?>
                                            </span>
                                        <?php } ?>

                                        <?php if ($total_authors > 5) { ?>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#authorModal" style="font-size: 12px; padding: 2px 8px; border-radius: 4px;">
                                                View More...
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        // 2. Fetch Categories and store in an array
                        $category = Database::search("SELECT * FROM `book_category`");
                        $category_list = [];
                        while ($row = $category->fetch_assoc()) {
                            $category_list[] = $row;
                        }
                        $total_categories = count($category_list);
                        ?>

                        <div class="col-6">
                            <span class="txt-primary" style="font-size: 13px;">Registered Categories (<?= $total_categories ?> Categories)</span>
                            <div class="row py-2">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap align-items-center gap-1">
                                        <?php
                                        for ($x = 0; $x < min($total_categories, 5); $x++) {
                                        ?>
                                            <span class="authors-visible px-2" style="font-size: 14px;color: white;">
                                                <?= $category_list[$x]['category_name'] ?>
                                            </span>
                                        <?php } ?>

                                        <?php if ($total_categories > 5) { ?>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal" style="font-size: 12px; padding: 2px 8px; border-radius: 4px;">
                                                View More...
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="authorModal" tabindex="-1" aria-labelledby="authorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content" style="border: 1px solid #f6f6f6;">
                        <div class="modal-header">
                            <h5 class="modal-title fs-6" id="authorModalLabel">All Registered Authors</h5>
                            <button type="button" class="btn-close text-black bg-white" style="font-size: 13px;" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex flex-wrap gap-1">
                            <?php foreach ($author_list as $author) { ?>
                                <span class="authors-visible px-2" style="font-size: 14px;color: white;">
                                    <?= $author['author_name'] ?>
                                </span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content" style="border: 1px solid #f6f6f6;">
                        <div class="modal-header">
                            <h5 class="modal-title fs-6" id="categoryModalLabel">All Registered Categories</h5>
                            <button type="button" class="btn-close text-black bg-white" style="font-size: 13px;" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex flex-wrap gap-1">
                            <?php foreach ($category_list as $cat) { ?>
                                <span class="authors-visible px-2" style="font-size: 14px;color: white;">
                                    <?= $cat['category_name'] ?>
                                </span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <span class="nwaddbk">Store a books </span>
            <div class="row pt-4">
                <div class="col-12">
                    <div class="row" style="overflow-y: scroll;">
                        <div class="col-12 d-flex flex-column justify-content-start align-items-center col-md-5 col-xl-4">
                            <div class="card position-relative w-75" style="height: 400px; border-radius: 0;border: none;">
                                <div class="card-body border-0 d-flex justify-content-center align-items-center">
                                    <label for="imageUpload" onclick="upload_image();">
                                        <img src="../img/choose-image.png" width="200px" id="book_image" alt="img-upload">
                                    </label>
                                    <input type="file" hidden id="imageUpload" />
                                </div>
                            </div>
                            <div class="row py-3">
                                <div class="col-12 d-grid">
                                    <span class="hint-txt">This section used to book cover image upload</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-7 pt-md-0 col-xl-8 pt-3">
                            <div class="row">
                                <div class="col-12 d-none " id="alert">
                                    <div class="alert alert-danger border-0 rounded-0" role="alert" id="response" style="font-size: 13px; margin-bottom: 0;margin-top: 10px;">

                                    </div>
                                </div>
                            </div>
                            <div class="row pt-2 pb-2">
                                <div class="col-12 d-grid">
                                    <span class="hint-txt">This section used to author & Category add</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="userProfileInput mb-2">
                                        <label for="" class="form-label">Author name</label>
                                        <input type="text" placeholder="type author name" id="new_author_name" />
                                    </div>
                                    <div class="row px-0 mx-0">
                                        <div class="col-12 px-0 mx-0  userProfileInput">
                                            <label for="" class="form-label">Author about (optional)</label>
                                            <textarea name="" style="max-height: 100px;" id="author_about"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mx-0 px-0">
                                        <div class="col-12 px-0 mx-0 d-grid py-2 text-end">
                                            <button class="primary-btn rounded-1 px-3" onclick="register_new_author();">Author add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="px-0 mx-0">
                                        <div class="col-12 px-0 mx-0 userProfileInput position-relative">
                                            <label for="" class="form-label">Category</label>
                                            <input type="text" placeholder="Newly add Category" id="new_category" />
                                        </div>
                                    </div>
                                    <div class="px-0 mx-0">
                                        <div class="col-12 mx-0 px-0 py-2 d-grid text-end">
                                            <button class="primary-btn rounded-1 px-2" onclick="register_new_category();">Category add</button>
                                        </div>
                                    </div>
                                    <div class="px-0 mx-0">
                                        <div class="col-12 px-0 mx-0 userProfileInput position-relative">
                                            <label for="" class="form-label">Language</label>
                                            <input type="text" placeholder="Newly add language" id="new_language" />
                                        </div>
                                    </div>
                                    <div class="px-0 mx-0">
                                        <div class="col-12 mx-0 px-0 py-2 d-grid text-end">
                                            <button class="primary-btn rounded-1 px-2" onclick="register_new_language();">Language add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-12 d-grid">
                                    <span class="hint-txt">This section used to book details add</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 userProfileInput">
                                    <label for="" class="form-label">Book title</label>
                                    <input type="text" id="book_title" placeholder="Enter your book title" />
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6 userProfileInput">
                                    <label for="" class="form-label">Book pages count</label>
                                    <input type="text" id="book_page_count" placeholder="Enter your Book page count" />
                                </div>
                                <div class="col-6 userProfileInput">
                                    <label for="" class="form-label">Published Date</label>
                                    <input type="date" id="published_date" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 mt-4 userProfileInput">
                                    <label for="">Select author name</label>
                                    <div class="select_border">
                                        <select name="" id="author_value">
                                            <option value="">Select author</option>
                                            <?php
                                            $author_result = Database::search("SELECT * FROM `author_name`");
                                            for ($x = 0; $x < $author_result->num_rows; $x++) {
                                                $author_data = $author_result->fetch_assoc();
                                            ?>
                                                <option value="<?= $author_data["id"]; ?>">
                                                    <?= $author_data["author_name"]; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4 mt-4 userProfileInput">
                                    <label for="">Select category</label>
                                    <div class="select_border">
                                        <select name="" id="category_value">
                                            <option value="0">Select category</option>
                                            <?php
                                            $category_result = Database::search("SELECT * FROM `book_category`");
                                            for ($x = 0; $x < $category_result->num_rows; $x++) {
                                                $category_data = $category_result->fetch_assoc();
                                            ?>
                                                <option value="<?= $category_data["category_id"]; ?>">
                                                    <?= $category_data["category_name"]; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4 mt-4 userProfileInput">
                                    <label for="">Select language</label>
                                    <div class="select_border">
                                        <select name="" id="language_value">
                                            <option value="0">Select language</option>
                                            <?php
                                            $language_result = Database::search("SELECT * FROM `language` ");
                                            for ($x = 0; $x < $language_result->num_rows; $x++) {
                                                $language_data = $language_result->fetch_assoc();
                                            ?>
                                                <option value="<?= $language_data["l_id"]; ?>">
                                                    <?= $language_data["language"]; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 userProfileInput position-relative">
                                    <label for="" class="form-label">SKU</label>
                                    <input type="text" placeholder="Enter stock keeping unit" id="sku" />
                                </div>
                                <div class="col-6 userProfileInput">
                                    <label for="" class="form-label">ISBN</label>
                                    <input type="text" placeholder="International Standard Book Number" id="isbn" />
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 userProfileInput position-relative">
                                    <label for="" class="form-label">Description</label>
                                    <textarea name="" id="description"></textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 userProfileInput position-relative">
                                    <label for="" class="form-label">Quantity</label>
                                    <input type="number" min="1" value="1" id="qty" />
                                </div>
                                <div class="col-6 userProfileInput">
                                    <label for="" class="form-label">Price</label>
                                    <input type="text" placeholder="Enter book price EX - Rs.1500" id="book_price" />
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 userProfileInput position-relative">
                                    <label for="" class="form-label">Delivery fee colombo</label>
                                    <input type="text" placeholder="Colombo area delivery fee" id="delivery_colombo" />
                                </div>
                                <div class="col-6 userProfileInput">
                                    <label for="" class="form-label">Delivery fee other location</label>
                                    <input type="text" placeholder="Other area delivery fee" id="delivery_other" />
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-12 d-grid">
                                    <button class="primary-btn py-3 rounded-1" onclick="register_book();">Register</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
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
        console.log(sidebarBtn);
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    </script>
</body>

</html>