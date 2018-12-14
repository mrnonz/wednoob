<?
                                            if($detail_product['product_rate'] == "1")
                                            {
                                                $product_rate_piece = $detail_product['product_price'];
                                                $product_rate_disabled = "readonly=''";
                                            }
                                            elseif($detail_product['product_rate'] == "00")
                                            {
                                                $product_rate_piece = $detail_product['product_price'];
                                                $product_rate_disabled = "readonly=''";
                                            }
                                            else
                                            {
                                                $product_rate_piece = $detail_product['product_price'];
                                                $product_rate_disabled = "";
                                            }
                                            if(isset($_GET['status']) && $_GET['status'] == 5)
                                            {
                                                $_SESSION["member_status"] = "5";
                                            }
                                            $query_user_name = $connect->query("SELECT * FROM member WHERE uid = '".$_SESSION["member_uid"]."'");
                                            $fetch_user_name = $query_user_name->fetch_assoc();
                                            if($fetch_user_name)
                                            {
                                                if($detail_product['product_rate'] == "00")
                                                {
                                                    $point_user = $detail_product['product_price'];
                                                    $amount_order = "1";
                                                }
                                                else
                                                {
                                                    $point_user = $fetch_user_name['credit'];
                                                    $amount_order = $detail_product['product_rate'];
                                                }
                                            }
                                            else
                                            {
                                                if($detail_product['product_rate'] == "00")
                                                {
                                                    $point_user = $detail_product['product_price'];
                                                    $amount_order = "1";
                                                }
                                                else
                                                {
                                                    $point_user = "0.00";
                                                    $amount_order = $detail_product['product_rate'];
                                                }
                                            }

                                            if($detail_product['product_rate'] > "1")
                                            {
                                                $point_buy = $point_user * $amount_order;
                                            }
                                            else
                                            {
                                                $point_buy = "1";
                                            }
                                        ?>
<h4>
    ชำระเงิน : <?php if($detail_product){echo $detail_product['product_name'];} else {echo $steam_search_pname;}?>
