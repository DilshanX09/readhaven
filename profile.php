<?php

session_start();

include "./connection.php";

if (!isset($_SESSION['user'])) header('location:index.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/bootstrap.css" />
    <link rel="stylesheet" href="./css/index.css" />
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    <title>ReadHaven | Profile</title>
</head>

<body>

    <?php

    include "./components/header.php";

    if (isset($_SESSION["user"])) {
        $email = $_SESSION["user"]["email"];

        $user = Database::search("SELECT * FROM `users` WHERE `email` = '" . $email . "' ");
        $userAddress = Database::search("SELECT * FROM `users_has_address` INNER JOIN `city` ON users_has_address.city_id = city.city_id INNER JOIN `district` ON city.district_district_id = district.district_id INNER JOIN `province` ON district.province_province_id = province.province_id WHERE `users_email` = '" . $email . "' ");
        $userImage = Database::search("SELECT * FROM `profile_img` WHERE `users_email` = '" . $email . "' ");
        $usersAdressCount = $userAddress->num_rows;
        $users = $user->num_rows;
        $usersData = $user->fetch_assoc();
        $userAddressData = $userAddress->fetch_assoc();
        $imageData = $userImage->fetch_assoc();

    ?>
        <div class="container mt-4 pb-5">

            <div class="row">
                <div class="col-12">
                    <a href="index.php"><i class="fa-solid fa-arrow-left text-black"></i></a>
                    <a href="index.php" class="fw-medium text-black">Back</a>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <div class="row">

                        <div class="col-12 col-md-3 px-3">

                            <div class="userImage text-center">
                                <img src="<?= (isset($imageData['img_path'])) ? $imageData["img_path"] : "img/profile.png"; ?>" alt="profile-image" id="image" width="250px" />
                                <p><input id="imageChoose" type="file" class="d-none"></p>
                                <p class="ImgEdit-P"><label class="ImgEdit" onclick="change_profile_image();" for="imageChoose"><i class="fa-regular fa-pen-to-square"></i></label></p>
                            </div>

                            <div class="userInfo">
                                <p>Full name :</p>
                                <h6><?= $usersData["first_name"]; ?> <?= $usersData["last_name"]; ?></h6>
                                <p>Email Address :</p>
                                <h6><?= $email; ?></h6>
                                <p>Mobile :</p>
                                <h6><?= $usersData["mobile"] ?></h6>
                                <?php
                                if (!empty($userAddressData["line1"])) {
                                ?>
                                    <p>Address Line 01:</p>
                                    <h6><?php echo $userAddressData["line1"] ?></h6>
                                <?php
                                }
                                ?>
                                <?php
                                if (!empty($userAddressData["line2"])) {
                                ?>
                                    <p>Address Line 02:</p>
                                    <h6><?php echo $userAddressData["line2"] ?></h6>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mt-3">

                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5>Edit Profile</h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 d-none" id="alert">
                                    <div class="alert alert-danger border-0 rounded-1" role="alert" id="response" style="font-size: 13px; margin-bottom: 10;margin-top: 10px;">

                                    </div>
                                </div>
                            </div>

                            <div class="row">


                                <div class="col-12 col-lg-6 input-tags">
                                    <label for="" class="form-label">First Name</label>
                                    <input type="text" required id="fname" value="<?= $usersData["first_name"]; ?>" />
                                </div>

                                <div class="col-12 col-lg-6 input-tags">
                                    <label for="" class="form-label">Last Name</label>
                                    <input type="text" required id="lname" value="<?= $usersData["last_name"]; ?>" />
                                </div>

                            </div>

                            <div class="row mt-2">
                                <div class="col-12 input-tags">
                                    <label for="" class="form-label">Mobile</label>
                                    <input type="text" required id="mobile" value="<?= $usersData["mobile"]; ?>" />
                                </div>
                            </div>

                            <div class="row mt-2">

                                <?php

                                $address_line_01 = (!empty($userAddressData['line1'])) ? $userAddressData["line1"] : '';
                                $address_line_02 =  (!empty($userAddressData['line2'])) ? $userAddressData["line2"] : '';

                                $placeholder_line_01 = (empty($userAddressData['line1'])) ? "placeholder='Enter your Address line 01'" : null;
                                $placeholder_line_02 = (empty($userAddressData['line2'])) ? "placeholder='Enter your Address line 02'" : null;

                                ?>

                                <div class="col-12 col-lg-6 input-tags">
                                    <label for="" class="form-label">Address line 01</label>
                                    <input type="text" id="line01" value="<?= $address_line_01 ?>" <?= $placeholder_line_01 ?> />
                                </div>

                                <div class="col-12 col-lg-6 input-tags">
                                    <label for="" class="form-label">Address line 02</label>
                                    <input type="text" id="line02" value="<?= $address_line_02 ?>" <?= $placeholder_line_02 ?> />
                                </div>


                            </div>


                            <?php

                            $provinceTable = Database::search("SELECT * FROM `province` ");
                            $districtTable = Database::search("SELECT * FROM `district` ");
                            $cityTable = Database::search("SELECT * FROM `city` ");

                            ?>
                            <div class="row mt-2">
                                <div class="col-12 col-lg-6 userProfileInput">
                                    <label for="" class="form-label">Province</label>
                                    <select name="" onchange="select_district();" id="province" class="select-input">

                                        <option value="">Select Province</option>

                                        <?php

                                        for ($x = 0; $x < $provinceTable->num_rows; $x++) {
                                            $provinceData = $provinceTable->fetch_assoc();
                                            $selected = (!empty($userAddressData['province_id'])) ? $userAddressData['province_id'] : '';
                                        ?>
                                            <option value="<?= $provinceData['province_id']; ?>" <?= ($provinceData['province_id'] == $selected) ? 'selected' : '' ?>>
                                                <?= $provinceData['province_name']; ?>
                                            </option>

                                        <?php

                                        }

                                        ?>

                                    </select>

                                </div>
                                <div class="col-12 col-lg-6 userProfileInput">
                                    <label for="" class="form-label">District</label>
                                    <select name="" id="district" onchange="select_city();" class="select-input">

                                        <option value="">Select District</option>

                                        <?php

                                        for ($x = 0; $x < $districtTable->num_rows; $x++) {
                                            $districtData = $districtTable->fetch_assoc();
                                            $selected = (!empty($userAddressData['district_id'])) ? $userAddressData['district_id'] : '';
                                        ?>

                                            <option value="<?= $districtData['district_id']; ?>" <?= ($districtData['district_id'] == $selected) ? 'selected' : '' ?>>
                                                <?= $districtData['district_name']; ?>
                                            </option>

                                        <?php

                                        }

                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6 userProfileInput">
                                    <label for="" class="form-label">City</label>
                                    <select id="city" class="select-input">

                                        <option value="">Select City</option>

                                        <?php

                                        for ($y = 0; $y < $cityTable->num_rows; $y++) {
                                            $cityData = $cityTable->fetch_assoc();
                                            $selected = (!empty($userAddressData['city_id'])) ? $userAddressData['city_id'] : '';
                                        ?>

                                            <option value="<?= $cityData['city_id']; ?>" <?= ($cityData['city_id'] == $selected) ? 'selected' : '' ?>>
                                                <?= $cityData['city_name']; ?>
                                            </option>

                                        <?php

                                        }

                                        ?>

                                    </select>
                                </div>

                                <div class="col-6 input-tags">
                                    <label for="" class="form-label">Postal Code</label>
                                    <input type="text" required id="pcode" <?= (empty($userAddressData['postal_code'])) ? "placeholder='Enter your Postal code'" : "" ?> value="<?= (!empty($userAddressData['postal_code'])) ? $userAddressData["postal_code"] : null ?>" />
                                </div>

                                <div class="row mt-2">
                                    <div class="col-12">
                                        <p style="font-size:13px ;" class="px-2">
                                            "Please ensure all information is accurate and up-to-date. Inaccurate details may affect your account’s functionality and access."
                                        </p>
                                    </div>
                                </div>

                                <div class="row mt-3 m-0 p-0">
                                    <div class="col d-grid m-0">
                                        <button class="cart" onclick="save_profile_changes();">Save Changes</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 col-md-3 mt-3">

                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5>Your Primary information</h5>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12 input-tags">
                                    <label for="" class="form-label">Primary Email Address</label>
                                    <input type="text" id="email" value="<?= $usersData["email"]; ?>" readonly />
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12 input-tags position-relative">
                                    <label for="" class="form-label">Primary Password</label>
                                    <input id="password" type="password" value="<?= $usersData["password"]; ?>" readonly />
                                    <span onclick="ShowPassword();" class="profile-eye"><i id="eye" class="fa-regular fa-eye-slash"></i></span>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 text-end">
                                    <a href="reset-password.php<?= isset($email) ? '?e=' . $email : ''; ?>" id="pminf">Reset Password</a>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        </div>

    <?php

    }

    ?>

    <?php include "./components/footer.php"; ?>

    <script src="./js/bootstrap.bundle.js"></script>
    <script src="./js/script.js"></script>

</body>

</html>