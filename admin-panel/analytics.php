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
  <link href="https://cdn.jsdelivr.net/gh/yesiamrocks/cssanimation.io@1.0.3/cssanimation.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
  <title>ReadHaven | Analytics</title>
</head>

<body>

  <?php include '../components/admin-sidebar.php' ?>

  <section class="home-section">

    <?php include '../components/admin-header.php' ?>

    <div class="container-fluid py-3">

      <nav class="pt-4 pb-3 ps-3" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="./main-panel.php">Main Panel</a></li>
          <li class="breadcrumb-item" aria-current="page">Analytics</li>
        </ol>
      </nav>

      <div class="row ps-3 pb-4">
        <div class="col">
          <h4>Analytics</h4>
        </div>
      </div>


      <div class="row ps-3">
        <div class="col-12">

          <div class="row">

            <?php include '../charts/book-sales-by-category.php'; ?>

            <?php include '../charts/overall-rating-distribution.php'; ?>

            <?php include '../charts/top-performing-authors.php'; ?>

            <div class="row">
              <div class="col-7">
                <?php include "../charts/monthly-revenue.php"; ?>
              </div>
            </div>

          </div>

        </div>
      </div>

    </div>

  </section>

  <script src="../js/script.js"></script>
  <script src="../js/bootstrap.bundle.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/yesiamrocks/cssanimation.io@1.0.3/letteranimation.min.js"></script>

</body>

</html>