<div class="home-content my-2">
    <i class='bx bx-menu fs-4'></i>

    <?php if (isset($_SESSION['ADMIN_UID'])) {
        $admin_email = $_SESSION['ADMIN_UID']['email'];
        $admin_result = Database::search("SELECT * FROM `admin` INNER JOIN `admin_img` ON `admin`.`username` = `admin_img`.`admin_username` WHERE `email` = '$admin_email'");
        $admin_data = $admin_result->fetch_assoc();
        $admin_prfile_image = (isset($admin_data['img_path'])) ? "../" . $admin_data['img_path'] : "../img/profile.png";
    } ?>

    <div class="d-flex me-3">
        <div class="d-flex flex-column justify-content-center text-end">
            <p style="font-size: 15px;"><?= $admin_data['username']; ?></p>
            <p style="font-size: 14px;"><?= $admin_email; ?></p>
        </div>
        <!-- <img src="<?= $admin_prfile_image ?>" style="width: 50px;height: 50px;border-radius: 100px;margin-left: 15px;" /> -->
    </div>
</div>