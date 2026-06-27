<!DOCTYPE html>

<?php

session_start();
include './connection.php';

# filter all GET parameters
$get_params = $_GET;
$filtered_params = array_filter($get_params);
$new_query_string = http_build_query($filtered_params);

$new_url = 'search.php';
if (!empty($new_query_string)) {
     $new_url .= '?' . $new_query_string . '&page=0';
}

if ($new_url !== $_SERVER['REQUEST_URI']) {
     echo "<script>window.history.replaceState(null, '', '{$new_url}');</script>";
}

if (!isset($_GET['page'])) header('location:index.php');

$query = $_GET['keyword'] ?? null;
$category = $_GET['category'] ?? null;
$in_stock = $_GET['stock'] ?? null;

# Base query to fetch books with necessary joins ==> tables list [books , book_category , author_name]
$search_query = "SELECT * FROM books 
     INNER JOIN author_name ON books.author_name_id = author_name.id 
     INNER JOIN book_category ON books.book_category_id = book_category.category_id ";

# empty array
$where = [];

# This block only execute if query is not empty
if (!empty($query)) {
     $where[] = "(books.title LIKE '%" . $query . "%' OR author_name.author_name LIKE '%" . $query . "%' OR book_category.category_name LIKE '%" . $query . "%')";
}

# This block only execute category is not empty
if (!empty($category)) {
     $where[] = "book_category.category_name = '" . $category . "'";
}

if ($in_stock == 'available') {
     $where[] = "books.qty > 0";
}

# Always add
$where[] = "active_active_id = '1' ORDER BY books.datetime_added DESC";

if (count($where) > 0) {
     $search_query .= " WHERE " . implode(" AND ", $where);
}

/*   
     * final output query example ( Real sample )
     * SELECT * FROM books 
          INNER JOIN author_name ON books.author_name_id = author_name.id 
          INNER JOIN book_category ON books.book_category_id = book_category.category_id 
          WHERE (books.title LIKE '%a%' OR author_name.author_name LIKE '%a%' OR book_category.category_name LIKE '%a%')
          AND book_category.category_name = 'Fantasy, Romance' 
          AND active_active_id = '1' 
          ORDER BY books.datetime_added DESC 
*/

/*
     * $where[] array test program
     while (count($where) > 0) {
          echo array_shift($where) . '<br>';
     }
*/

?>

<html lang="en">

<head>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <link href="https://cdn.jsdelivr.net/gh/yesiamrocks/cssanimation.io@1.0.3/cssanimation.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
     <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
     <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
     <link rel="stylesheet" href="./css/bootstrap.css" />
     <link rel="stylesheet" href="./css/index.css" />
     <script src="./js/script.js"></script>
     <title><?= $query ? "Search results for '$query'" : "All Books" ?></title>
</head>

