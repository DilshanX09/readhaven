<?php

$is_user_logged_in = isset($_SESSION["user"]);

function mask_email($email)
{

    $parts = explode('@', $email);
    $local = $parts[0];
    $domain = $parts[1];

    $visibleLength = 4;
    $localLength = strlen($local);

    $visiblePart = substr($local, -$visibleLength);

    $asterisksCount = min(5, max(0, $localLength - $visibleLength));
    $maskedPart = str_repeat('*', $asterisksCount);

    return $maskedPart . $visiblePart . '@' . $domain;
}

?>

<form action="search.php" method="GET">

    <input type="hidden" name="page" value="0">

    <header class="main-header-navigation">

        <div class="d-none d-md-block" style="background-color: rgb(255, 82, 82);">
            <div class="container py-2">
                <div class="start-head d-lg-block d-none">
                    <p class="text-white">"Welcome to Your Literary Paradise 📚 Explore Endless Stories"</p>
                </div>
            </div>
        </div>

        <nav class="second-nav">

            <div class="container second-nav-main py-3 d-flex justify-content-between align-items-center">

                <div onclick="window.location='index.php?_#Home'" class="start-logo d-flex flex-column" style="cursor: pointer;">
                    <?php

                    if (!isset($base_path)) {
                        $base_path = '././';
                    }

                    ?>
                    <img src="<?= $base_path; ?>img/logo.svg" alt="logo" width="300px">
                </div>

                <div class="center-search d-flex justify-content-evenly align-items-center gap-2 position-relative">

                    <input value="<?= (isset($_GET['keyword'])) ? $_GET['keyword'] : null ?>" type="text" placeholder="Find books that speak to you..." class="search-input border-0 px-3" id="keyword" name="keyword" />
                    <button class="rounded-5 pt-1" style="right: 5px;position: absolute;top: 5px;" type="submit">
                        <i class="bi bi-search fs-6 pb-2"></i>
                    </button>

                </div>

                <div class="end-user-cart d-flex justify-content-between align-items-center gap-3">
                    <div class="dropdown d-none d-lg-block ">

                        <?php

                        if ($is_user_logged_in) {
                            $data = $_SESSION["user"];
                            $user_image = Database::search("SELECT `img_path` FROM `profile_img` INNER JOIN `users` ON  `profile_img`.`users_email` = `users`.`email` WHERE `email` = '" . $data["email"] . "' ");
                            $image_data = $user_image->fetch_assoc();
                        ?>
                            <div class="dropdown pb-1">

                                <a href="" class="dropdown-toggle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?= (isset($image_data['img_path'])) ? $image_data['img_path'] : "./img/profile.png" ?>" alt="profile-image" width="43px" style="border-radius: 100%;">
                                    <div class="d-flex flex-column">
                                        <p class="ms-2 d-none d-xl-inline-block" style="font-size: 13px;">Welcome, <?= $data['last_name']; ?></p>
                                        <p class="ms-2 d-none d-xl-inline-block text-end" style="font-size: 12px;"><?= mask_email($data["email"]); ?></p>
                                    </div>
                                </a>

                                <div class="dropdown-menu shadow">
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a href="./profile.php"><i class="fa-regular fa-user xxl-user"></i></a>
                                                <a class="xxl-text" href="./profile.php">My Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a href="./setting.php"><i class="fa-solid fa-gear xxl-user"></i></a>
                                                <a class="xxl-text" href="./setting.php">Settings</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a href="order-history.php"><i class="bi bi-clock-history xxl-user"></i></a>
                                                <a href="order-history.php" class="xxl-text">Order History</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a onclick="log_out();" href=""><i class="fa-solid fa-arrow-right-from-bracket xxl-user"></i></a>
                                                <span class="xxl-text" onclick="log_out();">Sign out</span>
                                            </div>
                                        </div>
                                    </li>

                                </div>
                            </div>
                        <?php

                        } else {

                            echo '<a class="d-none d-lg-block px-4 py-2 rounded-2" style="font-size: 13px; background-color: #f6f6f6;" href="./sign-in.php">LOG IN</a>';
                        }

                        ?>
                    </div>

                    <span class="vr-ln px-1 d-none d-lg-block">|</span>

                    <span class="position-relative mt-md-2">

                        <?php

                        $wishlist_books_count = ($is_user_logged_in) ? Database::search("SELECT * FROM watchlist WHERE users_email = '" . $_SESSION['user']['email'] . "'")->num_rows : 0;

                        ?>

                        <span class="heart-cin-count d-flex justify-content-center align-items-center"><?= $wishlist_books_count; ?></span>
                        <a <?= ($is_user_logged_in) ? 'href="./wishlist.php"' : ''; ?>><i class="bi bi-heart"></i></a>

                    </span>

                    <span class="position-relative mt-md-2">

                        <?php

                        $cart_books_count = ($is_user_logged_in) ? Database::search("SELECT * FROM cart WHERE cart_users_email = '" . $_SESSION['user']['email'] . "'")->num_rows : 0;

                        ?>

                        <span class="heart-cin-count d-flex justify-content-center align-items-center"><?= $cart_books_count; ?></span>
                        <a <?= ($is_user_logged_in) ? 'href="./cart.php"' : ''; ?>><i class="bi bi-bag"></i></a>

                    </span>

                    <div class="d-flex justify-content-center align-items-center">
                        <span class="d-lg-none d-block">
                            <div class="dropdown">

                                <?php

                                if ($is_user_logged_in) {
                                    $data = $_SESSION["user"];
                                ?>
                                    <a href="" class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="<?= (isset($image_data['img_path'])) ? $image_data['img_path'] : "./img/profile.png" ?>" alt="profile-image" width="43px" style="border-radius: 100%;">
                                    </a>

                                    <div class="dropdown-menu shadow-lg">
                                        <li class="dropdown-item">
                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                    <a href="./profile.php"><i class="fa-regular fa-user xxl-user"></i></a>
                                                    <a class="xxl-text" href="./profile.php">My Profile</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                    <a href="order-history.php"><i class="bi bi-clock-history xxl-user"></i></a>
                                                    <a href="order-history.php" class="xxl-text">Order History</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                    <a href="./setting.php"><i class="fa-solid fa-gear xxl-user"></i></a>
                                                    <a class="xxl-text" href="./setting.php">Settings</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                    <a onclick="log_out();" href=""><i class="fa-solid fa-arrow-right-from-bracket xxl-user"></i></a>
                                                    <span class="xxl-text" onclick="log_out();">Sign out</span>
                                                </div>
                                            </div>
                                        </li>
                                    </div>

                                <?php
                                } else {

                                ?>
                                    <a href="" class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-user fs-4"></i>
                                    </a>
                                    <div class="dropdown-menu shadow-sm">
                                        <li class="dropdown-item"><a class="text-black" href="./sign-in.php">Log in</a></li>
                                        <li class="dropdown-item"><a class="text-black" href="./register.php">Register</a></li>
                                    </div>

                                <?php

                                }

                                ?>

                            </div>
                        </span>
                    </div>
                </div>
            </div>
            <div class="top-header"></div>
        </nav>

        <section class="d-md-none d-block">

            <div class="container d-flex justify-content-between align-items-center pt-2 mini-header-section">

                <div onclick="window.location='index.php?_#Home'" class="start-logo d-flex flex-column" style="cursor: pointer;">
                    <img src="././img/logo.svg" alt="logo" width="300px">
                </div>

                <div class="mini-end-section d-flex justify-content-center align-items-center gap-3 align-items-center">
                    <span class="d-lg-none d-block">
                        <div class="dropdown">

                            <?php

                            if ($is_user_logged_in) {
                                $data = $_SESSION["user"];
                            ?>
                                <a href="#" class="text-black d-flex justify-content-center align-items-center dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?= (isset($image_data['img_path'])) ? $image_data['img_path'] : "./img/profile.png" ?>" alt="profile-image" width="43px" style="border-radius: 100%;">

                                </a>
                                <div class="dropdown-menu shadow">
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a href="./profile.php"><i class="fa-regular fa-user xxl-user"></i></a>
                                                <a class="xxl-text" href="./profile.php">My Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a href="order-history.php"><i class="bi bi-clock-history xxl-user"></i></a>
                                                <a href="order-history.php" class="xxl-text">Order History</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a href=""><i class="fa-regular fa-heart xxl-user"></i></a>
                                                <a class="xxl-text" href="./wishlist.php">Wishlist</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a href=""><i class="fa-solid fa-cart-shopping xxl-user"></i></i></a>
                                                <a class="xxl-text" href="./cart.php">Cart</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a href="./setting.php"><i class="fa-solid fa-gear xxl-user"></i></a>
                                                <a class="xxl-text" href="./setting.php">Settings</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center gap-3">
                                                <a onclick="log_out();" href=""><i class="fa-solid fa-arrow-right-from-bracket xxl-user"></i></a>
                                                <span class="xxl-text" onclick="log_out();">Sign out</span>
                                            </div>
                                        </div>
                                    </li>
                                </div>
                            <?php
                            } else {
                            ?>
                                <a href="" class="text-black dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i style="font-size: 25px;" class="fa-regular fa-user"></i>
                                </a>
                                <div class="dropdown-menu shadow-lg">
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-start align-items-center gap-3">
                                                <a href="./setting.php"><i class="bi bi-envelope-at xxl-user"></i></i></a>
                                                <a class="xxl-text" href="./sign-in.php">Sign in</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-item">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-start align-items-center gap-3">
                                                <a href="./setting.php"><i class="bi bi-envelope-paper-heart xxl-user"></i></a>
                                                <a class="xxl-text" href="./index.php">Register</a>
                                            </div>
                                        </div>
                                    </li>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </span>
                </div>
            </div>

            <!-- <div class="center-search container d-flex align-items-center gap-2 position-relative">

                <input value="<?= (isset($_GET['keyword'])) ? $_GET['keyword'] : '' ?>" type="text" placeholder="Find books that speak to you..." class="search-input w-100 my-3 border-0 px-3" id="keyword" name="keyword" />
                <button class="rounded-5 pt-1" style="right: 15px;position: absolute;top: 20px;" type="submit">
                    <i class="bi bi-search fs-6 pb-2"></i>
                </button>

            </div> -->

        </section>

        <nav class="box-shadow">
            <div class="container nav-main-section py-3 d-flex justify-content-between align-items-center">
                <div class="start-nav d-flex justify-content-evenly align-items-center">
                    <div class="dropdown-list d-flex justify-content-evenly align-items-center">

                        <div class="category-section">
                            <span><i class="fa-solid fa-bars-staggered"></i></span>
                        </div>

                        <select name="category" id="category">
                            <option value="">SHOP BY CATEGORIES</option>

                            <?php

                            $book_category = Database::search("SELECT * FROM `book_category`");
                            $selected_category = isset($_GET['category']) ? trim($_GET['category']) : '';

                            for ($x = 0; $x < $book_category->num_rows; $x++) {
                                $book_categoryData = $book_category->fetch_assoc();
                                $category_name = htmlspecialchars($book_categoryData["category_name"], ENT_QUOTES, 'UTF-8');
                                $is_selected = (trim($book_categoryData["category_name"]) === $selected_category) ? 'selected' : '';
                            ?>
                                <option value="<?= $category_name; ?>" <?= $is_selected; ?>>
                                    <?= $category_name; ?>
                                </option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="nav-items d-flex mx-4 gap-3">
                        <a class="borderBottomEffect" href="./index.php?_#Home">Home</a>
                        <a class="borderBottomEffect" href="">Trending Books</a>
                        <a class="borderBottomEffect" href="">New Arrivals</a>
                        <a class="borderBottomEffect" href="">Best Deals</a>
                        <a class="borderBottomEffect" href="contact-us.php">Contact Us</a>
                    </div>

                </div>

                <div class="end-nav d-xl-block d-none d-flex flex-row gap-3">
                    <span><i class="bi bi-rocket-takeoff"></i></span>
                    <span>Free International Delivery</span>
                </div>

            </div>
        </nav>
    </header>

</form>