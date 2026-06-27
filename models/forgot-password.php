<div class="modal fade shadow-lg" id="forgotPassword" tabindex="-1" aria-labelledby="forgotPassword" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content rounded-3" style="border: 1px solid #f6f6f6;">

               <div class="modal-header d-flex justify-content-between">
                    <h1 class="modal-title fs-4" id="forgotPassword">Forgot Password</h1>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close"><i class="fa-regular fa-circle-xmark fs-5"></i></button>
               </div>

               <div id="msgBoxup" class="d-none">
                    <div class="alert alert-danger border-0 rounded-1" role="alert" id="response_msgup" style="font-size: 13px;margin: 0px 20px;">

                    </div>
               </div>

               <div class="modal-body px-4">

                    <div class="row mt-2">
                         <div class="col-12 input-tags position-relative">
                              <label for="" class="form-label">Enter new Password</label>
                              <input type="password" required id="newPassword" placeholder="Enter your new password" />
                              <span onclick="ModelShowPasswordFirst();" class="model-eyes"><i id="eye-first" class="fa-regular fa-eye-slash"></i></span>
                         </div>
                    </div>

                    <div class="row mt-2">
                         <div class="col-12 input-tags position-relative">
                              <label for="" class="form-label">Retype Password</label>
                              <input type="password" required id="rePassword" placeholder="Retyped Password" />
                              <span onclick="ModelShowPasswordSecond();" class="model-eyes"><i id="eye-second" class="fa-regular fa-eye-slash"></i></span>
                         </div>
                    </div>

                    <div class="row mt-2">
                         <div class="col-12 input-tags position-relative">
                              <label for="" class="form-label">Verification code</label>
                              <input type="text" required id="verificationCode" placeholder="Verification code" />
                         </div>
                    </div>

                    <div class="row">
                         <div class="col-12 mt-3">
                              <p style="font-size: 13px;">! Your Verification code send to your email address please check this... &
                                   Forgot your password? Click here to reset it securely and regain access to your account.
                              </p>
                         </div>
                    </div>

               </div>

               <div class="modal-footer">
                    <button type="button" class="close_btn" style="font-size: 14px;" data-bs-dismiss="modal">Close</button>
                    <button type="button" style="font-size: 14px;" class="rest_password" onclick="userProfilePasswordReset();">Reset Password</button>
               </div>

          </div>
     </div>
</div>