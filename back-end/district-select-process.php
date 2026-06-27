<?php
    include "../connection.php";

    $province_id = $_GET["id"];

    $districtTable = Database::search("SELECT * FROM `district` WHERE `province_province_id` = '".$province_id."' ");
    $districtTableNumRows = $districtTable->num_rows;

    for($x=0;$x<$districtTableNumRows;$x++){
        $districtData = $districtTable->fetch_assoc();
        ?>
            <option value="<?php echo $districtData["district_id"];?>">
                <?php echo $districtData["district_name"];?>
            </option>
        <?php
    }
