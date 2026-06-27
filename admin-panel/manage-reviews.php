<?php

session_start();
include '../connection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
  <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
  <title>ReadHaven | Manage Reviews</title>
</head>

<body>

  <?php include '../components/admin-sidebar.php' ?>

  <section class="home-section">

    <?php include '../components/admin-header.php' ?>

    <div class="container-fluid py-3">

      <nav class="pt-4 pb-3 ps-3" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="./main-panel.php">Main Panel</a></li>
          <li class="breadcrumb-item" aria-current="page">Reviews Management</li>
          <li class="breadcrumb-item" aria-current="page">Manage Reviews</li>
        </ol>
      </nav>

      <div class="row ps-3">
        <div class="col">
          <h4>Reviews Management</h4>
        </div>
      </div>

      <div class="row">

        <div class="col-12">

          <div class="card" style="border: none;">
            <div class="card-body">

              <div class="row">

                <div class="col-12">
                  <h6>Filters</h6>
                </div>

                <div class="col-12 py-3 d-flex align-items-center justify-content-start">
                  <div class="d-flex gap-3 justify-content-evenly align-items-center">
                    <input class="ap-input" type="text" placeholder="Search by Book Title, Customer Name..." id="bookValue" />
                    <button onclick="find_reviews_by_book(0);" class="ap-btn-clr">Find Review</button>
                  </div>
                </div>
              </div>

              <div class="row" id="reviewsSection">
                <div class="col-12">
                  <div class="mt-4">
                    <table class="table px-2 py-3">
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

                        $review_query = "SELECT * FROM feedback INNER JOIN books ON feedback.feed_book_id = books.book_id INNER JOIN users ON users.email = feedback.users_email ORDER BY feedback.feed_date DESC";

                        $review_page_number = 1; // Properly initialized

                        if (isset($_GET['pageReview'])) {
                          $review_page_number = $_GET['pageReview'];
                        }

                        $reviews = Database::search($review_query);

                        $per_page_review = 10;

                        $pages_reviews = ceil($reviews->num_rows / $per_page_review);

                        $page_results_review = ($review_page_number - 1) * $per_page_review;

                        $selected_reviews = Database::search($review_query . " LIMIT " . $per_page_review . " OFFSET " . $page_results_review);

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

                                  <!-- <span style="font-size: 14px;color: black;">
                                    <?= $review_data['title'] ?>
                                  </span> -->

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