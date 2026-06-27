<?php
    include "../connection.php";

    $district_id = $_GET["id"];

    $city = Database::search("SELECT * FROM  `city` WHERE `district_district_id` = '".$district_id."' ");
    $citys = $city->num_rows;

    for($x=0;$x < $citys;$x++){
        $cityData =$city->fetch_assoc();
        ?>
            <option value="<?php echo $cityData["city_id"];?>">
                <?php echo $cityData["city_name"];?>
            </option>
        <?php
    }
