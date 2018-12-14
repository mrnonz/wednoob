<?php

/* 
 * This site Design with Bootstrap and Develop source code by. DJdai Yodsapon
 * Can contact DJdai at kuzaa516@gmail.com or +66800257281 (Thailand Number)
 */
            include_once '../_config.php'; // Database SQL STEAM
            
            $steamoriurl = $_POST['steam_url']; //ข้อความที่จะแปลงเป็นตัวเลข
            $steamnewurl = "0123456789";
            $n = strlen($steamoriurl);
            $x = strlen($steamnewurl);
            $strrsub_steamurl = "";
            for($i=0;$i<=$n;$i++){
                for($j=0;$j<=$x;$j++){
                    if($steamoriurl[$i] == $steamnewurl[$j]){
                        $strrsub_steamurl.=$steamnewurl[$j];
                    }
                }
            }
            include_once 'get_steam.php'; //Steam API
            if($steam_search_success == 1){
                
                $steam_url_percentagerate = $connect->query('SELECT * FROM webinfo WHERE webinfo_id = "1"');
                $rs_steam_url_percentagerate = $steam_url_percentagerate->fetch_assoc();
                
                $steam_url_priceinstore = $connect->query('SELECT * FROM product WHERE product_id = "'.$steam_search_pid.'"');
                $rs_steam_url_priceinstore = $steam_url_priceinstore->fetch_assoc();

                if($steam_search_pprice <= '0'){
                    echo '<div class="text-center" style="margin-top: 10px;">สินค้าไม่มีราคาหรือเป็นสินค้าฟรี ไม่สามารถสั่งซื้อได้</div>';
                } else {
?>
                <div style="margin-top: 10px;">
                    <div class="col-sm-3 text-center">
                        <img src="<?php echo $steam_search_pimageurl; ?>" class="img-responsive" alt="<?php echo $steam_search_pname; ?>"/>
                    </div>
                    <div class="col-sm-9">
                        <strong><?php echo $steam_search_pname; ?></strong> (<?php echo $steam_search_ptype; ?>)
                        <p>
                        ราคาบนสตีม : <del><?php echo number_format($steam_search_pprice,2); ?></del> บาท <br/>
                        <strong>ราคา :</strong>
                            <?php
                                if(isset($rs_steam_url_priceinstore)){
                                    echo number_format($rs_steam_url_priceinstore['product_price'],2).' <span class="price_baht">บาท</span>&nbsp;|&nbsp;';
                                    echo number_format($rs_steam_url_priceinstore['product_price']/(85/100),2).' <span class="price_true1">ทรู</span><span class="price_true2">มันนี่</span>';
                                } else {
                                    echo number_format($steam_search_pprice*$rs_steam_url_percentagerate['webinfo_ratesteam'],2).' <span class="price_baht">บาท</span>&nbsp;|&nbsp;';
                                    echo number_format($steam_search_pprice*$rs_steam_url_percentagerate['webinfo_ratesteam']/0.85,2).' <span class="price_true1">ทรู</span><span class="price_true2">มันนี่</span>';
                                }
                            ?>
                        </p>
                        <div class="text-right" style="margin-top: 30px;">
                            <button class="btn btn-primary" type="button" onclick="location.href='order.php?id=<?=$steam_search_pid;?>';">
                                <i class="fa fa-credit-card"></i> ไปยังส่วนชำระเงิน
                            </button>
                        </div>
                    </div>
                </div>
<?php
                }
            } else {
                echo '<div class="text-center" style="margin-top: 10px;">ไม่พบข้อมูล!</div>';
            }
?>