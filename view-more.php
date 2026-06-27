<?php

include './connection.php';

if (!empty($_GET['category'])) $category = $_GET['category'];
else header("location:index.php");

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
     <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
     <title>ReadHaven</title>
</head>

<body>
     <?php require './components/header.php' ?>

     <div class="container">
          <div class="row mt-4">

               <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div id="liveToast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
                         <div class="d-flex align-items-center">
                              <div class="toast-body">
                                   <span style="padding-left: 5px;color: black; font-size: 14px;" id="response-text"></span>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="row pt-3">
                    <div class="col-12">
                         <a href="index.php" style="padding-right: 6px;"><i class="fa-solid fa-arrow-left text-black"></i></a>
                         <a href="index.php" class="fw-medium text-black">Back</a>
                    </div>
               </div>

               <?php
               $categoeyResult = Database::search("SELECT * FROM `book_category` WHERE category_id = $category");
               $categoryData = $categoeyResult->fetch_assoc();
               ?>

               <div class="row py-4">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                         <h4 class="fw-medium"><?php echo $categoryData["category_name"]; ?></h4>
                    </div>
               </div>

               <?php

               $categoryNameResult = Database::search("SELECT * FROM `books` INNER JOIN `book_category` ON `books`.`book_category_id` =  `book_category`.`category_id`   WHERE `book_category_id` = '" . $categoryData["category_id"] . "' ");
               $bookResult = Database::search("SELECT * FROM `books`  INNER JOIN `author_name` ON `books`.`author_name_id` = `author_name`.`id` WHERE `book_category_id` = '" . $categoryData["category_id"] . "' AND `active_active_id` = '1' ORDER BY `datetime_added` DESC");
               $books = $bookResult->num_rows;
               $categoryNameResultData = $categoryNameResult->fetch_assoc();

               if ($books > 0) {

                    for ($y = 0; $y < $books; $y++) {
                         $book_data = $bookResult->fetch_assoc();
                         $maxLength = 25;
                         $text = $book_data['title'];

                         if (strlen($text) > $maxLength) {
                              $shortenedText = substr($text, 0, $maxLength) . '...';
                         } else {
                              $shortenedText = $text;
                         }
               ?>
                         <div class="col-6 col-md-3 col-xl-2  py-2 d-flex flex-column" role=button>

                              <div class="row product_img position-relative">

                                   <?php

                                   if (isset($_SESSION['user'])) {

                                        $email = $_SESSION['user']['email'];

                                        $watchlist = Database::search("SELECT * FROM `watchlist`  WHERE `books_book_id` = '" . $book_data['book_id'] . "' AND `users_email` = '" . $email . "'");

                                        $isAddedWatchlist = ($watchlist->num_rows == 1) ? true : false;
                                   }

                                   ?>


                                   <span onclick="book_add_to_wishlist(<?= $book_data['book_id'] ?>);" class="hrt-icn shadow-lg" style="right: 20px;top: 10px;">
                                        <i id="heart<?= $book_data['book_id'] ?>" class="bi bi-heart <?= $isAddedWatchlist ? 'text-danger' : '' ?>"></i>
                                   </span>

                                   <span <?php if ($book_data['qty'] > 0) { ?> onclick="book_add_cart(<?= $book_data['book_id'] ?> , <?= $book_data['qty']; ?>);" <?php } ?> style="position: absolute;top: 55px;right: 20px;" class="bg-white cart-icon shadow-lg">
                                        <i class="bi bi-bag"></i>
                                   </span>


                                   <div class="col-12 text-center">

                                        <?php

                                        $bookImageResult = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $book_data["book_id"] . "' ");
                                        $booksImagedata = $bookImageResult->fetch_assoc();
                                        $issetImage = ($bookImageResult->num_rows > 0) ? $booksImagedata["img_path"] : "./img/not-found.png";

                                        ?>

                                        <img onclick="window.location='./book.php?id=<?= $book_data['book_id'] ?>' ;"
                                             src="<?= $issetImage ?>" alt="book-image" width="200px" height="300px" class="rounded-2">
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
                    <span>No Items</span>
               <?php
               }

               ?>
          </div>
     </div>

     <?php require './components/footer.php' ?>

     <script src="./js/script.js"></script>
     <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>