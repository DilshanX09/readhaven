<?php

session_start();

include "../connection.php";

if (empty($_SESSION['ADMIN_UID'])) {
    header("Location: ../admin-auth/admin-authentication.php?redirect_uri=main-panel");
}

function greeting()

{
    date_default_timezone_set('Asia/Colombo');

    $hour = date('H');
    $greeting = "Unknown";

    if ($hour >= 5 && $hour < 12) {
        $greeting = "Good Morning";
    } elseif ($hour >= 12 && $hour < 17) {
        $greeting = "Good Afternoon";
    } elseif ($hour >= 17 && $hour < 21) {
        $greeting = "Good Evening";
    } else {
        $greeting = "Good Night";
    }

    return $greeting;
}

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
    <link href="https://cdn.jsdelivr.net/gh/yesiamrocks/cssanimation.io@1.0.3/cssanimation.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
    <title>ReadHaven | Admin Dashboard</title>
</head>

<body>

    <?php include '../components/admin-sidebar.php' ?>

    <section class="home-section">

        <?php include '../components/admin-header.php' ?>

        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <h4>Dashboard</h4>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        <div class="d-flex align-items-center gap-3">

                            <div>

                                <h2 class="" style="margin-bottom: 0rem;">
                                    <?= greeting(); ?>
                                </h2>

                                <Span class="fs-13">Welcome , <span style="font-size: 15px;" class="txt-primary"><?= $_SESSION['ADMIN_UID']['admin_fname'] . " " . $_SESSION['ADMIN_UID']['admin_lname'] ?> </span></Span>
                            </div>

                        </div>
                        <p onclick="location.href='./book-add.php';" style="background-color: #f6f6f6;font-size: 14px;cursor: pointer;" class="px-3 py-2 rounded-2 d-flex justify-content-center align-items-center text-decoration-none">
                            Register Books
                        </p>
                    </div>
                </div>
            </div>
            <div class="row mt-4 pb-4">
                <div class="col-12 d-flex px-2 py-2 gap-2" style="overflow-x: scroll; text-wrap: nowrap;scrollbar-width: none;">

                    <?php

                    $today = date("Y-m-d");
                    $thisMonth = date("m");
                    $thisYear = date("Y");

                    $dailyEarnings = 0;
                    $monthlyEarnings = 0;
                    $todaySellings = 0;
                    $monthlySellings = 0;
                    $totalSellings = 0;
                    $revenue = 0;


                    $invoice_srch = Database::search("SELECT * FROM invoice");

                    for ($i = 0; $i < $invoice_srch->num_rows; $i++) {
                        $invoice_data = $invoice_srch->fetch_assoc();
                        $totalSellings += $invoice_data['invoice_qty'];

                        $date = date("Y-m-d", strtotime($invoice_data['date']));

                        if ($date == $today) {
                            $dailyEarnings += $invoice_data['total'];
                            $todaySellings += $invoice_data['invoice_qty'];
                        }

                        $pMonth = date("m", strtotime($invoice_data['date']));
                        $pYear = date("Y", strtotime($invoice_data['date']));

                        if ($pYear == $thisYear && $pMonth == $thisMonth) {
                            $monthlyEarnings += $invoice_data['total'];
                            $monthlySellings += $invoice_data['invoice_qty'];
                        }

                        $revenue += $invoice_data['total'];
                    }


                    ?>

                    <div class="col-xl-2 col-lg-5 col-md-4 col-12 mb-3 rounded-3" style="border: 1px solid #f6f6f6">
                        <div class="card border normal-border-gray borderh-100 rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <p class="text-muted mb-1 text-truncate" style="font-size: 13px;">Revenue</p>
                                        <h5 class="text-dark mb-0">
                                            <?= 'LKR ' . number_format($revenue, 2, '.', ','); ?>
                                        </h5>
                                    </div>
                                    <div class="avatar-md d-flex justify-content-center align-items-center">
                                        <i class="bi bi-currency-dollar fs-4 text-primary"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top border-light py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-1 align-items-center">
                                        <span class="<?= ($revenue > 0) ? 'text-success' : 'text-danger'; ?>">
                                            <i class="<?= ($revenue > 0) ? 'bi bi-arrow-up-right' : 'bi bi-arrow-down-right'; ?>"></i>
                                        </span>
                                        <span class="text-muted text-uppercase" style="font-size: 12px;">Overall</span>
                                    </div>
                                    <a href="#" class="text-decoration-none text-dark" style="font-size: 12px; font-weight: 500;">View More <i class="bi bi-chevron-right" style="font-size: 10px;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-5 col-md-4 col-12 mb-3 rounded-3" style="border: 1px solid #f6f6f6">
                        <div class="card border normal-border-gray h-100 rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <p class="text-muted mb-1 text-truncate" style="font-size: 13px;">Daily Earnings</p>
                                        <h5 class="text-dark mb-0">
                                            <?= 'LKR ' . number_format($dailyEarnings, 2, '.', ','); ?>
                                        </h5>
                                    </div>
                                    <div class="avatar-md d-flex justify-content-center align-items-center">
                                        <i class="bi bi-cash-coin fs-4 text-success"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top border-light py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-1 align-items-center">
                                        <span class="<?= ($dailyEarnings > 0) ? 'text-success' : 'text-danger'; ?>">
                                            <i class="<?= ($dailyEarnings > 0) ? 'bi bi-arrow-up-right' : 'bi bi-arrow-down-right'; ?>"></i>
                                        </span>
                                        <span class="text-muted text-uppercase" style="font-size: 12px;">Overall</span>
                                    </div>
                                    <a href="#" class="text-decoration-none text-dark" style="font-size: 12px; font-weight: 500;">View More <i class="bi bi-chevron-right" style="font-size: 10px;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-5 col-md-4 col-12 mb-3 rounded-3" style="border: 1px solid #f6f6f6">
                        <div class="card border normal-border-gray h-100 rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <p class="text-muted mb-1 text-truncate" style="font-size: 13px;">Monthly Earnings</p>
                                        <h5 class="text-dark mb-0">
                                            <?= 'LKR ' . number_format($monthlyEarnings, 2, '.', ','); ?>
                                        </h5>
                                    </div>
                                    <div class="avatar-md d-flex justify-content-center align-items-center">
                                        <i class="bi bi-calendar-check fs-4 text-info"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top border-light py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-1 align-items-center">
                                        <span class="<?= ($monthlyEarnings > 0) ? 'text-success' : 'text-danger'; ?>">
                                            <i class="<?= ($monthlyEarnings > 0) ? 'bi bi-arrow-up-right' : 'bi bi-arrow-down-right'; ?>"></i>
                                        </span>
                                        <span class="text-muted text-uppercase" style="font-size: 12px;">Overall</span>
                                    </div>
                                    <a href="#" class="text-decoration-none text-dark" style="font-size: 12px; font-weight: 500;">View More <i class="bi bi-chevron-right" style="font-size: 10px;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-5 col-md-4 col-12 mb-3 rounded-3" style="border: 1px solid #f6f6f6">
                        <div class="card border normal-border-gray h-100 rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <p class="text-muted mb-1 text-truncate" style="font-size: 13px;">Today Sellings</p>
                                        <h5 class="text-dark mb-0">
                                            <?= $todaySellings ?> <span style="font-size: 13px;" class="text-muted">Items</span>
                                        </h5>
                                    </div>
                                    <div class="avatar-md d-flex justify-content-center align-items-center">
                                        <i class="bi bi-kanban fs-4 text-warning"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top border-light py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-1 align-items-center">
                                        <span class="<?= ($todaySellings > 0) ? 'text-success' : 'text-danger'; ?>">
                                            <i class="<?= ($todaySellings > 0) ? 'bi bi-arrow-up-right' : 'bi bi-arrow-down-right'; ?>"></i>
                                        </span>
                                        <span class="text-muted text-uppercase" style="font-size: 12px;">Overall</span>
                                    </div>
                                    <a href="#" class="text-decoration-none text-dark" style="font-size: 12px; font-weight: 500;">View More <i class="bi bi-chevron-right" style="font-size: 10px;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-5 col-md-4 col-12 mb-3 rounded-3" style="border: 1px solid #f6f6f6">
                        <div class="card border normal-border-gray h-100 rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <p class="text-muted mb-1 text-truncate" style="font-size: 13px;">Monthly Sellings</p>
                                        <h5 class="text-dark mb-0">
                                            <?= $monthlySellings ?> <span style="font-size: 13px;" class="text-muted">Items</span>
                                        </h5>
                                    </div>
                                    <div class="avatar-md d-flex justify-content-center align-items-center">
                                        <i class="bi bi-person-lines-fill fs-4 text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top border-light py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <?php if ($thisMonth == $pMonth) { ?>
                                        <div class="d-flex gap-1 align-items-center">
                                            <span class="<?= ($monthlySellings > 0) ? 'text-success' : 'text-danger'; ?>">
                                                <i class="<?= ($monthlySellings > 0) ? 'bi bi-arrow-up-right' : 'bi bi-arrow-down-right'; ?>"></i>
                                            </span>
                                            <span class="text-muted text-uppercase" style="font-size: 12px;">Overall</span>
                                        </div>
                                    <?php } else { ?>
                                        <div></div>
                                    <?php } ?>
                                    <a href="#" class="text-decoration-none text-dark" style="font-size: 12px; font-weight: 500;">View More <i class="bi bi-chevron-right" style="font-size: 10px;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-5 col-md-4 col-12 mb-3 rounded-3" style="border: 1px solid #f6f6f6">
                        <div class="card border normal-border-gray h-100 rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <p class="text-muted mb-1 text-truncate" style="font-size: 13px;">Total Sellings</p>
                                        <h5 class="text-dark mb-0">
                                            <?= $totalSellings ?> <span style="font-size: 13px;" class="text-muted">Items</span>
                                        </h5>
                                    </div>
                                    <div class="avatar-md d-flex justify-content-center align-items-center">
                                        <i class="bi bi-box-arrow-in-down fs-4" style="color: #6f42c1;"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top border-light py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-1 align-items-center">
                                        <span class="<?= ($totalSellings > 0) ? 'text-success' : 'text-danger'; ?>">
                                            <i class="<?= ($totalSellings > 0) ? 'bi bi-arrow-up-right' : 'bi bi-arrow-down-right'; ?>"></i>
                                        </span>
                                        <span class="text-muted text-uppercase" style="font-size: 12px;">Overall</span>
                                    </div>
                                    <a href="#" class="text-decoration-none text-dark" style="font-size: 12px; font-weight: 500;">View More <i class="bi bi-chevron-right" style="font-size: 10px;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-5 col-md-4 col-12 mb-3 rounded-3" style="border: 1px solid #f6f6f6">
                        <div class="card border normal-border-gray h-100 rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <p class="text-muted mb-1 text-truncate" style="font-size: 13px;">Total Users</p>
                                        <?php $user = Database::search("SELECT * FROM `users`"); ?>
                                        <h5 class="text-dark mb-0">
                                            <?= $user->num_rows; ?> <span style="font-size: 13px;" class="text-muted">USERS</span>
                                        </h5>
                                    </div>
                                    <div class="avatar-md d-flex justify-content-center align-items-center">
                                        <i class="bi bi-people fs-4 text-danger"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top border-light py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-1 align-items-center">
                                        <span class="<?= ($user->num_rows > 0) ? 'text-success' : 'text-danger'; ?>">
                                            <i class="<?= ($user->num_rows > 0) ? 'bi bi-arrow-up-right' : 'bi bi-arrow-down-right'; ?>"></i>
                                        </span>
                                        <span class="text-muted text-uppercase" style="font-size: 12px;">Overall</span>
                                    </div>
                                    <a href="#" class="text-decoration-none text-dark" style="font-size: 12px; font-weight: 500;">View More <i class="bi bi-chevron-right" style="font-size: 10px;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-md-2 normal-border-gray rounded-3">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md d-flex justify-content-center align-items-center" style="padding: 10px 20px;">
                                                    <i class="bi bi-currency-dollar fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 d-flex flex-column justify-content-end align-items-end">
                                        <p class="text-muted mb-0 text-truncate" style="font-size: 13px;">Revenue</p>
                                        <h4 class="text-dark mt-1 mb-0"><span style="font-size: 14px;color: black;">RS. </span><?= $revenue ?><span style="font-size: 14px;color: black;"> .00</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer m-1 border-0 py-2 bg-light bg-opacity">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="<?= ($revenue > 0) ? 'icn-ap-good' : 'icn-ap-bad'; ?>">
                                            <i class="<?= ($revenue > 0) ? 'bi bi-arrow-up-right text-success' : 'bi bi-arrow-down-right text-danger'; ?>">
                                            </i>
                                        </span>
                                        <span class="text-black ms-1 fs-12 text-uppercase" style="font-size: 13px;">overall
                                        </span>
                                    </div>
                                    <div>
                                        <span style="font-size: 13px;color: black;">View More</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="col-md-2 normal-border-gray rounded-3">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md d-flex justify-content-center align-items-center" style="padding: 10px 20px;">
                                                    <i class="bi bi-cash-coin fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 d-flex flex-column justify-content-end align-items-end">
                                        <p class="text-muted mb-0 text-truncate" style="font-size: 13px;">Daily Earnings</p>
                                        <h4 class="text-dark mt-1 mb-0"><span style="font-size: 14px;color: black;">RS. </span><?= $dailyEarnings ?><span style="font-size: 14px;color: black;"> .00</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer m-1 border-0 py-2 bg-light bg-opacity">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="<?= ($dailyEarnings > 0) ? 'icn-ap-good' : 'icn-ap-bad'; ?>">
                                            <i class="<?= ($dailyEarnings > 0) ? 'bi bi-arrow-up-right text-success' : 'bi bi-arrow-down-right text-danger'; ?>">
                                            </i>
                                        </span>
                                        <span class="text-black ms-1 fs-12 text-uppercase" style="font-size: 13px;">overall
                                        </span>
                                    </div>
                                    <div>
                                        <span style="font-size: 13px;color: black;">View More</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                    <div class="col-md-2 normal-border-gray rounded-3">

                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md d-flex justify-content-center align-items-center" style="padding: 10px 20px;">
                                                    <i class="bi bi-calendar-check fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 d-flex flex-column justify-content-end align-items-end">
                                        <p class="text-muted mb-0 text-truncate" style="font-size: 13px;">Monthly Earnings</p>
                                        <h4 class="text-dark mt-1 mb-0"><span style="font-size: 14px;color: black;">RS. </span><?= $monthlyEarnings ?><span style="font-size: 14px;color: black;"> .00</span></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer m-1 border-0 py-2 bg-light bg-opacity">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="<?= ($monthlyEarnings > 0) ? 'icn-ap-good' : 'icn-ap-bad'; ?>">
                                            <i class="<?= ($monthlyEarnings > 0) ? 'bi bi-arrow-up-right text-success' : 'bi bi-arrow-down-right text-danger'; ?>">
                                            </i>
                                        </span>
                                        <span class="text-black ms-1 fs-12 text-uppercase" style="font-size: 13px;">overall
                                        </span>
                                    </div>
                                    <div>
                                        <span style="font-size: 13px;color: black;">View More</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 normal-border-gray rounded-3">

                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md d-flex justify-content-center align-items-center" style="padding: 10px 20px;">
                                                    <i class="bi bi-kanban fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 d-flex flex-column justify-content-end align-items-end">
                                        <p class="text-muted mb-0 text-truncate" style="font-size: 13px;">Today Sellings</p>
                                        <h4 class="text-dark mt-1 mb-0"><?= $todaySellings ?> <span style="font-size: 14px;color: black;">Items</span></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer m-1 border-0 py-2 bg-light bg-opacity">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="<?= ($todaySellings > 0) ? 'icn-ap-good' : 'icn-ap-bad'; ?>">
                                            <i class="<?= ($todaySellings > 0) ? 'bi bi-arrow-up-right text-success' : 'bi bi-arrow-down-right text-danger'; ?>">
                                            </i>
                                        </span>
                                        <span class="text-black ms-1 fs-12 text-uppercase" style="font-size: 13px;">overall
                                        </span>
                                    </div>
                                    <div>
                                        <span style="font-size: 13px;color: black;">View More</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 normal-border-gray rounded-3">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md d-flex justify-content-center align-items-center" style="padding: 10px 20px;">
                                                    <i class="bi bi-person-lines-fill fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 d-flex flex-column justify-content-end align-items-end">
                                        <p class="text-muted mb-0 text-truncate" style="font-size: 13px;">Monthly Sellings</p>
                                        <h4 class="text-dark mt-1 mb-0"><?= $monthlySellings ?> <span style="font-size: 14px;color: black;">Items</span></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer m-1 border-0 py-2 bg-light bg-opacity">

                                <?php if ($thisMonth == $pMonth) { ?>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex gap-2 align-items-center">
                                            <span class="<?= ($monthlySellings > 0) ? 'icn-ap-good' : 'icn-ap-bad'; ?>">
                                                <i class="<?= ($monthlySellings > 0) ? 'bi bi-arrow-up-right text-success' : 'bi bi-arrow-down-right text-danger'; ?>">
                                                </i>
                                            </span>
                                            <span class="text-black ms-1 fs-12 text-uppercase" style="font-size: 13px;">overall
                                            </span>
                                        </div>
                                        <div>
                                            <span style="font-size: 13px;color: black;">View More</span>
                                        </div>
                                    </div>

                                <?php } ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 normal-border-gray rounded-3">

                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md d-flex justify-content-center align-items-center" style="padding: 10px 20px;">
                                                    <i class="bi bi-box-arrow-in-down fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 d-flex flex-column justify-content-end align-items-end">
                                        <p class="text-muted mb-0 text-truncate" style="font-size: 13px;">Total Sellings</p>
                                        <h4 class="text-dark mt-1 mb-0"><?= $totalSellings ?> <span style="font-size: 14px;color: black;">Items</span></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer m-1 border-0 py-2 bg-light bg-opacity">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="<?= ($totalSellings > 0) ? 'icn-ap-good' : 'icn-ap-bad'; ?>">
                                            <i class="<?= ($totalSellings > 0) ? 'bi bi-arrow-up-right text-success' : 'bi bi-arrow-down-right text-danger'; ?>">
                                            </i>
                                        </span>
                                        <span class="text-black ms-1 fs-12 text-uppercase" style="font-size: 13px;">overall
                                        </span>
                                    </div>
                                    <div>
                                        <span style="font-size: 13px;color: black;">View More</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 normal-border-gray rounded-3">
                        <div class="card border-0">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md d-flex justify-content-center align-items-center" style="padding: 10px 20px;">
                                                    <i class="bi bi-people fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 d-flex flex-column justify-content-end align-items-end">
                                        <p class="text-muted mb-0 text-truncate" style="font-size: 13px;" style="font-size: 13px;">Total Users</p>
                                        <?php
                                        $user = Database::search("SELECT * FROM `users`");
                                        ?>
                                        <h4 class="text-dark mt-1 mb-0"><?= $user->num_rows; ?> <span style="font-size: 14px;color: black;">USERS</span></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer m-1 border-0 py-2 bg-light bg-opacity">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="<?= ($user->num_rows > 0) ? 'icn-ap-good' : 'icn-ap-bad'; ?>">
                                            <i class="<?= ($user->num_rows > 0) ? 'bi bi-arrow-up-right text-success' : 'bi bi-arrow-down-right text-danger'; ?>">
                                            </i>
                                        </span>
                                        <span class="text-black ms-1 fs-12 text-uppercase" style="font-size: 13px;">overall
                                        </span>
                                    </div>
                                    <div>
                                        <span style="font-size: 13px;color: black;">View More</span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div> -->


                </div>
            </div>

            <div class="row">

                <div class="row d-flex align-items-stretch">

                    <?php include '../charts/book-sales-by-category.php'; ?>

                    <?php include '../charts/overall-rating-distribution.php'; ?>

                    <?php include '../charts/top-performing-authors.php'; ?>

                </div>

                <div class="container-fluid  mb-4">
                    <div class="row">

                        <div class="row">
                            <div class="col-12">
                                <div class="row d-flex">
                                    <div class="col-6">

                                        <?php include "../charts/monthly-revenue.php"; ?>

                                    </div>
                                    <div class="col-6 d-flex flex-column justify-content-between px-2">
                                        <div>
                                            <div class="row py-3 px-1">
                                                <div class="col-12">
                                                    <span style="font-size: 13px; color: black;">Latest Sign up Users</span>
                                                </div>
                                            </div>
                                            <table class="table px-2 py-3 ">
                                                <thead>
                                                    <tr class="horizontal-line-table">
                                                        <th>
                                                            #ID
                                                        </th>
                                                        <th>
                                                            USER
                                                        </th>
                                                        <th>
                                                            MOBILE
                                                        </th>
                                                        <th>
                                                            STATUS
                                                        </th>
                                                        <th class="text-center">
                                                            REGISTERD DATE
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    <?php

                                                    $user_srch_query = "SELECT * FROM `users`";
                                                    $page_number;

                                                    (isset($_GET['page'])) ? $page_number = $_GET['page'] : $page_number = 1;

                                                    $newly_regis_user = Database::search($user_srch_query);
                                                    $results_per_page = 10;
                                                    $number_of_pages = ceil($newly_regis_user->num_rows / $results_per_page);

                                                    $page_results = ($page_number - 1) * $results_per_page;

                                                    $selected_rslt = Database::search($user_srch_query . " LIMIT " . $results_per_page . " OFFSET " . $page_results);

                                                    for ($x = 0; $x < $selected_rslt->num_rows; $x++) {
                                                        $selected_data = $selected_rslt->fetch_assoc();
                                                    ?>
                                                        <tr class="horizontal-line">
                                                            <td>
                                                                <span style="font-size: 13px;color: black;">#<?= $x + 1 ?></span>
                                                            </td>

                                                            <td>
                                                                <span class="customer-name">
                                                                    <?= $selected_data['first_name'] . " " . $selected_data['last_name'] ?>
                                                                </span>
                                                            </td>

                                                            <td>
                                                                <span class="customer-name">
                                                                    <?= $selected_data['mobile']; ?>
                                                                </span>
                                                            </td>

                                                            <td>

                                                                <span class="<?= ($selected_data['active_id'] == 1) ? 'status-active text-success' : 'status-inactive text-danger'; ?>">
                                                                    <?= ($selected_data['active_id'] == 1) ? 'Active' : 'Inactive'; ?>
                                                                </span>

                                                            </td>

                                                            <td class="text-center">
                                                                <span style="font-size: 13px;color: black;"><?= explode(" ", $selected_data['joined_date'])[0]; ?></span>
                                                            </td>

                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="col-12 d-flex flex-column justify-content-between">

                                <table class="table px-2">
                                    <div class="row py-3 px-2">
                                        <div class="col-12">
                                            <span style="font-size: 13px; color: black;">Latest Invoices</span>
                                        </div>
                                    </div>
                                    <thead>
                                        <tr class="horizontal-line-table">
                                            <th>
                                                #INVOICE ID

                                            </th>
                                            <th>
                                                BOOK
                                            </th>
                                            <th>
                                                CUSTOMER
                                            </th>
                                            <th>
                                                TOTAL
                                            </th>
                                            <th>
                                                STATUS
                                            </th>
                                            <th>
                                                DATE
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $invoice_query = "SELECT * FROM `invoice`
                                        INNER JOIN `books` ON invoice.book_id = books.book_id
                                        INNER JOIN `users` ON invoice.users_email = users.email";

                                        $invoice_page_number;

                                        if (isset($_GET['invoice_page'])) {
                                            $invoice_page_number = $_GET['invoice_page'];
                                        } else {
                                            $invoice_page_number = 1;
                                        }

                                        $invoice_rs = Database::search($invoice_query);

                                        $invoice_rs_page = 15;
                                        $invoice_number_page = ceil($invoice_rs->num_rows / $invoice_rs_page);
                                        $page_results_invoice = ($invoice_page_number - 1) * $invoice_rs_page;

                                        $selected_invoice = Database::search($invoice_query . " LIMIT " . $invoice_rs_page . " OFFSET " . $page_results_invoice);

                                        for ($x = 0; $x < $selected_invoice->num_rows; $x++) {
                                            $selected_invoice_data = $selected_invoice->fetch_assoc();
                                        ?>
                                            <tr class="horizontal-line">
                                                <td>
                                                    <span class="text-uppercase" style="font-size: 13px;color: black;">#<?= $selected_invoice_data['order_id'] ?></span>
                                                </td>
                                                <td class="text-truncate text-muted">
                                                    <span style="font-size: 13px;color: black;"><?= $selected_invoice_data['title'] ?></span>
                                                </td>
                                                <td>
                                                    <span style="font-size: 13px;color: black;"><?= $selected_invoice_data['first_name'] . " " . $selected_invoice_data['last_name'] ?></span>
                                                </td>
                                                <td>
                                                    <span style="font-size: 13px;color: black;">RS.<?= $selected_invoice_data['total'] ?>.00</span>
                                                </td>
                                                <td>
                                                    <span style="font-size: 13px;color: black;" class="status-active text-success">Active</span>
                                                </td>
                                                <td class="text-center">
                                                    <span style="font-size: 13px;color: black;"><?= $selected_invoice_data['date'] ?></span>
                                                </td>
                                            </tr>
                                        <?php
                                        }

                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="mt-4">
                                <table class="table px-2 py-3 ">
                                    <div class="row pb-3">
                                        <div class="col-12">
                                            <span style="font-size: 13px; color: black;">Latest Reviews & Customers</span>
                                        </div>
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
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end gap-1">
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
                                            if ($review_page_number >= $pages_reviews) {
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

                <div class="row pb-5">
                    <?php
                    $admin_panel_view_books = Database::search("SELECT * FROM `books` INNER JOIN `author_name` ON `books`.`author_name_id` = `author_name`.`id` INNER JOIN `book_category` ON `books`.`book_category_id` = `book_category`.`category_id` INNER JOIN `active` ON `books`.`active_active_id` = `active`.`active_id`  ORDER BY `datetime_added` DESC LIMIT 12 OFFSET 0");
                    for ($x = 0; $x < $admin_panel_view_books->num_rows; $x++) {
                        $bookData = $admin_panel_view_books->fetch_assoc();
                    ?>
                        <div class="col-6 col-md-4  col-lg-3 col-xl-2 col-xxl-2 mt-4 d-flex flex-column product_item added_items">
                            <div class="row product_img position-relative">

                                <div class="col-12 text-center">
                                    <?php

                                    $selected_book_image = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $bookData['book_id'] . "' ");

                                    if ($selected_book_image->num_rows > 0) {
                                        $bookImage = $selected_book_image->fetch_assoc();
                                        $image_url = (isset($bookImage['img_path'])) ?  "../" . $bookImage['img_path'] : '../img/not-found.png';
                                    }

                                    ?>

                                    <img src="<?= $image_url; ?>" alt="book-image" width="100%" height="100%" class="rounded-2">

                                </div>

                            </div>

                            <div class="mt-2 p-3 rounded-2" style="background-color: #f6f6f6;">

                                <?php

                                $maxLength = 15;
                                $text = $bookData['title'];
                                $shortenedText;

                                if (strlen($text) > $maxLength) $shortenedText = substr($text, 0, $maxLength) . '...';
                                else $shortenedText = $text;

                                ?>

                                <p style="font-size: 15px;margin-bottom: 2px;" class="fw-medium"><?= $shortenedText; ?></p>
                                <p style="margin-bottom: 2px;">RS.<?= $bookData['price']; ?>.00</p>
                                <p style="font-size: 13px;margin-bottom: 2px;">Author , <?= $bookData['author_name']; ?></p>
                                <p class="category_display fs-13">Category, <?= $bookData['category_name']; ?></p>

                                <div class="row pt-1">

                                    <div class="col-6 fw-medium">
                                        <?php $isAvilable = ($bookData['qty'] > 0) ? 'in stock' : 'out of stock'; ?>
                                        <p style="font-size: 13px;"><?= $isAvilable; ?></p>
                                    </div>

                                    <div class="col-6 text-end">
                                        <span style="font-size: 13px;">qty : <?= $bookData['qty']; ?></span>
                                    </div>

                                </div>
                            </div>

                            <div class="form-check mt-1 form-switch">

                                <?php $isActive = ($bookData['active_active_id'] == 1) ? true : false ?>
                                <?php $isActiveText = ($bookData['active_active_id'] == 2) ? 'Switch on to Book Active' : 'Switch off to Book Deactive' ?>

                                <input class="form-check-input" onchange="book_status_toggle(<?= $bookData['book_id']; ?>);" type="checkbox" role="switch" id="toggle<?= $bookData['book_id']; ?>"
                                    <?= ($isActive) ? 'checked' : null ?>>

                                <label class="form-check-label" for="toggle<?= $bookData['book_id']; ?>">
                                    <?= $isActiveText ?>
                                </label>

                            </div>

                            <div class="mt-1 text-danger d-flex align-items-center px-2 py-1 rounded-1" style="cursor: pointer;background-color: #fef2f2;" onclick="window.location='book-update.php?id=<?= $bookData['book_id']; ?>';">
                                <p style="font-size: 14px;">Edit book</p>
                                <i class="bi bi-arrow-right-short fs-5"></i>
                            </div>

                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
    </section>

    <script src="../js/script.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/yesiamrocks/cssanimation.io@1.0.3/letteranimation.min.js"></script>

    <script>
        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement;
                arrowParent.classList.toggle("showMenu");
            });
        }
    </script>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");

        if (localStorage.getItem("sidebarState") === "closed") {
            sidebar.classList.add("close");
        }

        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");

            if (sidebar.classList.contains("close")) {
                localStorage.setItem("sidebarState", "closed");
            } else {
                localStorage.removeItem("sidebarState"); // no saved state = default open
            }
        });
    </script>
</body>

</html>