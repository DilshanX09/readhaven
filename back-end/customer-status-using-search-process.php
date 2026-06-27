<?php

include '../connection.php';

if (isset($_GET['state'])) {
    $state = $_GET['state'];
    $user_srch_query = "SELECT * FROM `users`";

    if ($state == "1") {
        $user_srch_query .= "WHERE `active_id` = '" . $state . "'";
    } else if ($state == "2") {
        $user_srch_query .= "WHERE `active_id` = '" . $state . "'";
    }

?>
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
            <div>
                <tbody>
                    <?php
                    
                    $page_number;

                    if (isset($_GET['page'])) {
                        $page_number = $_GET['page'];
                    } else {
                        $page_number = 1;
                    }

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
                            <a class="page-link page-active fs-6" href="<?php echo "?page=" . ($x); ?>">
                                <?php echo $x; ?>
                            </a>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li class="page-item">
                            <a class="page-link none-active fs-6" href="<?php echo "?page=" . ($x); ?>">
                                <?php echo $x; ?>
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

<?php }
