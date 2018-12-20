<?php
    $strrsub_steamurl = $_GET['id'];
    //include_once 'application/steam_api/get_steam.php';
?>
<h4><?php echo $detail_product['product_name']; ?></h4>
<div class="row" style="padding: 0px 10px 0px 10px;">
    <div class="col-lg-5">
        <img style="border-radius: 8px 8px 8px 8px;" src="images/product/<?php echo $detail_product['product_image']; ?>" alt="<?php echo $detail_product['product_name']; ?>" class="img-responsive" style="margin-bottom: 5px;"/>
    </div>
    <div class="col-lg-7">
        <div class="well well-sm" style="margin: 0px 0px 5px 0px;">
            <strong>ชื่อสินค้า : </strong>
                <?php echo $detail_product['product_name']; ?>
            <br/>
            <strong>Platform : </strong>
                <?php 
                    $sql_product_platform = 'SELECT * FROM product_platform WHERE platform_id = "'.$detail_product['product_platform'].'"';
                    $query_product_platform = $connect->query($sql_product_platform);
                    $product_platform = $query_product_platform->fetch_assoc();
                    if($product_platform){
                        echo $product_platform['platform_name'];
                    } else {
                        echo 'ไม่ระบุ';
                    }
                ?>
            <br/>
            <strong>รูปแบบสินค้า : </strong>
                <?php 
                    $sql_product_type = 'SELECT * FROM product_type WHERE type_id = "'.$detail_product['product_type'].'"';
                    $query_product_type = $connect->query($sql_product_type);
                    $product_type = $query_product_type->fetch_assoc();
                    if($product_type){
                        echo $product_type['type_name'];
                    } else {
                        echo 'ไม่ระบุ';
                    }
                ?>
            <br/>
            <strong>สถานะ : </strong>
                <?php 
                    $sql_product_status = 'SELECT * FROM product_status WHERE status_id = "'.$detail_product['product_status'].'"';
                    $query_product_status = $connect->query($sql_product_status);
                    $product_status = $query_product_status->fetch_assoc();
                    if($product_status){
                        echo $product_status['status_name'];
                    } else {
                        echo 'อื่นๆ';
                    }
                ?>
        </div>
    </div>
    <div class="col-lg-7">
            <?php if($detail_product['product_sale_status'] == '1'): ?>
                <div class="well well-sm text-center"  style="margin: 0px 0px 5px 0px;">
                    <script type="text/javascript" src="<?php echo $config['site_url']; ?>/js/jquery.countdown.js"></script>
                    <div id="sale-timer"></div>
                    <script type="text/javascript">
                        $('#sale-timer').countdown('<?php echo $detail_product['product_sale_timer']; ?>', function(event) {
                            var getHours = event.offset.totalDays * 24 + event.offset.hours;
                            var $this = $(this);
                            if(getHours >= 48){
                                $this.html(event.strftime('<h4 style="margin-top: 10px;"><strong style="color:#2ea72e;">ราคาโปรโมชัน!</strong> ข้อเสนอนี้จะจบลงใน %D วัน %H ชั่วโมง %M นาที %S วินาที</h4>'));
                            } else {
                                $this.html(event.strftime('<h4 style="margin-top: 10px;"><strong style="color:#2ea72e;">ราคาโปรโมชัน!</strong> ข้อเสนอนี้จะจบลงใน '+getHours+' ชั่วโมง %M นาที %S วินาที</h4>'));
                            }
                        });
                    </script>
                </div>
            <?php endif;?>
            <div class="well well-sm" style="margin: 0px; padding: 0px 15px;">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table" style="margin: 0px;">
                                <tr>
                                    <?
                                        $unit_product = explode("-", $detail_product['product_rate']);
                                        if($detail_product['product_rate'] == "00")
                                        {
                                            $detail_product_rate = "1";
                                        }
                                        else
                                        {
                                            $detail_product_rate = $unit_product[0];
                                        }
                                    ?>
                                    <td class="col-md-2" style="border: 0px;"><strong>ราคา : </strong></td>
                                    <td class="col-md-10" style="border: 0px;">
                                        <?=number_format($detail_product['product_price'],2);?> <span class="price_baht">บาท</span> ได้ <b><?=$detail_product_rate;?></b> <? echo $unit_product[1]; ?><br/>
                                        <?=number_format($detail_product['product_price']/(85/100),2);?> <span class="price_true1">ทรู</span><span class="price_true2">มันนี่</span> ได้ <b><?=$detail_product_rate;?></b> <? echo $unit_product[1]; ?>
                                    </td>
                                </tr>
                                <?php if($_SESSION['member_uid'] != NULL){ ?>
                                <tr>
                                    <td class="col-md-12" colspan="2" style="border: 0px;"><strong>จำนวนเงินของคุณ : </strong><?=number_format($member['credit'],2);?> บาท</td>
                                </tr>
                                <tr>
                                    <?
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
                                    <?
                                    if($detail_product['product_status'] == '2')
                                    {
                                        ?>
                                        <td class="col-md-12" colspan="2" style="border: 0px;"><strong>สินค้าเหลือ : </strong>0 <?=$unit_product[1];?> (สินค้าหมด)</td>
                                        <?
                                    }
                                    else
                                    {
                                        ?>
                                        <td class="col-md-12" colspan="2" style="border: 0px;"><strong>สินค้าเหลือ : </strong><?=$exp_stock[0];?> <?=$unit_product[1];?></td>
                                        <?
                                    }
                                    ?>
                                    
                                </tr>
                                <? } ?>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4" style="margin-top: 5px;">
                        <a href="order.php?id=<?php echo $detail_product['product_id']; ?>" class="btn btn-primary btn-lg btn-block sweet" style="background-color:#ed5565;border-color:#ed5565" role="button"><i class="fa fa-credit-card"></i> ไปยังส่วนชำระเงิน</a>
                    </div>
                </div>
            </div>
    </div>
    <div class="col-lg-12">
            <div class="well well-sm" style="margin: 5px 0px;">
                <h4 style="margin: 0px;">รายละเอียด : </h4>
                <p><?php echo html_entity_decode($detail_product['product_detail']); ?></p>
                <?php 
                    /*if($jtoarr_app[$strrsub_steamurl]['success'] == 1){
                        echo $steam_search_pspec; 
                    } else {
                        if($jtoarr_sub[$strrsub_steamurl]['success'] == 1){
                            echo '<ul>';
                            for($i=0; $i<=count($steam_search_insub)-1; $i++){
                                echo '<li>'.$jtoarr_sub[$strrsub_steamurl]['data']['apps'][$i]['name'].'</li>';
                            }
                            echo '</ul>';
                        }
                    }*/
                ?>
            </div>
    </div>
</div>