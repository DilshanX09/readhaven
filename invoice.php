<?php include 'connection.php';
if (empty($_GET['id'])) {
    header("Location:index.php");
} else {
?>
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
        <title>ReadHaven | Invoice</title>
    </head>

    <body>

        <?php

        include 'components/header.php';

        if (isset($_SESSION['user'])) {
            $email = $_SESSION['user']['email'];
            $order_id = $_GET['id'];

            $invoice_rslt = Database::search("SELECT * FROM `invoice`
                INNER JOIN `users` ON `users`.`email` = `invoice`.`users_email` 
                INNER JOIN `books` ON `invoice`.`book_id` = `books`.`book_id`
                WHERE `order_id` = '" . $order_id . "';
            ");

            $invoice_data = $invoice_rslt->fetch_assoc();

        ?>
            <div class="container" id="page">

                <div class="row pt-4">
                    <div class="col-6 d-flex flex-column">
                        <span class="txt-primary fs-2 fw-medium">ReadHaven.lk</span>
                        <span class="fw-medium fs-5">Invoice No : #<?php echo $invoice_data["invoice_id"] ?></span>
                        <span class="fw-light fs-6"><i class="bi bi-calendar3"></i> Issued Date : <?php echo $invoice_data["date"] ?></span>
                    </div>
                    <div class="col-6 pt-1 text-end d-flex flex-column">
                        <span class="fs-5 fw-medium text-black">ReadHaven.lk</span>
                        <span>ReadHaven@gmail.com</span>
                        <span>071 917 8824</span>
                        <span>NO87/ Colombo Road , Avissawella</span>
                        <span>Sri Lanka,</span>
                    </div>
                </div>

                <div class="row mt-4 invc py-3 px-2">
                    <div class="col-12 d-flex flex-column">
                        <?php
                        $user_location = Database::search("SELECT * FROM `users_has_address` WHERE `users_email` = '" . $email . "'");
                        $location_data = $user_location->fetch_assoc();
                        ?>
                        <span class="fs-4 fw-medium text-black">Invoice To:</span>
                        <span><?php echo $invoice_data["first_name"] . " " . $invoice_data["last_name"] ?></span>
                        <span><?php echo $location_data["line1"] ?>,</span>
                        <span><?php echo $location_data["line2"] ?>,</span>
                        <span><?php echo $email; ?></span>
                        <span><?php echo $invoice_data["mobile"] ?></span>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <table style="width: 100%;">

                            <tr class="invc-tbl">
                                <th style="width: 15%;"><span class="fw-medium">#ORDER ID</span></th>
                                <th style="width: 30%;"><span class="fw-medium">BOOK</span></th>
                                <th style="width: 14%;"><span class="fw-medium">UNIT PRICE</span></th>
                                <th style="width: 14%;" class="text-center"><span class="fw-medium">QUANTITY</span></th>
                                <th style="width: 18.33%;" class="text-end"><span class="fw-medium">TOTAL</span></th>
                            </tr>
                            <tr class="inv-tr">
                                <td><span class="text-black text-uppercase" style="font-size: 12px;">#<?php echo $invoice_data["order_id"] ?></span></td>
                                <td class="d-flex align-items-center gap-2">
                                    <?php
                                    $img = Database::search("SELECT * FROM `book_img` WHERE `book_id` = '" . $invoice_data["book_id"] . "' ");
                                    $img_data = $img->fetch_assoc();
                                    ?>
                                    <img src="<?php echo $img_data["img_path"]; ?>" width="70px" />
                                    <span class="text-black pe-1" style="font-size: 13px;"><?php echo $invoice_data["title"] ?></span>
                                </td>
                                <td><span class="text-black" style="font-size: 14px;">Rs.<?php echo $invoice_data["price"] ?>.00</span></td>
                                <td class="text-center"><span class="text-black" style="font-size: 14px;"><?php echo $invoice_data["invoice_qty"] ?></span></td>
                                <td class="text-end"><span class="text-black" style="font-size: 14px;">Rs.<?php echo $invoice_data["price"] * $invoice_data["invoice_qty"] ?>.00</span></td>
                            </tr>

                        </table>
                    </div>
                </div>

                <?php
                $city_rslt = Database::search("SELECT * FROM `city` WHERE `city_id` = '" . $location_data['city_id'] . "' ");
                $city_data = $city_rslt->fetch_assoc();

                $delivery;

                if ($city_data['district_district_id'] == 22) {
                    $delivery = $invoice_data['delivery_fee_colombo'];
                } else {
                    $delivery = $invoice_data['delivery_fee_other'];
                }

                $subtotal = $invoice_data['price'] * $invoice_data['invoice_qty'];
                ?>

                <div class="row sub-ttl mt-4">
                    <div class="col-12 d-flex gap-2 flex-column justify-content-end align-items-end">
                        <div class="col-4 d-flex justify-content-between">
                            <span class="text-black">SUBTOTAL(<?php echo $invoice_rslt->num_rows; ?>)</span>
                            <span class="text-black">RS.<?php echo $subtotal ?>.00</span>
                        </div>
                        <div class="col-4 d-flex justify-content-between">

                            <span class="text-black">DELIVERY FEE</span>
                            <span class="text-black">RS.<?php echo $delivery ?>.00</span>
                        </div>
                        <div class="col-4 d-flex pt-2 justify-content-between sum">
                            <h6 class="fw-medium">GRAND TOTAL</h6>
                            <h6 class="fw-medium">RS.<?php echo $subtotal + $delivery ?>.00</h6>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 gap-2 d-flex">
                        <div class="d-flex justify-content-center align-items-center">
                            <span><i class="bi bi-file-earmark-text fs-3"></i></span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-medium text-black">Note:</span>
                            <span style="font-size: 13px;">Here we can write a additional notes for the client to get a better understanding of this invoice.</span>
                        </div>
                    </div>

                </div>

            </div>

            <div class="container my-4">
                <div class="row mb-5">
                    <div class="col-12 d-flex gap-3">
                        <button onclick="print_invoice();" class="border-0 bg-transparent">
                            <i class="bi bi-printer"></i>
                            <span class="text-black">Print</span>
                        </button>
                        <button class="border-0 bg-transparent">
                            <i class="bi bi-download"></i>
                            <span class="text-black">Download</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php
        }

        ?>


        <?php include 'components/footer.php'; ?>


        <script src="./js/script.js"></script>
        <script src="./js/bootstrap.bundle.js"></script>
    </body>

    </html>
<?php
}
?>