<?php

include '../connection.php';

$category = $_POST['category'];
$author = $_POST['author'];
$status = $_POST['status'];
$stock = $_POST['stock'];
$keyword = $_POST['keyword'];

$search_query = "SELECT * FROM `books` INNER JOIN `author_name` ON books.author_name_id = author_name.id INNER JOIN `book_category` ON books.book_category_id = book_category.category_id ";

$conditions = [];

if ($category != 0) {
    $conditions[] = "books.book_category_id = '$category'";
}

if ($author != 0) {

    $conditions[] = "books.author_name_id = '$author'";
}
if ($status != 0) {
    $conditions[] = "books.active_active_id = '$status'";
}

if ($stock != 0) {
    if ($stock == 1) {
        $conditions[] = "books.qty > 0";
    } else if ($stock == 2) {
        $conditions[] = "books.qty = 0";
    }
}

if (!empty($keyword)) {
    $conditions[] = "(books.title LIKE '%$keyword%' OR author_name.author_name LIKE '%$keyword%' OR book_category.category_name LIKE '%$keyword%')";
}

if (count($conditions) > 0) {
    $search_query .= "WHERE " . implode(' AND ', $conditions);
}

/*
    * Querys test *
    * while (count($conditions) > 0) {
    *    echo array_shift($conditions) . '<br>';
    * }
*/

?>


<div class="row">

    <?php

    $page_number;

    if ("0" != $_POST["page"]) {
        $page_number = $_POST["page"];
    } else {
        $page_number = 1;
    }

    $books_result = Database::search($search_query);
    $books_list_number = $books_result->num_rows;
    $book_per_page = 50;
    $number_of_pages = ceil($books_list_number / $book_per_page);

    $page_results = ($page_number - 1) * $book_per_page;
    $selected_books_result = Database::search($search_query . " LIMIT " . $book_per_page . " OFFSET " . $page_results . "");
    $selected_book_list = $selected_books_result->num_rows;


    if ($selected_book_list == 0) { ?>

        <div class="row mt-5 pt-5">
            <div class="col d-flex flex-column align-items-center">
                <img src=".././img/not-result-found.jpg" width="150px" />
                <h5 class="mt-5 query-center">No results found.</h5>
                <button onclick="window.location.reload();" style="outline: none; color: black; background-color: #f6f6f6; font-size: 13px; padding:10px 15px; border: none; border-radius: 7px; ">
                    Refresh
                </button>
            </div>
        </div>

    <?php } ?>

    <?php

    for ($x = 0; $x < $selected_book_list; $x++) {
        $selected_books = $selected_books_result->fetch_assoc();
    ?>
        <div class="col-6 col-md-4  col-lg-3 col-xl-2 col-xxl-2 mt-4 d-flex flex-column product_item added_items">
            <div class="row product_img position-relative">

                <div class="col-12 text-center">
                    <?php

                    $selected_book_image = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $selected_books['book_id'] . "' ");

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
                $text = $selected_books['title'];
                $shortenedText;

                if (strlen($text) > $maxLength) $shortenedText = substr($text, 0, $maxLength) . '...';
                else $shortenedText = $text;

                ?>

                <p style="font-size: 15px;margin-bottom: 2px;" class="fw-medium"><?= $shortenedText; ?></p>
                <p style="margin-bottom: 2px;">RS.<?= $selected_books['price']; ?>.00</p>
                <p style="font-size: 13px;margin-bottom: 2px;">Author , <?= $selected_books['author_name']; ?></p>
                <p class="category_display fs-13">Category, <?= $selected_books['category_name']; ?></p>

                <div class="row pt-1">

                    <div class="col-6 fw-medium">
                        <?php $isAvilable = ($selected_books['qty'] > 0) ? 'in stock' : 'out of stock'; ?>
                        <p style="font-size: 13px;"><?= $isAvilable; ?></p>
                    </div>

                    <div class="col-6 text-end">
                        <span style="font-size: 13px;">qty : <?= $selected_books['qty']; ?></span>
                    </div>

                </div>
            </div>

            <div class="form-check mt-1 form-switch">

                <?php $isActive = ($selected_books['active_active_id'] == 1) ? true : false ?>
                <?php $isActiveText = ($selected_books['active_active_id'] == 2) ? 'Switch on to Book Active' : 'Switch off to Book Deactive' ?>

                <input class="form-check-input" onchange="book_status_toggle(<?= $selected_books['book_id']; ?>);" type="checkbox" role="switch" id="toggle<?= $selected_books['book_id']; ?>"
                    <?= ($isActive) ? 'checked' : null ?>>

                <label class="form-check-label" for="toggle<?= $selected_books['book_id']; ?>">
                    <?= $isActiveText ?>
                </label>

            </div>

            <div class="mt-1 text-danger d-flex align-items-center px-2 py-1 rounded-1" style="cursor: pointer;background-color: #fef2f2;" onclick="window.location='book-update.php?id=<?= $selected_books['book_id']; ?>';">
                <p style="font-size: 14px;">Edit book</p>
                <i class="bi bi-arrow-right-short fs-5"></i>
            </div>

        </div>

    <?php

    }

    ?>

    <div class="container my-5">
        <nav aria-label="Page navigation example">
            <ul class="pagination d-flex align-items-center justify-content-center gap-1">

                <!-- Previous button -->
                <li class="page-item  <?= ($page_number <= 1) ? 'disabled' : ''; ?>">
                    <a
                        class="page-link page-link-prnv"
                        href="<?= ($page_number <= 1)
                                    ? '#'
                                    : 'search.php?q=' . urlencode($_GET['q'] ?? '') . '&c=' . urlencode($_GET['c'] ?? '') . '&page=' . ($page_number - 1); ?>"
                        aria-label="Previous">
                        <i class="bi bi-arrow-left-short fs-5"></i>
                    </a>
                </li>

                <!-- Page numbers -->
                <?php for ($x = 1; $x <= $number_of_pages; $x++): ?>
                    <li class="page-item">
                        <a
                            class="page-link fs-5 <?= ($page_number == $x) ? 'page-active' : 'none-active'; ?>"
                            href="search.php?q=<?= urlencode($_GET['q'] ?? '') ?>&c=<?= urlencode($_GET['c'] ?? '') ?>&page=<?= $x ?>">
                            <?= $x ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Next button -->
                <li class="page-item <?= ($page_number >= $number_of_pages) ? 'disabled' : ''; ?>">
                    <a
                        class="page-link page-link-prnv"
                        href="<?= ($page_number >= $number_of_pages)
                                    ? '#'
                                    : 'search.php?q=' . urlencode($_GET['q'] ?? '') . '&c=' . urlencode($_GET['c'] ?? '') . '&page=' . ($page_number + 1); ?>"
                        aria-label="Next">
                        <i class="bi bi-arrow-right-short fs-5"></i>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
