<div class="row my-5 py-3">

     <div class="col-12">
          <div class="row">
               <div class="col-12 col-md-5 d-flex flex-column justify-content-center">
                    <div class="new-keyword w-25">Newly added</div>
                    <h1 class="fw-bold">Latest Arrivals, Unlimited Possibilities!</h1>
                    <div class="cstom-bg mb-3 mt-3">
                         <span>
                              "Step into a world of fresh ideas and exciting tales with our newest book collection. Begin reading today!"
                         </span>
                    </div>
                    <div class="cstom-bg mb-3">
                         <span>
                              "Browse our latest book collection and discover stories that entertain, inspire, and transform your reading experience."
                         </span>
                    </div>
               </div>

               <div class="col-12 col-md-7 d-flex gap-4 overflow-auto mt-3 mt-md-0">
                    <?php

                    $newly_added_books = Database::search("SELECT * FROM books INNER JOIN book_img ON books.book_id = book_img.book_id ORDER BY books.datetime_added DESC LIMIT 10");

                    for ($i = 0; $i < $newly_added_books->num_rows; $i++) {

                         $book_data = $newly_added_books->fetch_assoc();
                         $maxLength = 15;
                         $text = $book_data['title'];

                         if (strlen($text) > $maxLength) $shortenedText = substr($text, 0, $maxLength) . '...';
                         else $shortenedText = $text;

                    ?>
                         <div onclick="window.location='book.php?id=<?= $book_data['book_id'] ?>'" style="width: 200px;" role="button">
                              <img src="<?= $book_data['img_path'] ?>" class="rounded-3" width="210px" height="300px">
                              <h5 class="text-black pt-1"><?= $shortenedText ?></h5>
                              <h6 class="text-black fw-medium">Rs.<?= $book_data['price'] ?>.00</h6>
                         </div>

                    <?php

                    }

                    ?>

               </div>
          </div>
     </div>
</div>