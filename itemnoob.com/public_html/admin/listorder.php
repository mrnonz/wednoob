<?php
    if(isset($_GET['action']) == 'cancel'){
        $select_orderstatus = $connect->query('SELECT * FROM `order` WHERE order_id = "'.$_GET['orderid'].'"')->fetch_assoc();
        if($select_orderstatus['order_status'] == '0'){
                $sql_update_orderstatus = 'UPDATE `order` SET order_status = "2" WHERE order_id = "'.$_GET['orderid'].'"';
                        $query_update_orderstatus = $connect->query($sql_update_orderstatus);

                $order_cancelcredit = $_GET['cancelcredit'];

                $sql_update_orderstatus = 'UPDATE `member` SET credit = credit+"'.$order_cancelcredit.'" WHERE uid = "'.$_GET['uid'].'"';
                        $query_update_orderstatus = $connect->query($sql_update_orderstatus);
                header('location: ?page=listorder&p='.$_GET['p']);
        } else {
	echo '<script>alert("รายการนี้ได้รับการจัดการเรียบร้อยแล้ว"); window.history.back();</script>';
        }
    }
?>
<h4>รายการสั่งซื้อ</h4><hr/>
<?php
    // Select DB - order
    $sql_order = "SELECT * FROM `order` ";
    $query_order = $connect->query($sql_order);
    $numrows_order = $query_order->num_rows;
    $Per_Page = 30;
    $Page = $_GET["p"];
    if(!$_GET["p"]){
        $Page=1;
    }
    $Prev_Page = $Page-1;
    $Next_Page = $Page+1;
    $Page_Start = (($Per_Page*$Page)-$Per_Page);
    if($numrows_order<=$Per_Page){
        $Num_Pages =1;
    } elseif(($numrows_order % $Per_Page)==0){
        $Num_Pages =($numrows_order/$Per_Page) ;
    } else {
        $Num_Pages =($numrows_order/$Per_Page)+1;
        $Num_Pages = (int)$Num_Pages;
    }
    $sql_order .= " ORDER BY order_id DESC LIMIT $Page_Start , $Per_Page";
    $query_order = $connect->query($sql_order);