<body>

     <?php include './components/header.php'; ?>

     <div class="container mt-5">

          <?php include './components/filter.php'; ?>

          <?php if (!empty($query)) { ?>
               <h5 class="mb-5">Search for <span class="query-dark">"<?= $query ?>"</span></h5>
          <?php } ?>

          <div class="row px-0 mx-0">
               <div class="col-12">
                    <div class="row">
                         <div class="col-3 d-none d-lg-block position-sticky top-0">
                              <div class="px-2">
                                   <div class="row">
                                        <div class="col-12">
                                             <h5>IN STOCK</h5>
                                        </div>
                                        <div class="horizontal-line-mini"></div>
                                        <div class="col-12 srt-radio">
                                             <div class="form-check pt-3">
                                                  <input class="form-check-input" <?= (isset($_GET['stock']) && $_GET['stock'] == 'available') ? 'checked' : '' ?> onclick="find_in_stock_book(this , 0)" value="1" type="checkbox" id="in_stock" name="in_stock">
                                                  <label class="form-check-label">
                                                       <span style="font-size: 15px;">Available</span>
                                                  </label>
                                             </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                             <h5>CATEGORIES</h5>
                                        </div>
                                        <div class="horizontal-line-mini"></div>
                                        <?php
                                        $categoryResult = Database::search("SELECT * FROM `book_category`");
                                        $categoryNumber = $categoryResult->num_rows;
                                        for ($x = 0; $x < $categoryNumber; $x++) {
                                             $categoryData = $categoryResult->fetch_assoc();
                                        ?>
                                             <div class="col-12 pt-3">
                                                  <div class="row">
                                                       <div class="d-flex justify-content-between align-items-center">
                                                            <?php
                                                            if ($category == $categoryData['category_name']) {
                                                            ?>
                                                                 <span style="font-size: 16px; cursor: pointer;" class="highlighted-category" onclick="highlight_category(this, '<?php echo $categoryData['category_name'] ?>' , 0);"><?php echo $categoryData['category_name'] ?></span>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                 <span style="font-size: 16px; cursor: pointer;" onclick="highlight_category(this, '<?php echo $categoryData['category_name'] ?>' , 0);"><?php echo $categoryData['category_name'] ?></span>
                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            $oneByoneRead = Database::search("SELECT * FROM `books` WHERE `book_category_id` = '" . $categoryData['category_id'] . "' AND `active_active_id` = '1' ");
                                                            ?>
                                                            <span>(<?php echo $oneByoneRead->num_rows ?>)</span>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="horizontal-line mt-1" style="margin-left: 11px;"></div>
                                        <?php
                                        }
                                        ?>
                                        <div class="col-12 mt-3">
                                             <h5>FILTER BY PRICE</h5>
                                        </div>
                                        <div class="horizontal-line-mini"></div>
                                        <div class="row mt-3">
                                             <div class="price-range-slider">
                                                  <span>Minimum</span>
                                                  <input type="range" class="form-range" id="priceRangeMin" min="0" value="0" name="min" max="50000" oninput="update_price_range()">
                                                  <span>Maximum</span>
                                                  <input type="range" class="form-range" id="priceRangeMax" min="0" max="50000" value="0" name="max" oninput="update_price_range()">
                                             </div>
                                             <div class="d-flex justify-content-between align-items-center mt-1">
                                                  <button type="submit" onclick="filter(0);" type="button" class="buy rounded-5 px-3" style="font-size: 13px;">FILTER</button>
                                                  <div>
                                                       <span id="minPrice">Rs. 0</span> -
                                                       <span id="maxPrice">Rs. 0</span>
                                                  </div>
                                             </div>
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
                         <div class="col-lg-9 col-12">
                              <div class="row">
                                   <div class="col-12 d-flex justify-content-between align-items-center">

                                        <span class="d-lg-block d-none">Results</span>

                                        <div class="srt-select">
                                             <select id="sort_price" name="sort" onchange="sort_by_prices(0);">
                                                  <option value="0">Sort by Best match</option>
                                                  <option value="1">Price: high to low</option>
                                                  <option value="2">Price: low to high</option>
                                             </select>
                                        </div>

                                        <!-- Filter mobile ui -->
                                        <div class="d-lg-none" onclick="document.getElementById('sidebar').style.width = '300px';">
                                             <span class="flt"><i class="bi bi-funnel"></i></span>
                                        </div>

                                   </div>
                              </div>
                              <div class="row pt-4 d-flex justify-content-start" id="results">

                                   <?php

                                   $page_number;

                                   if ("0" != $_GET["page"]) {
                                        $page_number = $_GET["page"];
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

                                   if ($selected_book_list == 0) {

                                   ?>

                                        <div class="row">
                                             <div class="col d-flex flex-column align-items-center">
                                                  <img src="././img/not-result-found.jpg" width="150px" />
                                                  <h5 class="mt-5 query-center">No results found.</h5>
                                                  <button onclick="window.location = 'index.php'" style="outline: none; color: black; background-color: #f6f6f6; font-size: 13px; padding:10px 15px; border: none; border-radius: 7px; ">
                                                       Go to homepage
                                                  </button>
                                             </div>
                                        </div>

                                   <?php

                                        exit();
                                   }

                                   for ($x = 0; $x < $selected_book_list; $x++) {
                                        $selected_books_data = $selected_books->fetch_assoc();

                                        $maxLength = 25;
                                        $query = $selected_books_data['title'];

                                        if (strlen($query) > $maxLength) {
                                             $shortendText = substr($query, 0, $maxLength) . '...';
                                        } else {
                                             $shortendText = $query;
                                        }

                                   ?>
                                        <div class="col-6  col-md-4 col-xl-3  py-2 d-flex flex-column" role="button">

                                             <div class="row product_img position-relative">

                                                  <?php

                                                  if (isset($_SESSION['user'])) {

                                                       $email = $_SESSION['user']['email'];

                                                       $watchlist = Database::search("SELECT * FROM `watchlist`  WHERE `books_book_id` = '" . $selected_books_data['book_id'] . "' AND `users_email` = '" . $email . "'");

                                                       $isAddedWatchlist = ($watchlist->num_rows == 1) ? true : false;
                                                  }

                                                  ?>

                                                  <span onclick="book_add_to_wishlist(<?= $selected_books_data['book_id'] ?>);" class="hrt-icn shadow-lg" style="right: 25px;top: 10px;">
                                                       <i id="heart<?= $selected_books_data['book_id'] ?>" class="bi bi-heart <?= $isAddedWatchlist ? 'text-danger' : '' ?>"></i>
                                                  </span>

                                                  <span <?php if ($selected_books_data['qty'] > 0) { ?> onclick="book_add_cart(<?= $selected_books_data['book_id'] ?> , <?= $selected_books_data['qty']; ?>);" <?php } ?> style="position: absolute;top: 55px;right: 25px;" class="bg-white cart-icon shadow-lg">
                                                       <i class="bi bi-bag"></i>
                                                  </span>


                                                  <div class="col-12 text-center">

                                                       <?php
                                                       $bookImageResult = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $selected_books_data["book_id"] . "' ");
                                                       $booksImagedata = $bookImageResult->fetch_assoc();
                                                       $issetImage = ($bookImageResult->num_rows > 0) ? $booksImagedata["img_path"] : "./img/not-found.png";
                                                       ?>

                                                       <img onclick="window.location='./book.php?id=<?= $selected_books_data['book_id'] ?>' ;"
                                                            src="<?= $issetImage ?>" alt="book-image" width="100%" height="100%" class="rounded-2">
                                                  </div>

                                             </div>

                                             <div class="pt-2" onclick="window.location='./book.php?id=<?= $selected_books_data['book_id'] ?>' ;">
                                                  <p class="fw-medium" style="font-size: 16px;"><?= $shortendText ?></p>
                                                  <p class="author_display">Author, <?= $selected_books_data['author_name'] ?></p>
                                                  <h6 style="margin:5px 0;">RS.<?= $selected_books_data['price'] ?>.00</h6>
                                                  <p style="font-size: 13px;" class="query-muted"><?= ($selected_books_data['qty'] == 0) ? "Out Of Stock" : "" ?></p>
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


     <script src="./js/bootstrap.bundle.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
     <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/yesiamrocks/cssanimation.io@1.0.3/letteranimation.min.js"></script>
     <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
</body>

</html>