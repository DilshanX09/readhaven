<?php include "../connection.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="../css/bootstrap.css" />
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
    <title>Readhaven</title>
</head>

<body>

    <div class="container d-flex vh-100 justify-content-center align-items-center" style="position: absolute;top: 0;right: 0;left: 0;">
        <div class="bg-white px-3 py-3" style="width: 35rem;">

            <div class="row px-5">

                <div class="col-12 mb-2">
                    <h3>ReadHaven</h3>
                </div>

                <div class="col-12">
                    <h6 style="font-size: 20px;">System Administrator Login</h6>
                </div>

                <div class="col-12">
                    <p style="font-size: 13px;" class="mt-2">"Welcome back! Please enter your admin credentials to access the system. Ensure your username and password are correct for secure and efficient management. Thank you for logging in."</p>
                </div>

                <div class="col-12 mt-2">

                    <div class="row d-flex justify-content-center">

                        <div class="col-12 py-1">
                            <span style="font-size: 14px;" class="text-danger" id="response_msg"></span>
                        </div>

                        <div class="row pt-1">
                            <div class="col-12 px-0">
                                <div class="input-tags">
                                    <input type="text" value="" class="mt-1" id="username" placeholder="Username" autocomplete="off" />
                                </div>
                            </div>
                        </div>

                        <div class="row pt-1">
                            <div class="col-12 px-0">
                                <div class="input-tags position-relative">
                                    <input type="password" class="mt-1" id="password" placeholder="Password" value="" autocomplete="off" />
                                    <span onclick="ShowPassword();" class="eye-admin-sign-in"><i id="eye" class="fa-regular fa-eye-slash"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 d-grid px-0">
                                <button style="border-radius: 7px;" class="primary-btn my-3 d-flex py-3 justify-content-center gap-3 align-items-center" onclick="admin_authenticate();">

                                    <div class="loader d-none" id="loader">
                                    </div>

                                    <span class="text-white" id="authBtnSpan">Authenticate</span>

                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 px-0">
                                <span style="font-size: 13px;">The admin sign-in process is restricted exclusively to administrators, ensuring secure access for authorized personnel only</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <script src="../js/script.js"></script>
        <script src="../js/bootstrap.bundle.js"></script>
</body>

</html>