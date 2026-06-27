<?php
if (!empty($_GET['stt'])) {
?>
     <!DOCTYPE html>
     <html lang="en">

     <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
          <link rel="stylesheet" href="./css/bootstrap.css" />
          <link rel="stylesheet" href="./css/index.css" />
          <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
          <title>ReadHaven | Payment</title>
     </head>

     <body>
          <div class="container vh-100 d-flex justify-content-center align-items-center">
               <div class="cstm-card" style="width: 400px;">
                    <div class="card-body d-flex align-items-center flex-column">
                         <dotlottie-player class="my-3" src="https://lottie.host/19836a70-7275-4901-a3c5-4c4566ac57aa/uIDhOkfR4p.lottie" background="transparent" speed="1" style="width: 70px; height: 70px" loop autoplay></dotlottie-player>
                         <span class="text-black mb-3" style="font-size: 17px;">Payment Cancelled</span>
                    </div>
                    <div class="cstm-border"></div>
                    <div class="row px-3 py-3">
                         <div class="col-12 d-flex flex-column">
                              <span class="fs-13 text-black mb-2">Your payment was not completed.</span>
                              <span class="fs-13 text-black">If this was unintentional, please try again or contact support for assistance..</span>
                              <span class="fs-13 txt-primary mt-4">www.readhaven.lk</span>
                         </div>
                    </div>
                    <div class="cstm-border"></div>
                    <a href="index.php">
                         <div class="row mx-1 my-3">
                              <div class="col-12 d-grid">
                                   <button class="buy rounded-3 py-3 fs-13">Back to Home</button>
                              </div>
                         </div>
                    </a>
               </div>

               <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
               <script src="./js/script.js"></script>
               <script src="./js/bootstrap.bundle.js"></script>
     </body>

     </html>
<?php
}
?>