</h4>
<div class="row" style="padding: 0px 10px 0px 10px;">
    <div id="rs_neworder"></div>
    <form name="neworder_frm" id="neworder_frm" method="POST" action="javascript:void(0);">
        <div class="col-lg-5">
            <img style="border-radius: 8px 8px 8px 8px;" src="<?php if($detail_product){echo 'images/product/'.$detail_product['product_image'];} else {echo $steam_search_pimageurl;}?>" alt="<?php if($detail_product){echo $detail_product['product_name'];} else {echo $steam_search_pname;}?>" class="img-responsive" style="margin-bottom: 5px;"/>
        </div>
        <hr/>
        <div class="col-lg-7">
            <input type="hidden" name="product_id" id="product_id" value="<?php echo $connect->real_escape_string($_GET['id']); ?>">
            <input type="hidden" name="product_name" id="product_name" value="<?php if($detail_product){echo $detail_product['product_name'];} else {echo $steam_search_pname.' ('.$steam_search_ptype.')';}?>">
            <div class="panel panel-primary" style="border-color:#da4453">
            <?
                $sql_product_platform = 'SELECT * FROM product_platform WHERE platform_id = "'.$detail_product['product_platform'].'"';
                $query_product_platform = $connect->query($sql_product_platform);
                $product_platform = $query_product_platform->fetch_assoc();
            ?>
            <div class="panel-heading" style="color:#fff;background-color:#da4453;border-color:#da4453"><center>รายละเอียดสินค้า <?php if($detail_product){echo $detail_product['product_name'];} else {echo $steam_search_pname.' ('.$steam_search_ptype.')';}?></center></div>
                <div classs="panel-body">
                    <div class="list-group">
                        <a class="list-group-item ">
                            <strong>Platform:</strong> 
                            <span class="badge"><?php if($detail_product){
                                
                                if($product_platform){
                                    echo $product_platform['platform_name'];
                                    echo '<input type="hidden" name="product_platform" id="product_platform" value="'.$product_platform['platform_name'].'">';
                                } else {
                                    echo 'ไม่ระบุ';
                                    echo '<input type="hidden" name="product_platform" id="product_platform" value="ไม่ระบุ">';
                                }
                            } else {
                                echo 'STEAM';
                                echo '<input type="hidden" name="product_platform" id="product_platform" value="STEAM">';
                            }?></span>
                        </a>
                        <a class="list-group-item ">
                            <strong>รูปแบบสินค้า:</strong>
                            <span class="badge"><?if($detail_product){
                                $sql_product_type = 'SELECT * FROM product_type WHERE type_id = "'.$detail_product['product_type'].'"';
                                $query_product_type = $connect->query($sql_product_type);
                                $product_type = $query_product_type->fetch_assoc();
                                if($product_type){
                                    echo $product_type['type_name'];
                                    echo '<input type="hidden" name="product_type" id="product_type" value="'.$product_type['type_name'].'">';
                                } else {
                                    echo 'ไม่ระบุ';
                                    echo '<input type="hidden" name="product_type" id="product_type" value="ไม่ระบุ">';
                                }
                            } else {
                                echo 'Gift (Steam URL)';
                                echo '<input type="hidden" name="product_type" id="product_type" value="Gift (Steam URL)">';
                            }?></span>
                        </a>
                        <a class="list-group-item ">
                            <strong>สถานะ:</strong>
                            <span class="badge"><?if($detail_product){
                                $sql_product_status = 'SELECT * FROM product_status WHERE status_id = "'.$detail_product['product_status'].'"';
                                $query_product_status = $connect->query($sql_product_status);
                                $product_status = $query_product_status->fetch_assoc();
                                if($product_status){
                                    echo $product_status['status_name'];
                                } else {
                                    echo 'อื่นๆ';
                                }
                            } else {
                                echo 'พร้อมส่ง';
                            }?></span>
                        </a>
                        <a class="list-group-item ">
                            <strong>ราคา:</strong>
                            <span class="badge"><?
                            $unit_product = explode("-", $detail_product['product_rate']);
                            if($detail_product){
                                if($detail_product['product_rate'] == "00")
                                {
                                    $detail_product_rate = "1";
                                }
                                else
                                {
                                    $detail_product_rate = $unit_product[0];
                                }
                                echo number_format($detail_product['product_price'],2).' บาท ได้ <b>'.$detail_product_rate.'</b> '.$unit_product[1];
                                echo '<input type="hidden" name="product_price" id="product_price" value="'.number_format($detail_product['product_price'],2,'.','').'">';
                            } else {
                                echo number_format($steam_search_pprice*$webinfo['webinfo_ratesteam'],2).' <span class="price_baht">บาท</span>';
                                echo '<input type="hidden" name="product_price" id="product_price" value="'.number_format($steam_search_pprice*$webinfo['webinfo_ratesteam'],2,'.','').'">';
                            }?></span>
                        </a>
                        <a class="list-group-item ">
                            <strong>จำนวนเงินของคุณ:</strong>
                            <span class="badge"><? echo number_format($member['credit'],2); ?> บาท</span>
                        </a>
                        <a class="list-group-item ">
                            <?php
                            if($detail_product){
                                if($member['credit'] >= $detail_product['product_price']){
                                    if($detail_product['product_stock'] > 0)
                                        {
                                            $product_amount_stock = $detail_product['product_stock'];
                                            $exp_stock = explode(".", $product_amount_stock);
                                        }
                                        else
                                        {
                                            $exp_stock = "0";
                                        }
                                    ?>
                                    <strong>สินค้าเหลือ : </strong><span class="badge"><?=$exp_stock[0];?></span>
                                    <?
                                } else {
                                    echo '<strong>ขาดเงินอีก : </strong><span class="badge">'.number_format($detail_product['product_price']-$member['credit'],2).' บาท</span>';
                                }
                            } else {
                                if($member['credit'] >= $steam_search_pprice*$webinfo['webinfo_ratesteam']){
                                    echo '<strong>หลังชำระเหลือ : </strong><span class="badge">'.number_format($member['credit']-$steam_search_pprice*$webinfo['webinfo_ratesteam'],2).' บาท</span>';
                                } else {
                                    echo '<strong>ขาดเงินอีก : </strong><span class="badge">'.number_format($steam_search_pprice*$webinfo['webinfo_ratesteam']-$member['credit'],2).' บาท</span>';
                                }
                            }
                            ?>
                        </a>
                        <hr/>
                        <?php if(isset($_SESSION['member_uid']) != NULL): ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_amount_want" style="font-size:14px;">กรอกจำนวนสินค้า  จำนวนขั้นต่ำ 1 <? echo $unit_product[1]; ?><br/><small> (จำเป็น)</small>: </label>
                                    <div class="input-group" style="height: 50px;">
                                        <input type="hidden" name="member_uid" id="member_uid" value="<?php echo $fetch_user_name['uid']; ?>">
                                        <input type="hidden" name="order_money_want" id="order_money_want" class="form-control" maxlength="11" placeholder="เช่น 50" value="<?php echo $detail_product['product_price']; ?>" readonly="">
                                        <input type="number" style="height: 50px;" name="order_amount_want" id="order_amount_want" class="form-control" maxlength="11" placeholder="เช่น 1" value="<? echo $point_buy; ?>">
                                        <div class="input-group-addon"><? echo $unit_product[1]; ?></div>

                                        <input type="hidden" name="order_rate" id="order_rate" class="form-control" value="<?php echo $detail_product_rate; ?>">
                                        <input type="hidden" name="order_original_rate" id="order_original_rate" class="form-control" value="<?php echo $amount_order; ?>">
                                        <input type="hidden" name="product_in_stock" id="product_in_stock" class="form-control" value="<?php echo $exp_stock[0]; ?>">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_detail" style="font-size:15px;">รายละเอียด<small>(Roblox เข้ากลุ่มเเละใส่ชื่อในเกม GT  GrowID เเละ World)</small> </label>
                                    <input type="text" style="height: 50px; margin-top:18px;" name="order_detail" id="order_detail" class="form-control" maxlength="255" placeholder="Roblox ใส่ชื่อในเกม GT ใส่ ID&WORLD">
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <b>อย่าลืมกรอกข้อมูลเพื่อรับสินค้าด้วยนะครับ  และ สามารถดูสถาณะการจัดส่งสินค้าได้ที่ ประวัติการสั่งซื้อ</b> <small>ใส่ข้อมูลเพื่อรับสินค้า (Roblox เข้ากลุ่มใหม่ทุกครั้ง (วิธีเข้าเลื่อนกลับไปหน้าที่เเล้ว กด "คลิกที่นี่" เป็นลิงก์นำไปดูกลุ่ม Roblox) เเละใส่ชื่อในเกม ส่วน GT กรอก GrowID เเละ World ในเวิร์ลต้องมี Display box หรือ Donation Box ) </small>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    
                    
                </table>
            </div>
        </div>
        <div class="col-lg-8" style="padding-top: 15px;">
            <small>ระบบจะตัดยอดเงินของคุณอัตโนมัติเมื่อกดปุ่ม ชำระเงินทันที</small>
        </div>
        <div class="col-lg-4 text-right">
            <?php 
                if(isset($_SESSION['member_uid']) == NULL){
                    echo '<button class="btn btn-default disabled" disabled>กรุณาเข้าสู่ระบบก่อนสั่งซื้อ</button>';
                } else {
                    if($detail_product['product_status'] == '2'){
                        echo '<button class="btn btn-danger disabled" disabled>ขออภัยสินค้าหมด</button>';
                    } else {
                        # Check Promotion Expired
                        $promotion_expired = $detail_product['product_sale_timer'];
                        $promotion_today = date('Y/m/d H:i');
                        if($detail_product['product_sale_status'] == '1' && $promotion_today >= $promotion_expired){
                            echo '<button class="btn btn-danger disabled" disabled>หมดโปรโมชั่น!! รออัพเดทราคา</button>';
                        } else {
                            if($detail_product){
                                $product_total = $detail_product['product_price'];
                            } else {
                                $product_total = number_format($steam_search_pprice*$webinfo['webinfo_ratesteam'],2,'.','');
                            }
                            if(number_format($member['credit'],2,'.','') >= $product_total) {
                                $query_link = $connect->query("SELECT * FROM product_event WHERE event_type = '".$detail_product['product_platform']."' LIMIT 1");
                                $numrow_link = $query_link->num_rows;
                                $fetch_query_link = $query_link->fetch_assoc();
                                //echo '<input type="text" name="link_goto" id="link_goto" value="'.$fetch_query_link['event_go'].'"';
                                
                                if($numrow_link == '1')
                                {
                                    ?>
                                    <input name="link_goto" id="link_goto" type="hidden" value="<?php echo $fetch_query_link['event_go']; ?>">
                                    <?
                                }
                                else
                                {
                                    ?>
                                    <input name="link_goto" id="link_goto" type="hidden" value="index.php">
                                    <?
                                }
                                echo '<button type="submit" name="neworder_btn" id="neworder_btn" class="btn btn-primary" style="background-color:#ed5565;border-color:#ed5565" onclick="NewOrder()">ชำระเงินทันที</button>';
                                
                            } else {
                                echo '<button class="btn btn-default disabled" style="border-color:#ed5565" disabled>จำนวนเงินไม่พอชำระค่าสินค้า</button>';
                            }
                        }
                    }
                }
            ?>
        </div>
    </form>
</div>