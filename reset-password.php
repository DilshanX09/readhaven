<?php require "connection.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
     <link rel="stylesheet" href="css/index.css" />
     <link rel="stylesheet" href="css/bootstrap.css" />
     <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
     <title>Readhaven</title>
</head>

<body onload="check_reset_password_page_status();">

     <div class="container d-flex vh-100 justify-content-center align-items-center" style="position: absolute;top: 0;right: 0;left: 0;">
          <div class="bg-white px-3 py-3" style="width: 35rem;">

               <div class="row px-5">

                    <div class="col-12 mb-2">
                         <h3>ReadHaven</h3>
                    </div>

                    <div id="sendMail" class="d-none">

                         <div class="col-12">
                              <h6 style="font-size: 20px;">Reset Your Password</h6>
                         </div>

                         <div class="col-12">
                              <p style="font-size: 13px;" class="mt-2">"Welcome back! Please provide the email associated with your account so we can send detailed steps and a secure link to reset it."</p>
                         </div>

                         <div class="col-12 mt-2">

                              <div class="row d-flex justify-content-center">

                                   <div class="col-12 py-1">
                                        <span style="font-size: 14px;" class="text-danger" id="response"></span>
                                   </div>

                                   <div class="row mt-2">
                                        <div class="col-12 px-0">
                                             <div class="input-tags">
                                                  <input type="email" value="<?= (isset($_GET['e'])) ? $_GET['e'] : null ?>" class="mt-1" id="email" placeholder="Email" autocomplete="off" />
                                             </div>
                                        </div>
                                   </div>

                                   <div class="row">
                                        <div class="col-12 d-grid px-0">
                                             <button style="border-radius: 7px;" class="primary-btn my-3 d-flex py-3 justify-content-center gap-3 align-items-center" onclick="send_password_reset_code();">

                                                  <div class="loader d-none" id="loader">
                                                  </div>

                                                  <p class="text-white" id="emailSendBtn">Send Verification Code</p>

                                             </button>
                                        </div>
                                   </div>

                                   <div class="row">
                                        <div class="col-12 px-0">
                                             <span style="font-size: 13px;">Submit your email below. Watch for a reset message in your inbox and follow the link to update your password.</span>
                                        </div>
                                   </div>

                              </div>

                         </div>


                    </div>

                    <div id="mailVerify" class="d-none">

                         <div class="col-12">
                              <h6 style="font-size: 20px;">Reset Password Verification Code</h6>
                         </div>

                         <div class="col-12">
                              <p style="font-size: 13px;" class="mt-2">"We’ve sent a unique verification code to your email. Enter it below to confirm your identity and continue resetting your password."</p>
                         </div>

                         <div class="col-12 mt-2">

                              <div class="row d-flex justify-content-center">


                                   <div class="col-12">
                                        <p style="font-size: 13px;" id="showEmail" class="pb-3"></p>
                                   </div>

                                   <div class="col mt-3">
                                        <div class="reset-otp-field d-flex justify-content-center align-items-center">
                                             <input type="text" maxlength="1" />
                                             <input type="text" maxlength="1" />
                                             <input class="space" type="text" maxlength="1" />
                                             <input type="text" maxlength="1" />
                                             <input type="text" maxlength="1" />
                                             <input type="text" maxlength="1" />
                                        </div>
                                   </div>

                                   <div class="col-12 pt-3 text-center">
                                        <span style="font-size: 14px;" class="text-danger" id="verification_response">
                                   </div>


                                   <div class="row mt-5">
                                        <div class="col-12 px-0">
                                             <span style="font-size: 13px;">Submit your email below. Watch for a reset message in your inbox and follow the link to update your password.</span>
                                        </div>
                                   </div>

                              </div>

                         </div>

                    </div>

                    <div id="createPassword" class="d-none">

                         <div class="col-12">
                              <h6 style="font-size: 20px;">Create New Password</h6>
                         </div>

                         <div class="col-12">
                              <p style="font-size: 13px;" class="mt-2">"Set your new password here. Pick something secure and unique to maintain account safety and prevent unauthorized access."</p>
                         </div>

                         <div class="col-12 mt-2">

                              <div class="row d-flex justify-content-center">

                                   <div class="col-12 py-1">
                                        <span style="font-size: 14px;" class="text-danger" id="createPasswordResponse"></span>
                                   </div>

                                   <div class="row pt-1">

                                        <div class="col-12 px-0">
                                             <div class="col input-tags pt-1 position-relative">
                                                  <input type="password" required placeholder="New Password" id="password" autocomplete="new-password" />
                                                  <span onclick="ShowResetPassword();" class="eye"><i id="eye" class="fa-regular fa-eye-slash"></i></span>
                                             </div>
                                        </div>

                                        <div class="col-12 px-0">
                                             <div class="col input-tags pt-1 position-relative">
                                                  <input type="password" required placeholder="Confirm Password" id="retypedPassword" />
                                                  <span onclick="ShowResetPassword();" class="eye"><i id="reEye" class="fa-regular fa-eye-slash"></i></span>
                                             </div>
                                        </div>

                                   </div>

                                   <div class="row">
                                        <div class="col-12 d-grid px-0">
                                             <button style="border-radius: 7px;" class="primary-btn my-3 d-flex py-3 justify-content-center gap-3 align-items-center" onclick="reset_password();">

                                                  <div class="loader d-none" id="createPasswordLoader">
                                                  </div>

                                                  <span class="text-white" id="changePasswordBtn">Change Password</span>

                                             </button>
                                        </div>
                                   </div>

                                   <div class="row">
                                        <div class="col-12 px-0">
                                             <span style="font-size: 13px;">Enter your new password below. Make it strong and secure, then confirm to successfully update and protect your account.</span>
                                        </div>
                                   </div>

                              </div>

                         </div>
                    </div>

               </div>


          </div>


          <script src="./js/script.js"></script>
          <script src="./js/bootstrap.bundle.js"></script>
</body>

</html>