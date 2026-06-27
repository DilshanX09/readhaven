 <div id="sidebar" class="sidenav">
      <a href="javascript:void(0)" class="closebtn" onclick="document.getElementById('sidebar').style.width = '0';">&times;</a>
      <div class="px-2">
           <div class="row">
                <div class="col-12">
                     <h5>IN STOCK</h5>
                </div>
                <div class="horizontal-line-mini"></div>
                <div class="col-12 srt-radio">
                     <div class="form-check pt-3">
                          <input class="form-check-input" name="check2" onclick="onlyOne(this , 0)" type="checkbox" id="stock_status">
                          <label class="form-check-label" for="flexCheckDefault">
                               <span style="font-size: 15px;">Available</span>
                          </label>
                     </div>
                </div>
                <div class="col-12 mt-3">
                     <h5>CATEGORIES</h5>
                </div>
                <div class="horizontal-line-mini"></div>
                <?php
                    $categoryResult = Database::search("SELECT * FROM `book_category`");
                    $categoryNumber = $categoryResult->num_rows;
                    for ($x = 0; $x < $categoryNumber; $x++) {
                         $categoryData = $categoryResult->fetch_assoc();
                    ?>
                     <div class="col-12 pt-3">
                          <div class="row">
                               <div class="d-flex justify-content-between align-items-center">
                                    <?php
                                        if ($category == $categoryData['category_name']) {
                                        ?>
                                         <span style="font-size: 16px; cursor: pointer;" class="highlighted-category" onclick="highlight_category(this, '<?php echo $categoryData['category_name'] ?>' , 0);"><?php echo $categoryData['category_name'] ?></span>
                                    <?php
                                        } else {
                                        ?>
                                         <span style="font-size: 16px; cursor: pointer;" onclick="highlight_category(this, '<?php echo $categoryData['category_name'] ?>' , 0);"><?php echo $categoryData['category_name'] ?></span>
                                    <?php
                                        }
                                        ?>
                                    <?php
                                        $oneByoneRead = Database::search("SELECT * FROM `books` WHERE `book_category_id` = '" . $categoryData['category_id'] . "' AND `active_active_id` = '1' ");
                                        ?>
                                    <span>(<?php echo $oneByoneRead->num_rows ?>)</span>
                               </div>
                          </div>
                     </div>
                     <div class="horizontal-line mt-1" style="margin-left: 11px;"></div>
                <?php
                    }
                    ?>
                <div class="col-12 mt-3">
                     <h5>FILTER BY PRICE</h5>
                </div>
                <div class="horizontal-line-mini"></div>
                <!-- <div class="row mt-3">
                     <div class="price-range-slider">
                          <span>Minimum</span>
                          <input type="range" class="form-range" id="priceRangeMin" min="0" value=" " max="50000" oninput="update_price_range()">
                          <span>Maximum</span>
                          <input type="range" class="form-range" id="priceRangeMax" min="0" value=" " max="50000" oninput="update_price_range()">
                     </div>
                     <div class="d-flex justify-content-between align-items-center mt-1">
                          <button onclick="filter(0);" type="button" class="buy rounded-5 px-3" style="font-size: 13px;">FILTER</button>
                          <div>
                               <span id="minPrice">Rs. 0</span> -
                               <span id="maxPrice">Rs. 0</span>
                          </div>
                     </div>
                </div> -->
           </div>
      </div>
 </div>