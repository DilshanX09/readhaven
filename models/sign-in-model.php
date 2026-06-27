<div class="modal fade" id="sign-in-model" tabindex="-1" aria-labelledby="sign-in-modelLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content rounded-3 shadow-lg" style="border: 1px solid #f6f6f6;">

               <div class="modal-header mx-3 d-flex flex-column align-items-start">
                    <h1 class="modal-title fs-4" id="sign-in-modelLabel">Sign in to get started</h1>
                    <p class="py-2">"Welcome to ReadHaven! 📚 Step into a world of stories where endless reading pleasures await you at every turn."</p>
               </div>

               <div class="row">
                    <div class="col-12 d-none" id="alert">
                         <div class="alert alert-danger border-0 rounded-1" role="alert" id="response" style="font-size: 13px;margin: 5px 20px;">

                         </div>
                    </div>
               </div>

               <div class="modal-body px-4">

                    <?php

                    $email =  (isset($_COOKIE['email'])) ? $_COOKIE['email'] : null;
                    $password = (isset($_COOKIE['password'])) ? $_COOKIE['password'] : null;

                    ?>

                    <div class="row">
                         <div class="col-12 input-tags position-relative">
                              <input type="email" required id="email" placeholder="Email" value="<?= $email; ?>" />
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
                              <a href="reset-password.php?e=<?= $email; ?>" style="cursor: pointer;text-decoration: underline;font-size: 14px;" data-bs-target="#forgotPassword" data-bs-toggle="model" class="text-primary">Forgot Password</a>
                         </div>
                    </div>

               </div>


               <div class="row px-4">
                    <div class="col-12 d-grid">
                         <button style="border-radius: 7px;" class="primary-btn my-3 d-flex py-3 justify-content-center gap-3 align-items-center" onclick="authenticate();">

                              <div class="loader d-none" id="loader">
                              </div>

                              <span class="text-white" id="authBtnSpan">SIGN IN</span>

                         </button>
                    </div>
               </div>


               <div class="row my-3">
                    <div class="col-12">
                         <p class="p-tags-size-mobile text-center">No account yet ? <span class="text-primary"> <a class="p-tags-size-mobile" href="./register.php">Join now</a></span></p>
                    </div>
               </div>

          </div>
     </div>
</div>