?>
<div class="col-lg-12 table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Order ID</th>
                <th class="col-md-6">ชื่อสินค้า</th>
                <th class="col-md-2 text-center">วัน/เวลาที่สั่งซื้อ</th>
                <th class="col-md-2 text-center">วัน/เวลาที่จัดส่ง</th>
                <th class="col-md-2 text-center" colspan="2">จัดการ</th>
            </tr>
        </thead>
        <?php if($numrows_order == '0'): ?>
        <tbody>
            <tr>
                <td colspan="5" class="text-center">ไม่พบรายการสั่งซื้อ</td>
            </tr>
        </tbody>
        <?php else: ?>
        <tbody>
            <?php
                while($order = $query_order->fetch_assoc()):
                    //ดึงข้อมูลสมาชิกจากฐานข้อมูลสมาชิกโดยอ้างอิงค์จากรหัสสมาชิก
                    $get_member_order = $connect->query('SELECT * FROM member WHERE uid = "'.$order['order_uid'].'"')->fetch_assoc();
                    //ดึงข้อมูลรายการส่งสินค้าจากฐานข้อมูล orderreceived
                    $get_orderreceived = $connect->query('SELECT * FROM orderreceived WHERE orderreceived_order_id = "'.$order['order_id'].'"')->fetch_assoc();
            ?>
            <tr>
                <td class="text-center"><?php echo $order['order_id']; ?></td>
                <td><?php echo $order['order_product_name']; ?> - <small><?php echo $order['order_product_type']; ?> (<?php echo $order['order_product_id']; ?>)</small></td>
                <td class="text-center">
                    <?php 
                        // Convert datetime Format
                        $rs_order_date = date_create($order['order_date']);
                        echo date_format($rs_order_date, "d/m/Y H:i:s"); 
                    ?>
                </td>
                <td class="text-center">
                    <?php 
                    if($order['order_senddate'] == "0000-00-00 00:00:00"){
                        if($order['order_status'] == '2'){ 
                            echo 'รายการที่ถูกยกเลิก';
                        } else {
                            echo 'อยู่ระหว่างการจัดส่ง';
                        }
                    } else {
                        // Convert datetime Format
                        $rs_order_senddate = date_create($order['order_senddate']);
                        echo date_format($rs_order_senddate, "d/m/Y H:i:s"); 
                    }
                    ?>
                </td>
                <td class="text-center">
                    <?php
                    if($order['order_status'] == '0'){ 
                        echo '<a href="#" data-toggle="modal" data-target="#send-order-'.$order['order_id'].'" title="จัดส่ง?"><div class="label label-warning">จัดส่ง</div></a> ';
                        echo '<a href="?page=listorder&action=cancel&p='.$Page.'&orderid='.$order['order_id'].'&uid='.$order['order_uid'].'&cancelcredit='.$order['order_total'].'" title="ยกเลิก?" onclick="return confirm(\'ยกเลิกรายการสั่งซื้อหมายเลข '.$order["order_id"].' และคืนเงินจำนวน '.$order["order_total"].' บาท ให้กับลูกค้า? \')"><div class="label label-danger">ยกเลิก</div></a>';
                    } elseif($order['order_status'] == '1') {
                        echo '<span class="label label-success">จัดส่งเรียบร้อย</span>';
                    } else {
                        echo '<span class="label label-danger">ยกเลิกรายการ</span>';
                    }
                    ?>
                </td>
                <td class="text-center"><a href="javascript:showotherdetail('<?php echo $order['order_id']; ?>');" title="รายละเอียดเพิ่มเติม"><i class="glyphicon glyphicon-chevron-down"></i></a></td>
            </tr>
            <tr id="<?php echo $order['order_id']; ?>" style="display:none">
                <td colspan="6">
                    <div class="well well-sm"><b>จัดส่งที่ : </b><?php if($order['order_detail'] != NULL){ echo $order['order_detail']; } else { echo 'ในเว็บหน้าประวัติการสั่งซื้อ'; }; ?></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="col-md-8">CD-Key/Code/URL</th>
                                    <th class="col-md-2">จำนวนเงิน</th>
                                    <th class="col-md-2 text-right">จำนวนที่ต้องส่ง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php 
                                        if($get_orderreceived['orderreceived_key'] == NULL){
                                            echo 'ไม่มีบันทึก';
                                        } else {
                                            // ถอดรหัสข้อมูล
                                            $password = md5($order['order_uid'].'-'.$order['order_id']);
                                            $method = "aes-256-cbc";
                                            $get_orderreceived_data = openssl_decrypt($get_orderreceived['orderreceived_key'], $method, $password);
                                            echo $get_orderreceived_data;
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $order['order_total']; ?> บาท</td>
                                    <td class="text-right"><?php echo $order['order_total_piece']; ?> ชิ้น</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="col-md-10">ข้อมูลผู้สั่งซื้อ</th>
                                    <th class="col-md-2 text-center">IP Address ผู้สั่งซื้อ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-10"><a href="?page=member&keyword=<?php echo $get_member_order['username']; ?>" target="_blank"><?php echo $get_member_order['username']; ?></a></td>
                                    <td class="col-md-2 text-center"><?php echo $order['order_ip']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
<!-- Start Send Order -->
<div class="modal fade" id="send-order-<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="send-order-<?php echo $order['order_id']; ?>-Label">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="sendorder_frm" method="POST" action="" onsubmit="return confirm('ยืนยันการจัดส่งสินค้าให้กับรายการสั่งซื้อหมายเลข <?php echo $order["order_id"]; ?> ?')">
            <div class="modal-header">
                <h4 class="modal-title">จัดส่งสินค้า - <?php echo $order["order_id"]; ?></h4>
            </div>
            <div class="modal-body">
                <div class="well well-sm">
                    <strong>ชื่อสินค้า : </strong> <?=$order['order_product_name'];?> (<?=$order['order_product_id'];?>)<br/>
                    <strong>ID สินค้า : </strong> <?=$order['order_product_id'];?><br/>
                    <strong>รูปแบบสินค้า : </strong> <?=$order['order_product_type'];?><br/>
                    <strong>ผู้สั่งซื้อ : </strong><?=$get_member_order['username'];?> (<?=$order['order_ip']; ?>)<br/>
                    <strong>จำนวน : </strong><?=$order['order_total_piece'];?><br/>
                </div>
                <div class="col-md-12 form-group">
                    <label for="sendorder_data">รายละเอียด :</label>
                    <input type="text" name="sendorder_data" class="form-control" autocomplete="off" required=""/>
                    <small>เช่น CD-Key หรือ จัดส่งทางอีเมลล์เรียบร้อยแล้ว</small>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" name="sendorder_btn-<?php echo $order['order_id'];?>" class="btn btn-primary" value="จัดส่ง"/>
            </div>
            </form>
        </div>
    </div>
</div>
<?php
if(isset($_POST['sendorder_btn-'.$order['order_id']])){
    $sendorder_product_id = $order['order_product_id'];
    $sendorder_uid = $get_member_order['uid'];
    $sendorder_order_id = $order["order_id"];
    $sendorder_data = $_POST['sendorder_data'];
    if(!empty($sendorder_data)){
        // ตรวจสอบข้อมูลออเดอร์ซ้ำ
        $chksendorder = $connect->query('SELECT * FROM orderreceived WHERE orderreceived_order_id = "'.$sendorder_order_id.'"')->fetch_assoc();
        if($chksendorder){
            echo '<script>alert("Error! : รายการนี้มีข้อมูลอยู่แล้ว"); history.back();</script>';
        } else {
            // ทำการเข้ารหัสข้อมูล
            $password = md5($sendorder_uid.'-'.$sendorder_order_id);
            $method = "aes-256-cbc"; // รูปแบบการเข้ารหัส (method)
            $encrypted = openssl_encrypt($sendorder_data, $method, $password); //เข้ารหัส
            $sendorder_key = $encrypted;
            $sql_createsendorder = 'INSERT INTO orderreceived (orderreceived_product_id,orderreceived_uid,orderreceived_order_id,orderreceived_key) VALUES ("'.$sendorder_product_id.'","'.$sendorder_uid.'","'.$sendorder_order_id.'","'.$sendorder_key.'") ';
            $query_createsendorder = $connect->query($sql_createsendorder);
            if($query_createsendorder){
                // อัพเดทสถานะของออเดอร์
                $sql_update_orderstatus = 'UPDATE `order` SET order_status = "1" , order_senddate = "'.date('Y-m-d H:i:s').'" WHERE order_id = "'.$order['order_id'].'"';
                $query_update_orderstatus = $connect->query($sql_update_orderstatus);
                if($query_update_orderstatus){
                    $sql_update_stock = 'UPDATE `product` SET product_stock = product_stock-"'.$order['order_total_piece'].'" WHERE product_id = "'.$order['order_product_id'].'"';
                    $query_update_stock = $connect->query($sql_update_stock);
                    if($query_update_stock)
                    {
                        header('location:'.$_SERVER['HTTP_REFERER']);
                    }
                    else
                    {
                        echo '<script>alert("Error! : การบันทึกข้อมูลล้มเหลว กรุณาลองใหม่"); history.back();</script>';
                    }
                }
            } else {
                echo '<script>alert("Error! : การบันทึกข้อมูลล้มเหลว กรุณาลองใหม่"); history.back();</script>';
            } 
        }
    } else {
        echo '<script>alert("Error! : กรุณากรอกข้อมูลให้ครบทุกช่อง"); history.back();</script>';
    }
}
?>
<!-- End Send Order -->
            <?php endwhile; ?>
        </tbody>
        <?php endif; ?>
    </table>
</div>
<div class="col-lg-12">
    <nav class="text-center">
        <ul class="pagination">
        <?php
            for($i=1; $i<=$Num_Pages; $i++){
                if($i != $Page){
                    echo "<li><a href='?page=".$_GET['page']."&p=$i'>$i</a></li>";
                } else {
                    echo "<li class='active'><a href='?page=".$_GET['page']."&p=$i'>$i</a></li>";
                }
            }
        ?>
        </ul>
    </nav>
</div>