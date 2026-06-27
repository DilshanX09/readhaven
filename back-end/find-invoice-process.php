<?php

include '../connection.php';

if (isset($_POST['value'])) {

    $value = $_POST["value"];
    $invoice_srch_query = "SELECT * FROM `invoice` INNER JOIN `books` ON invoice.book_id = books.book_id INNER JOIN `users` ON invoice.users_email = users.email  WHERE `order_id` = '" . $value . "' OR `email` = '" . $value . "'";
    $rslt = Database::search($invoice_srch_query);

    if ($rslt->num_rows > 0) {
?>
        <table class="table">
            <thead>
                <tr class="horizontal-line-table">
                    <th>
                        #ORDER ID
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
                        ISSUED DATE
                    </th>
                    <th>
                        STATUS
                    </th>
                    <th>
                        ACTIONS
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php

                $page_number;

                if ("0" != $_POST['page']) {
                    $page_number = $_POST['page'];
                } else {
                    $page_number = 1;
                }

                $invoice_results = Database::search($invoice_srch_query);
                $results_per_page = 50;
                $number_of_pages = ceil($invoice_results->num_rows / $results_per_page);

                $page_results = ($page_number - 1) * $results_per_page;

                $selected_rslt = Database::search($invoice_srch_query . " LIMIT " . $results_per_page . " OFFSET " . $page_results);
                for ($x = 0; $x < $selected_rslt->num_rows; $x++) {
                    $selected_data = $selected_rslt->fetch_assoc();
                ?>
                    <tr class="horizontal-line">
                        <td>
                            <span class="text-uppercase" style="font-size: 13px;color: black;">#<?= $selected_data['order_id'] ?></span>
                        </td>
                        <td>
                            <span class="customer-name">
                                <?= $selected_data['title'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <?php
                                    $user_img = Database::search("SELECT * FROM `profile_img` WHERE `users_email` = '" . $selected_data['email'] . "' ");
                                    $img_data = $user_img->fetch_assoc();
                                    $profile_img = (isset($img_data['img_path'])) ? $img_data['img_path'] : "img/profile.png";

                                    ?>
                                    <img src="<?= '../' . $profile_img ?>" class="rounded-circle" width="50px" />
                                </div>
                                <div class="d-flex flex-column">
                                    <span style="font-size: 14px;color: black;"><?= $selected_data['first_name'] . " " . $selected_data['last_name'] ?></span>
                                    <span style="font-size: 13px;"><?= $selected_data['email'] ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="review-text">
                            RS.<?= $selected_data['total'] ?>.00
                        </td>
                        <td>

                            <span style="font-size: 13px;color: black;"><?= $selected_data['date'] ?></span>
                        </td>
                        <td>
                            <span style="font-size: 13px;color: black;" class="status-active text-success">Packing</span>
                        </td>
                        <td>
                            <i class="bi bi-trash3 text-danger"></i>
                            <i class="bi bi-eye" style="padding-left: 20px;"></i>
                        </td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center gap-1">
                <li class="page-item"
                    <?php
                    if ($page_number <= 1) {
                        echo "#";
                    } else {
                    ?>
                    onclick="find_invoice(<?php echo ($page_number - 1); ?>);"
                    <?php
                    }
                    ?>>
                    <a class="page-link page-link-prnv"
                        aria-label="Previous">
                        <i class="bi bi-arrow-left-short fs-6"></i>
                    </a>
                </li>
                <?php
                for ($x = 1; $x <= $number_of_pages; $x++) {
                    if ($page_number == $x) {
                ?>
                        <li class="page-item" onclick="find_invoice(<?php echo ($x); ?>);">
                            <a class="page-link page-active fs-6">
                                <?php echo $x; ?>
                            </a>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li class="page-item" onclick="find_invoice(<?php echo ($x); ?>);">
                            <a class="page-link none-active fs-6">
                                <?php echo $x; ?>
                            </a>
                        </li>
                <?php
                    }
                }
                ?>
                <li class="page-item"
                    <?php
                    if ($page_number >= $number_of_pages) {
                        echo "#";
                    } else {
                    ?>
                    onclick="find_invoice(<?php echo ($page_number + 1); ?>);"
                    <?php
                    }
                    ?>>
                    <a class="page-link page-link-prnv" aria-label=" Next">
                        <i class="bi bi-arrow-right-short fs-6"></i>
                    </a>
                </li>
            </ul>
        </nav>
<?php
    }
}
