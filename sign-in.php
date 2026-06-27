<?php

session_start();

$email =  (isset($_COOKIE['email'])) ? $_COOKIE['email'] : null;
$password = (isset($_COOKIE['password'])) ? $_COOKIE['password'] : null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="ReadHaven" content="Online_books_selling_web_application">
    <meta name="keywords" content="book,books,online books,books selling online,book store">
    <meta name="author" content="Chamod Dilshan">
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/index.css">
    <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
    <title>ReadHaven</title>
</head>

<body>
    <div class="container">

        <div class="row">

            <div class="col-12">

                <div class="row ">

                    <?php include "./components/authentication-left-side.php"; ?>

                    <div class="col-xxl-6 col-xl-6 col-lg-7 col-12 vh-100 px-xl-10" id="use">

                        <div class="d-flex justify-content-center align-items-center vh-100">
                            <div class="container  p-xxl-4 p-xl-4 p-lg-5 p-md-5 p-sm-4 p-3  bg-white rounded">

                                <h6 style="font-size: 20px;">Sign in to get started</h6>

                                <p class="py-2">"Welcome to ReadHaven! 📚 Step into a world of stories where endless reading pleasures await you at every turn."</p>

                                <div>
                                    <div class="row">
                                        <div class="col-12 d-none" id="alert">
                                            <div class="alert alert-danger border-0 rounded-1" role="alert" id="response" style="font-size: 13px; margin-bottom: 0;margin-top: 10px;">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col input-tags mt-4">
                                            <input type="text" required placeholder="Email" id="email" value="<?= $email; ?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col input-tags pt-1 position-relative">
                                            <input type="password" required placeholder="Password" id="password" value="<?= $password; ?>" />
                                            <span onclick="ShowPassword();" class="eye"><i id="eye" class="fa-regular fa-eye-slash"></i></span>
                                        </div>
                                    </div>

                                    <div class="row mt-3">

                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                                <label class="form-check-label p-tags-size-mobile fw-normal" style="font-size: 15px;" for="flexCheckDefault">
                                                    Remember me
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-6 text-end">
                                            <a style="cursor: pointer;text-decoration: underline;font-size: 14px;" href="reset-password.php<?= isset($email) ? '?e=' . $email : ''; ?>">Forgot Password</a>
                                        </div>

                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12 d-grid">
                                            <button style="padding: 13px 0;" class="primary-btn d-flex justify-content-center gap-3 align-items-center" onclick="authenticate();">

                                                <div class="loader d-none" id="loader">
                                                </div>

                                                <span class="text-white" id="authBtnSpan">SIGN IN</span>

                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <p class="p-tags-size-mobile">No account yet ? <span class="text-primary"> <a class="p-tags-size-mobile" href="./register.php">Join now</a></span></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="./js/bootstrap.bundle.js"></script>

</body>

</html>