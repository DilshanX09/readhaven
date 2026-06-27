<?php

session_start();

include './connection.php';

if (isset($_GET['bid'])) {
     $book_id = $_GET['bid'];
     $email = $_GET['uid'];
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
          <title>ReadHaven | Review Order</title>
     </head>

     <body>

          <?php include './components/header.php' ?>

          <div class="container py-5">
               <?php
               $review_book_result = Database::search("SELECT * FROM books RIGHT JOIN book_img ON books.book_id = book_img.book_id RIGHT JOIN invoice ON books.book_id = invoice.book_id WHERE  invoice.book_id = '$book_id' AND invoice.users_email = '$email'");
               $data = $review_book_result->fetch_assoc();
               ?>
               <div class="row">
                    <div class="col-12">
                         <div class="row">

                              <div class="col-12 d-flex  align-items-center gap-2" style="cursor: pointer;" onclick="return_to_order_history_url();">
                                   <p><i class="fa-solid fa-arrow-left text-black"></i></p>
                                   <p class="fw-medium text-black">Back</p>
                              </div>

                         </div>

                         <div class="row mt-5 d-none" id="feedscc">
                              <div class="col-12 d-flex justify-content-center">
                                   <div class="row" style="padding: 35px;">
                                        <div class="col-12 d-flex flex-column align-items-center gap-4">
                                             <img src="./img/success.png" width="100px" />
                                             <span class="text-black">Feedback send Successfully Complete!</span>
                                        </div>
                                   </div>
                              </div>
                         </div>


                         <div id="feedView" class="d-block">
                              <div class="row pt-4">
                                   <div class="col-12">
                                        <h1 class="fs-4">Write a product review</h1>
                                   </div>
                              </div>

                              <div class="row">
                                   <div class="col-12">
                                        <span>Delivered <?php echo $data["date"] ?></span>
                                   </div>
                                   <div class="col-12">
                                        <span style="font-size: 13px;" class="text-black">Rate and review purchased product</span>
                                   </div>
                              </div>
                              <div class="mt-3 d-md-flex gap-5 rounded-2 px-3 py-4">
                                   <div class="d-flex d-md-block justify-content-center justify-content-md-start">
                                        <img src="<?php echo $data["img_path"] ?>" alt="image" width="200px" class="rounded-2">
                                   </div>
                                   <div class="pt-4 pt-md-0">
                                        <h4><?php echo $data["title"] ?></h4>
                                        <div class="mb-4">
                                             <label>
                                                  How would you describe your mood after using our product for the first time?
                                             </label>
                                             <div class="emoji-group pt-2">
                                                  <label for="smile">
                                                       <input type="radio" id="smile" name="mood" hidden />
                                                       <img alt="Happy face emoji" class="emoji" height="40" src="./img/modes/smile.png" width="40" />
                                                  </label>
                                                  <label for="normal">
                                                       <input type="radio" type="radio" hidden id="normal" name="mood" />
                                                       <img alt="Neutral face emoji" class="emoji" height="40" src="./img/modes/neutral-face.png" width="40" />
                                                  </label>
                                                  <label for="bad">
                                                       <input type="radio" type="radio" hidden id="bad" name="mood" />
                                                       <img alt="Sad face emoji" class="emoji" height="40" src="./img/modes/sad-face.png" width="40" />
                                                  </label>
                                             </div>
                                        </div>
                                        <div class="mb-4">
                                             <label class="mb-3">
                                                  How would you rate the quality of our product?
                                             </label>
                                             <div class="rating-group d-flex">
                                                  <input type="radio" id="star1" checked hidden name="rating">
                                                  <label for="star1" class="rating">
                                                       1
                                                  </label>
                                                  <input type="radio" id="star2" hidden name="rating">
                                                  <label for="star2" class="rating">
                                                       2
                                                  </label>
                                                  <input type="radio" id="star3" hidden name="rating">
                                                  <label for="star3" class="rating">
                                                       3
                                                  </label>
                                                  <input type="radio" id="star4" hidden name="rating">
                                                  <label for="star4" class="rating">
                                                       4
                                                  </label>
                                                  <input type="radio" id="star5" hidden name="rating">
                                                  <label for="star5" class="rating">
                                                       5
                                                  </label>

                                             </div>
                                        </div>
                                        <div class="mb-4">
                                             <label class="mb-3">
                                                  Which feature is the best for you?
                                             </label>
                                             <div class="form-check">
                                                  <input class="form-check-input border-dark-subtle" id="feature1" name="fr" type="checkbox" onclick="select_review_category(this)" />
                                                  <label class="form-check-label" for="feature1">
                                                       Customer service & delivery service
                                                  </label>
                                             </div>
                                             <div class="form-check">
                                                  <input class="form-check-input border-dark-subtle" id="feature2" name="fr" type="checkbox" onclick="select_review_category(this)" />
                                                  <label class="form-check-label" for="feature2">
                                                       The advanced search functionality
                                                  </label>
                                             </div>
                                             <div class="form-check">
                                                  <input class="form-check-input border-dark-subtle" id="feature3" name="fr" type="checkbox" onclick="select_review_category(this)" />
                                                  <label class="form-check-label" for="feature3">
                                                       Books high quality & highly recommended
                                                  </label>
                                             </div>
                                             <div class="form-check">
                                                  <input class="form-check-input border-dark-subtle" id="feature4" name="fr" type="checkbox" onclick="select_review_category(this)" />
                                                  <label class="form-check-label" for="feature3">
                                                       Other
                                                  </label>
                                             </div>
                                        </div>
                                        <div class="mb-2">
                                             <label for="feedback" class="mb-3">
                                                  Your feedback
                                             </label>
                                             <textarea id="feedback" class="w-100 px-2 py-2 rounded-2" placeholder="Anything you'd like to add? Your input is valuable to us" rows="4"></textarea>
                                        </div>
                                        <div class="row">
                                             <div class="col-12 text-end">
                                                  <div>
                                                       <span class="text-danger" style="font-size: 13px;" id="review-error"></span>
                                                       <button class="btn bg-dark text-white" type="button" onclick="write_book_review(<?php echo $data['book_id']; ?>);">
                                                            Send Feedback
                                                       </button>
                                                  </div>
                                             </div>
                                        </div>

                                   </div>

                              </div>
                         </div>

                    </div>
               </div>
          </div>



          <?php include './components/footer.php' ?>

          <script>
               document.querySelectorAll('.emoji').forEach(item => {
                    item.addEventListener('click', event => {
                         document.querySelectorAll('.emoji').forEach(emoji => {
                              emoji.classList.remove('selected');
                         });
                         item.classList.add('selected');
                    });
               });

               document.querySelectorAll('.rating').forEach(item => {
                    item.addEventListener('click', event => {
                         document.querySelectorAll('.rating').forEach(rating => {
                              rating.classList.remove('active');
                         });
                         item.classList.add('active');
                    });
               });
          </script>
          <script src="./js/script.js"></script>
          <script src="./js/bootstrap.bundle.js"></script>
     </body>

     </html>
<?php
} else {
     header('Location:order-history.php');
}


?>