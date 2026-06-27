<?php

session_start();
include "../connection.php";

$category = strval($_POST['category']) ?? '';
$keyword = strval($_POST['keyword']) ?? '';
$price = intval($_POST['sort_by_prices']) ?? 0;
$stock = $_POST['stock'];

/*
    stage 1 -> find in stock checked and price high to low [1] -> done

    stage 3 -> find category is set and price high to low [1]

    stage 5 -> find in stock checked and category set to price high to low [1] -> done
*/

$search_query = "SELECT * FROM books
    INNER JOIN author_name ON books.author_name_id = author_name.id
    INNER JOIN book_category ON books.book_category_id = book_category.category_id ";

# price [1] ==> high to low
# price [2] ==> low to high

$where = [];

if (!empty($category)) {
    $where[] = "book_category.category_name = '" . $category . "'";
}

if (!empty($keyword)) {
    $where[] = "(books.title LIKE '%$keyword%' OR author_name.author_name LIKE '%$keyword%' OR book_category.category_name LIKE '%$keyword%')";
}

if ($stock === 'true') {
    $where[] = "books.qty > 0";
}

if (count($where) > 0) {

    $search_query .= " WHERE " . implode(" AND ", $where) . " AND active_active_id = '1'";

    if ($price == 1) $search_query .= " ORDER BY books.price DESC";
    else if ($price == 2) $search_query .= " ORDER BY books.price ASC";
} else {
    $search_query .= " ORDER BY books.price DESC";
}

// while (count($where) > 0) {
//     echo array_shift($where) . '<br>';
// }

// echo $search_query;

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
    $selected_books = Database::search($search_query . " LIMIT " . $book_per_page . " OFFSET " . $page_results . "");
    $selected_book_list = $selected_books->num_rows;


    for ($x = 0; $x < $selected_book_list; $x++) {
        $selected_books_data = $selected_books->fetch_assoc();
        $maxLength = 25;
        $text = $selected_books_data['title'];

        if (strlen($text) > $maxLength) {
            $shortenedText = substr($text, 0, $maxLength) . '...';
        } else {
            $shortenedText = $text;
        }

    ?>

        <div class="col-6  col-md-4 col-xl-3  py-2 d-flex flex-column">

            <div class="row product_img position-relative">

                <?php

                if (isset($_SESSION['user'])) {

                    $email = $_SESSION['user']['email'];

                    $watchlist = Database::search("SELECT * FROM `watchlist`  WHERE `books_book_id` = '" . $selected_books_data['book_id'] . "' AND `users_email` = '" . $email . "'");

                    $isAddedWatchlist = ($watchlist->num_rows == 1) ? true : false;
                }

                ?>

                <span onclick="book_add_to_wishlist(<?= $selected_books_data['book_id'] ?>);" class="hrt-icn" style="right: 25px;top: 10px;">
                    <i id="heart<?= $selected_books_data['book_id'] ?>" class="bi bi-heart <?= $isAddedWatchlist ? 'text-danger' : '' ?>"></i>
                </span>

                <span <?php if ($selected_books_data['qty'] > 0) { ?> onclick="book_add_cart(<?= $selected_books_data['book_id'] ?> , <?= $selected_books_data['qty']; ?>);" <?php } ?> style="position: absolute;top: 55px;right: 25px;" class="bg-white cart-icon">
                    <i class="bi bi-bag"></i>
                </span>


                <div class="col-12 text-center">

                    <?php
                    $bookImageResult = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $selected_books_data["book_id"] . "' ");
                    $booksImagedata = $bookImageResult->fetch_assoc();
                    $issetImage = ($bookImageResult->num_rows > 0) ? $booksImagedata["img_path"] : "./img/not-found.png";
                    ?>

                    <img onclick="window.location='./book.php?id=<?= $selected_books_data['book_id'] ?>' ;"
                        src="<?= $issetImage ?>" alt="book-image"  width="100%" height="100%" class="rounded-2">
                </div>

            </div>

            <div class="pt-2" onclick="window.location='./book.php?id=<?= $selected_books_data['book_id'] ?>' ;">
                <p class="fw-medium" style="font-size: 16px;"><?= $shortenedText ?></p>
                <p class="author_display">Author, <?= $selected_books_data['author_name'] ?></p>
                <h6 style="margin:5px 0;">RS.<?= $selected_books_data['price'] ?>.00</h6>
                <p style="font-size: 13px;" class="text-muted"><?= ($selected_books_data['qty'] == 0) ? "Out Of Stock" : "" ?></p>
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
</div>
