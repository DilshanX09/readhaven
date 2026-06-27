<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="ReadHaven" content="Online_books_selling_web_application">
     <meta name="keywords" content="book,books,online books,books selling online,book store">
     <meta name="author" content="Chamod Dilshan">
     <!-- Icons CDN -->
     <script src="https://kit.fontawesome.com/d94916ec8b.js" crossorigin="anonymous"></script>
     <!-- Styles files -->
     <link rel="stylesheet" href="./css/bootstrap.css">
     <link rel="stylesheet" href="./css/index.css">
     <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
     <title>ReadHaven | Create a new account</title>
</head>

<body>
     <div class="container">
          <div class="row">
               <div class="col-12">
                    <div class="row ">

                         <?php include "./components/authentication-left-side.php"; ?>

                         <div class="col-xxl-6 col-xl-6 col-lg-7 col-12 vh-100 px-xl-10" id="use">
                              <div class="d-flex justify-content-center align-items-center vh-100">

                                   <div class="container p-xxl-4 p-xl-4 p-lg-5 p-md-5 p-sm-4 p-3  bg-white rounded">

                                        <h6 style="font-size: 20px;">Create an account to get started</h6>
                                        <div class="row py-1">
                                             <div class="col-12">
                                                  <p>Kindly fill in your details to create an account.</p>
                                             </div>
                                        </div>

                                        <div>

                                             <div class="row">
                                                  <div class="col-12 d-none" id="alert">
                                                       <div class="alert alert-danger border-0 rounded-1" role="alert" id="response" style="font-size: 13px; margin-bottom: 0;margin-top: 10px;">

                                                       </div>
                                                  </div>
                                             </div>

                                             <div class="row mt-3">
                                                  <div class="col-6 input-tags" style="padding-right: 5px;">
                                                       <input style="width: 100%;" class="m-0" type="text" placeholder="First Name" id="first_name" />
                                                  </div>
                                                  <div class="col-6 input-tags" style="padding-left: 5px;">
                                                       <input type="text" style="width: 100%;" class="m-0" placeholder="Last Name" id="last_name" />
                                                  </div>
                                             </div>

                                             <div class="row">
                                                  <div class="col input-tags pt-1">
                                                       <input type="text" placeholder="Mobile" id="mobile" />
                                                  </div>
                                             </div>

                                             <div class="row">
                                                  <div class="col input-tags pt-1">
                                                       <input type="text" placeholder="Email" id="email" value="" autocomplete="off" />
                                                  </div>
                                             </div>

                                             <div class="row">
                                                  <div class="col input-tags pt-1 position-relative">
                                                       <input type="password" placeholder="Password" id="password" value="" autocomplete="new-password" />
                                                       <span onclick="ShowPassword();" class="eye"><i id="eye" class="fa-regular fa-eye-slash"></i></span>
                                                  </div>
                                             </div>

                                             <div class="row mt-3">

                                                  <div class="row mt-1">
                                                       <div class="col-12">
                                                            <div class="form-check">
                                                                 <input class="form-check-input" type="checkbox" id="agree">
                                                                 <label class="form-check-label p-tags-size-mobile fw-normal" style="font-size: 15px;" for="flexCheckDefault">
                                                                      I agree to the <span class="text-primary"><a href="" class="p-tags-size-mobile">terms
                                                                                of
                                                                                Service </a></span> and
                                                                      <span class="text-primary "><a href="" class="p-tags-size-mobile">privacy
                                                                                policy</a></span>
                                                                 </label>
                                                            </div>
                                                       </div>
                                                  </div>

                                             </div>

                                             <div class="row mt-3">
                                                  <div class="col-12 d-grid">
                                                       <button style="padding: 13px 0;" class="primary-btn d-flex justify-content-center gap-3 align-items-center" onclick="create_account();">

                                                            <div class="loader d-none" id="loader">
                                                            </div>

                                                            <span class="text-white" id="registerBtn">REGISTER</span>

                                                       </button>
                                                  </div>
                                             </div>

                                             <div class="row mt-3">
                                                  <div class="col-12">
                                                       <p class="p-tags-size-mobile">Already a member ? <span class="text-primary"> <a class="p-tags-size-mobile" href="./sign-in.php">Log in</a></span></p>
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

     <script src="./js/script.js"></script>
     <script src="./js/bootstrap.bundle.js"></script>

</body>

</html>