<?php include 'connection.php'; ?>

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
    <title>ReadHaven | Contact us</title>
</head>

<body>

    <?php include './components/header.php'; ?>

    <div class="row d-none overflow-hidden m-0" style="padding:100px 0px;" id="feedscc">
        <div class="col-12 d-flex flex-column align-items-center gap-4">
            <img src="./img/success.png" width="100px" />
            <span class="text-black">Your Message send Successfully Complete!</span>
        </div>
    </div>

    <div class="container" id="cnt-frm">

        <div class="row mt-5">

            <div class="col-12 d-flex justify-content-center">

                <div class="col-6 px-3 py-4 d-flex justify-content-center align-items-center">

                    <div class="row">

                        <div class="col-12">

                            <div class="f_a_q d-flex mb-3 justify-content-center align-items-center">
                                <h2>Contact us</h2>
                            </div>

                            <div class="footer-newsletter mb-4">
                                <div class="container">
                                    <div class="row text-center">
                                        <h3>Let’s Get In Touch</h3>
                                        <span>Or Just reach out manually to <span class="txt-primary">ReadHaven.lk</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-column align-items-center">

                            <div style="width:100%;">

                                <div class="row mb-2">
                                    <div class="col-12 d-none" id="msgBox">
                                        <div class="alert alert-danger border-0" role="alert" id="response_msg" style="font-size: 13px; margin-bottom: 0;margin-top: 10px;">

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6 userProfileInput">
                                        <input type="text" placeholder="Name" id="name" />
                                    </div>
                                    <div class="col-6 userProfileInput">
                                        <input type="text" placeholder="Email" id="email" />
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-6 userProfileInput">
                                        <input type="text" placeholder="Mobile" id="mobile" />
                                    </div>
                                    <div class="col-6 userProfileInput">
                                        <input type="text" placeholder="Subject" id="subject" />
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-12 userProfileInput">
                                        <textarea id="message" placeholder="Message" class="p-3"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-grid">
                                        <button style="border-radius: 7px;" class="primary-btn my-3 d-flex py-3 justify-content-center gap-3 align-items-center" onclick="contact();">

                                            <div class="loader d-none" id="loader">
                                            </div>

                                            <span class="text-white" id="contact-btn">Submit Form</span>

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include './components/footer.php'; ?>

    <script src="./js/script.js"></script>
    <script src="./js/bootstrap.bundle.js"></script>
    
</body>
</html>