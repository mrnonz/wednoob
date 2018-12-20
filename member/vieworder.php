<?php
if(isset($_SESSION['member_uid']) != NULL):
    $order_id = sprintf("%08d",$_GET['orderid']);
    $order_key = str_replace(' ', '', $_GET['key']);
    if($order_key != NULL){
        $sql_vieworder = 'SELECT * FROM orderreceived WHERE orderreceived_order_id = "'.$order_id.'" ';
        $query_vieworder = $connect->query($sql_vieworder);
        $vieworder = $query_vieworder->fetch_assoc();
        if(str_replace(' ', '', str_replace('+', ' ', $vieworder['orderreceived_key'])) === $order_key){
            $password = md5($member['uid'].'-'.$order_id);
            $method = "aes-256-cbc";
            // ทำการถอดรหัสข้อมูล
            $decrypted = openssl_decrypt($vieworder['orderreceived_key'], $method, $password); 
            if($decrypted){
                // เรียกข้อมูลออเดอร์
                $query_vieworder_getdetail = $connect->query('SELECT * FROM `order` WHERE order_id = "'.$order_id.'"');
                $vieworder_getdetail = $query_vieworder_getdetail->fetch_assoc();
                // เรียกข้อมูลสินค้า
                $query_vieworder_getproduct = $connect->query('SELECT * FROM `product` WHERE product_id = "'.$vieworder['orderreceived_product_id'].'"');
                $vieworder_getproduct = $query_vieworder_getproduct->fetch_assoc();

?>
<h4>รับสินค้า - <?php echo $vieworder_getdetail['order_id']; ?></h4>
<div class="row" style="padding: 10px 10px;">
        <div class="col-md-4" style="margin-bottom: 15px;">
            <img src="images/product/<?php echo $vieworder_getproduct['product_image']; ?>" class="img-responsive img-rounded"/>
        </div>
        <div class="col-md-8" style="margin-bottom: 15px;">
            <strong>ชื่อสินค้า : <?php echo $vieworder_getdetail['order_product_name']; ?></strong>
        </div>
        <div class="col-md-8" style="margin-bottom: 15px;">
            <strong>วัน/เวลาที่จัดส่ง : </strong><?php $rs_vieworder_date = date_create($vieworder_getdetail['order_date']); echo date_format($rs_vieworder_date, "d/m/Y H:i:s"); ?>
        </div>
    <div class="col-md-8" style="margin-bottom: 15px;">
            <strong>ราคาสินค้า : </strong><?php echo $vieworder_getdetail['order_total']; ?> <span class="price_baht">บาท</span>
        </div>
        <div class="col-md-12">
            <label>สินค้าของคุณ : </label>
            <textarea name="cdkeycodeurl" class="form-control input-lg" onclick="this.focus();this.select()" readonly="readonly"><?php echo $decrypted; ?></textarea>
        </div>
</div>
<?php
            } else {
                header('location:'.$config['site_url'].'/member.php?page=historyorder');
            }
        } else {
            header('location:'.$config['site_url'].'/member.php?page=historyorder');
        }
    } else {
        header('location:'.$config['site_url'].'/member.php?page=historyorder');
    }
else : 
    header('location:'.$config['site_url']);
endif;
?>