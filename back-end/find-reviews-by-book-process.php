<?php

include '../connection.php';

if (isset($_POST['value'])) {

  $value = $_POST["value"];
  $reviews_srch_query = "SELECT * FROM feedback INNER JOIN books ON feedback.feed_book_id = books.book_id INNER JOIN users ON feedback.users_email = users.email WHERE books.title LIKE '%" . $value . "%' OR users.first_name LIKE '%" . $value . "%' OR users.last_name LIKE '%" . $value . "%'";
  $rslt = Database::search($reviews_srch_query);

  if ($rslt->num_rows > 0) {
?>

    <div class="col-12">
      <div class="mt-4">
        <table class="table px-2 py-3">
      </div>
      <thead>
        <tr class="horizontal-line-table">
          <th>
            PRODUCT

          </th>
          <th>
            CUSTOMER

          </th>
          <th>
            RATING

          </th>
          <th>
            REVIEW

          </th>
          <th>
            DATE

          </th>
        </tr>
      </thead>
      <tbody>
        <?php

        $review_page_number = 1; // Properly initialized

        if (isset($_GET['pageReview'])) {
          $review_page_number = $_GET['pageReview'];
        }

        $reviews = Database::search($reviews_srch_query);

        $per_page_review = 10;

        $pages_reviews = ceil($reviews->num_rows / $per_page_review);

        $page_results_review = ($review_page_number - 1) * $per_page_review;

        $selected_reviews = Database::search($reviews_srch_query . " LIMIT " . $per_page_review . " OFFSET " . $page_results_review);

        if ($selected_reviews && $selected_reviews->num_rows > 0) {
          while ($review_data = $selected_reviews->fetch_assoc()) {
        ?>
            <tr class="horizontal-line">
              <td style="width: 280px;">

                <span class="text-black" style="font-size: 14px;font-weight: 500;">
                  <?php
                  $title = $review_data['title'];
                  if (strlen($title) > 30) {
                    $title = substr($title, 0, 30) . '...';
                  }
                  echo $title;
                  ?>
                </span>
              </td>

              <td style="width: 300px;">

                <span class="customer-name">

                  <?php

                  $img_data = null;
                  $customer_img = Database::search("SELECT * FROM `profile_img` WHERE `users_email` = '" . $review_data['users_email'] . "'");

                  if ($customer_img && $customer_img->num_rows > 0) {
                    $img_data = $customer_img->fetch_assoc();
                  }
                  ?>

                  <img height="40" width="40" class="customer-img" src="<?= (isset($img_data) && !empty($img_data['img_path'])) ?  "../" . $img_data['img_path'] : "../img/profile.png"; ?>" />

                  <?= $review_data['first_name'] . " " . $review_data['last_name'] ?>

                </span>

              </td>
              <td class="rating">

                <?php

                $yello_start = (int)$review_data['rating'];
                $gray_star = 5 - $yello_start;

                for ($i = 0; $i < $yello_start; $i++) echo '<i class="bi bi-star-fill text-warning pe-1"></i>';
                for ($i = 0; $i < $gray_star; $i++) echo '<i class="bi bi-star text-warning pe-1"></i>';

                ?>

              </td>
              <td class="review-text" style="width: 800px;">
                <?= substr($review_data['feedback_msg'], 0, 100) . "..." ?>
              </td>
              <td>
                <span style="font-size: 14px;color: black;">
                  <?= $review_data['feed_date']; ?>
                </span>
              </td>
            </tr>

          <?php } ?>

        <?php } else { ?>

          <tr>
            <td colspan="5" class="text-center py-5">
              <span style="font-size: 14px;color: black;">No Reviews Found</span>
            </td>
          </tr>

        <?php } ?>

      </tbody>
      </table>
      <nav aria-label="Page navigation example mt-3">
        <ul class="pagination justify-content-center gap-1">
          <li class="page-item">
            <a class="page-link page-link-prnv" href="
                                            <?php

                                            if ($review_page_number <= 1) {
                                              echo "#";
                                            } else {
                                              echo "?pageReview=" . ($review_page_number - 1);
                                            }
                                            ?>"
              aria-label="Previous">
              <i class="bi bi-arrow-left-short fs-6"></i>
            </a>
          </li>
          <?php
          for ($x = 1; $x <= $pages_reviews; $x++) {
            if ($review_page_number == $x) {
          ?>
              <li class="page-item">
                <a class="page-link page-active fs-6" href="<?= "?pageReview=" . ($x); ?>">
                  <?= $x; ?>
                </a>
              </li>
            <?php
            } else {
            ?>
              <li class="page-item">
                <a class="page-link none-active fs-6" href="<?= "?pageReview=" . ($x); ?>">
                  <?= $x; ?>
                </a>
              </li>
          <?php
            }
          }
          ?>
          <li class="page-item">
            <a class="page-link page-link-prnv" href="
                                            <?php
                                            if ($review_page_number >= $pages_reviews || $pages_reviews == 0) {
                                              echo "#";
                                            } else {
                                              echo "?pageReview=" . ($review_page_number + 1);
                                            }
                                            ?>
                                            " aria-label=" Next">
              <i class="bi bi-arrow-right-short fs-6"></i>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    </div>

<?php }
}
