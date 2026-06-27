<?php
session_start();
include "../connection.php";
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
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
    <title>ReadHaven | Manage Users</title>
</head>

<body>

    <?php include '../components/admin-sidebar.php' ?>

    <section class="home-section">

        <?php include '../components/admin-header.php' ?>

        <div class="container-fluid py-3">

            <nav class="pt-4 pb-3 ps-3" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="./main-panel.php">Main Panel</a></li>
                    <li class="breadcrumb-item" aria-current="page">User Management</li>
                    <li class="breadcrumb-item" aria-current="page">Manage Users</li>
                </ol>
            </nav>

            <div class="row ps-3">
                <div class="col">
                    <h4>User Management</h4>
                </div>
            </div>


            <div class="card" style="border: none;">
                <div class="card-body">
                    <div class="row">

                        <div class="col-12">
                            <h6>Filters</h6>
                        </div>

                        <div class="col-12 py-2 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="srt-select p-2">
                                    <select id="userStatus" onchange="customer_status_using_search();">
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">In-Active</option>
                                    </select>
                                </div>
                                <span id="userMsg" class="text-danger" style="font-size: 15px;">

                                </span>
                            </div>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center">
                                <input class="ap-input" id="keyword" type="text" placeholder="Search user.." />
                                <button class="ap-btn-clr" onclick="search_customer(0);">Find user</button>
                                <button onclick=" window.location.reload();" class="bg-transparent pt-1 d-flex align-items-center" style="outline: none;border: none;"><i class="bi bi-arrow-clockwise fs-4 fw-bold"></i></button>
                            </div>
                        </div>

                        <div class="col-12" id="userRslt">
                            <div class="mt-4">
                                <table class="table">
                                    <thead>
                                        <tr class="horizontal-line-table">
                                            <th>
                                                USER

                                            </th>
                                            <th>
                                                MOBILE

                                            </th>
                                            <th>
                                                JOINED DATE

                                            </th>
                                            <th>
                                                STATUS
                                            </th>
                                            <th>
                                                ACTION
                                            </th>
                                        </tr>
                                    </thead>
                                    <div id="userRslt">
                                        <tbody>
                                            <?php

                                            $user_srch_query = "SELECT * FROM `users`";
                                            $page_number;

                                            if (isset($_GET['page'])) $page_number = $_GET['page'];
                                            else $page_number = 1;


                                            $newly_regis_user = Database::search($user_srch_query);
                                            $results_per_page = 50;
                                            $number_of_pages = ceil($newly_regis_user->num_rows / $results_per_page);

                                            $page_results = ($page_number - 1) * $results_per_page;

                                            $selected_rslt = Database::search($user_srch_query . " LIMIT " . $results_per_page . " OFFSET " . $page_results);
                                            for ($x = 0; $x < $selected_rslt->num_rows; $x++) {
                                                $selected_data = $selected_rslt->fetch_assoc();
                                            ?>
                                                <tr class="horizontal-line">
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                <?php
                                                                $user_img = Database::search("SELECT * FROM `profile_img` WHERE `users_email` = '" . $selected_data['email'] . "' ");
                                                                $img_data = $user_img->fetch_assoc();
                                                                $img_url = (isset($img_data['img_path'])) ? $img_data['img_path'] : "img/profile.png";
                                                                ?>
                                                                <img src="<?= "../" . $img_url ?>" class="rounded-circle" width="50px" />
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span style="font-size: 14px;color: black;"><?= $selected_data['first_name'] . " " . $selected_data['last_name'] ?></span>
                                                                <span style="font-size: 13px;"><?= $selected_data['email'] ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span style="font-size: 14px;color: black;">
                                                            <?= $selected_data['mobile'] ?>
                                                        </span>
                                                    </td>
                                                    <td class="rating">
                                                        <?php $split_date = explode(" ", $selected_data['joined_date']); ?>
                                                        <span style="font-size: 13px;color: black;"><?= $split_date[0] ?></span>
                                                    </td>

                                                    <td class="review-text">
                                                        <?php $isActive = ($selected_data['active_id'] == 1) ? "Active" : "Inactive"; ?>
                                                        <span style="font-size: 13px;color: black;" class="<?= $isActive == "Active" ? 'status-active text-success' : 'status-inactive text-danger'; ?>"><?= $isActive; ?></span>
                                                    </td>

                                                    <td>
                                                        <i class="bi bi-trash3 text-danger"></i>
                                                        <i class="<?= $isActive ? 'bi bi-eye' : 'bi bi-eye-slash'; ?>" style="padding-left: 20px;cursor: pointer;" onclick="customer_status_toggle('<?= $selected_data['email'] ?>');"></i>
                                                    </td>
                                                </tr>

                                            <?php } ?>

                                        </tbody>
                                    </div>
                                </table>

                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center gap-1">
                                        <li class="page-item">
                                            <a class="page-link page-link-prnv" href="
                                            <?php

                                            if ($page_number <= 1) {
                                                echo "#";
                                            } else {
                                                echo "?page=" . ($page_number - 1);
                                            }
                                            ?>"
                                                aria-label="Previous">
                                                <i class="bi bi-arrow-left-short fs-6"></i>
                                            </a>
                                        </li>
                                        <?php
                                        for ($x = 1; $x <= $number_of_pages; $x++) {
                                            if ($page_number == $x) {
                                        ?>
                                                <li class="page-item">
                                                    <a class="page-link page-active fs-6" href="<?= "?page=" . ($x); ?>">
                                                        <?= $x; ?>
                                                    </a>
                                                </li>
                                            <?php
                                            } else {
                                            ?>
                                                <li class="page-item">
                                                    <a class="page-link none-active fs-6" href="<?= "?page=" . ($x); ?>">
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
                                            if ($page_number >= $number_of_pages) {
                                                echo "#";
                                            } else {
                                                echo "?page=" . ($page_number + 1);
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