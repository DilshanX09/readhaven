<?php

include '../connection.php';

$keyword = $_POST['keyword'];

$user_srch_query = "SELECT * FROM `users` WHERE `email` = '" . $keyword . "' OR `mobile` = '" . $keyword . "'";

$rslt = Database::search($user_srch_query);

if ($rslt->num_rows == 1) { ?>

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

                    for ($x = 0; $x < $rslt->num_rows; $x++) {
                        $selected_data = $rslt->fetch_assoc();
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
    </div>

<?php } else { ?>

    <div class="row mt-5">
        <div class="col-12 text-center pt-2">
            <span style="font-size: 14px;color: black;">This user not found...!</span>
        </div>
    </div>

<?php }
