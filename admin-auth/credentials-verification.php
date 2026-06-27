<?php

session_start();
include "../connection.php";

if (isset($_SESSION['ADMIN_UID']) && isset($_GET['otp-sent']) && $_GET['otp-sent'] === 'true' && isset($_GET['admin'])) {

    $admin_email = $_SESSION['ADMIN_UID']['email'];
?>
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
        <title>Readhaven | Authentication</title>
    </head>

    <body>

        <div class="container d-flex vh-100 justify-content-center align-items-center"
            style="position: absolute;top: 0;right: 0;left: 0;">
            <div class="bg-white px-3 py-3 cv-bg" style="width: 35rem;">

                <div class="row">
                    <div class="col-12 px-5" id="otp-div">

                        <div class="col-12 mb-2">
                            <h3>ReadHaven</h3>
                        </div>

                        <div class="col-12 pt-2">
                            <h6 style="font-size: 20px;">Identity Verification</h6>
                        </div>


                        <div class="col-12 pt-2">
                            <p style="font-size: 14px;">Protecting your tickets is our top priority. Please confirm your account by entering the authorization code sent to </p>
                        </div>


                        <div class="col-12 text-center py-4">

                            <?php

                            function explode_email($email)

                            {
                                $email_explode = explode("@", $email);
                                $local = $email_explode[0];
                                $domain = $email_explode[1];

                                if (strlen($local) > 4) $masked_local = substr($local, 0, 2) . str_repeat("*", strlen($local) - 4) . substr($local, -2);
                                else $masked_local = substr($local, 0, 1) . "*";

                                return $masked_local . "@" . $domain;
                            }

                            ?>

                            <p class="text-black" style="font-size: 14px;">Send to : <?= explode_email($admin_email); ?></p>

                        </div>



                        <div class="col">
                            <div class="otp-field d-flex justify-content-center align-items-center">
                                <input type="text" maxlength="1" />
                                <input type="text" maxlength="1" />
                                <input class="space" type="text" maxlength="1" />
                                <input type="text" maxlength="1" />
                                <input type="text" maxlength="1" />
                                <input type="text" maxlength="1" />
                            </div>
                        </div>



                        <!-- <div class="col-12 py-3 d-flex justify-content-center">
                            <div class="w-100 otp-input d-flex justify-content-center">
                                <input type="text" maxlength="6" class="cs-input" id="otp" />
                            </div>
                        </div> -->


                        <div class="col-12 text-center mt-3">
                            <span style="font-size: 13px;" id="message" class="text-dark"></span>
                        </div>


                        <div class="col-12 pt-4">
                            <span style="font-size: 13px;">It may take a minute to receive your code</span>
                            <span style="font-size: 13px;">haven’t received it ? <span class="text-primary" style="cursor: pointer;" onclick="otp_code_resend('<?= $admin_email ?>');"> Resend a new Code</span> </span>
                        </div>


                    </div>

                    <div class="row d-none" id="welcome-div">
                        <div class="col-12 text-center">
                            <h2 id="admin-name"></h2>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script src="../js/script.js"></script>
        <script src="../js/bootstrap.bundle.js"></script>
        <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    </body>

    </html>
<?php
} else {
    header("Location:admin-auth/admin-authentication.php");
}
