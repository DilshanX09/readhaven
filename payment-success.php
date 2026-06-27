<?php
session_start();
if (!empty($_GET['oid']) && !empty($_GET['ship']) && !empty($_GET['ttl']) && !empty($_GET['amnt'])) {
     $order_id = $_GET['oid'];
     $shipping = $_GET['ship'];
     $total = $_GET['ttl'];
     $amount = $_GET['amnt'];
     $name = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
     date_default_timezone_set('Asia/Colombo');
     $formattedDate = date('d-m-Y, g.i A');
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
                         <dotlottie-player src="https://lottie.host/de37dfda-3e19-4d5a-b12c-08ece9995673/s2XKqy7ysU.lottie" background="transparent" speed="1" style="width: 70px; height: 70px" loop autoplay></dotlottie-player>
                         <span class="text-black" style="font-size: 17px;">Payment Successful</span>
                         <span class="fs-13">Thank you for your order!</span>
                         <div onclick="window.location='./check-out-invoice.php?id=<?php echo $order_id ?>'">
                              <div class="my-3 gap-2 d-flex align-items-center cstm-box">
                                   <i class="bi bi-file-earmark-plus text-black fs-13"></i>
                                   <span class="fs-13 text-black">#<span class="text-uppercase"><?php echo $order_id ?></span></span>
                              </div>
                         </div>
                         <div class="cstm-border"></div>
                    </div>
                    <div class="px-3 py-3">
                         <div class="d-flex flex-row justify-content-between align-items-center">
                              <span class="fs-13 text-black">Time / Date</span>
                              <span class="fs-13 text-black"><?php echo $formattedDate ?></span>
                         </div>
                         <div class="d-flex mt-1 flex-row justify-content-between align-items-center">
                              <span class="fs-13 text-black">Order Number</span>
                              <span class="fs-13 text-black text-uppercase"><?php echo $order_id ?></span>
                         </div>
                         <div class="d-flex mt-1 flex-row justify-content-between align-items-center">
                              <span class="fs-13 text-black">Sender Name</span>
                              <span class="fs-13 text-black"><?php echo $name ?></span>
                         </div>
                    </div>
                    <div class="cstm-border"></div>
                    <div class="px-3 py-3">
                         <div class="d-flex flex-row justify-content-between align-items-center">
                              <span class="fs-13 text-black">Amount</span>
                              <span class="fs-13 text-black">Rs.<?php echo $amount ?>.00</span>
                         </div>
                         <div class="d-flex mt-1 flex-row justify-content-between align-items-center">
                              <span class="fs-13 text-black">Delivery fee</span>
                              <span class="fs-13 text-black">Rs.<?php echo $shipping ?>.00</span>
                         </div>
                         <div class="d-flex mt-1 flex-row justify-content-between align-items-center">
                              <span class="fs-13 text-black">Total</span>
                              <span class="fs-13 text-black">Rs.<?php echo $total ?>.00</span>
                         </div>
                    </div>

                    <div class="row mx-1 my-3">
                         <div class="col-12 d-grid">
                              <button onclick="window.location='./index.php'" class="buy rounded-3 py-3 fs-13">Back to Home</button>
                         </div>
                    </div>

               </div>
          </div>

          <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
          <script src="./js/script.js"></script>
          <script src="./js/bootstrap.bundle.js"></script>
     </body>

     </html>
<?php
} else {
     echo 'eror';
}
?>