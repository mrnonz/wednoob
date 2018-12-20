<div class="col-lg-2 col-md-2 text-left">
    <button type="button" class="btn btn-primary btn-block" onClick="location.href='?page=product&action=insert'">
        <i class="glyphicon glyphicon-plus"></i> เพิ่มสินค้าใหม่
    </button>
</div>
<div class="col-lg-1 col-md-1 text-left">
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            เพิ่มเติม
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li><a href="?page=product&action=mn_category">จัดการหมวดหมู่สินค้า</a></li>
            <li><a href="?page=product&action=mn_platform">จัดการ Platform</a></li>
            <li><a href="?page=product&action=mn_type">จัดการรูปแบบสินค้า</a></li>
        </ul>
    </div>
</div>
<div class="col-lg-9 col-md-9">
    <form method="GET" class="form-horizontal" role="form" action="">
        <div class="input-group">
            <input type="hidden" class="form-control" name="page" value="product"/>
            <input type="text" class="form-control" name="keyword" value="<?php echo $_GET["keyword"];?>" placeholder="ค้นหาสินค้าโดยใช้ ชื่อสินค้า , รหัสสินค้า หรือ คำค้น" autocomplete="off" required=""/>
            <span class="input-group-btn">
                <input type="submit" class="btn btn-primary" value="ค้นหา"/>
            </span>
        </div>
    </form>
</div>

<?php
    if($_GET['keyword'] != NULL){
        $sql_product = "SELECT * FROM product WHERE (product_name LIKE '%".$_GET["keyword"]."%' or product_tag LIKE '%".$_GET["keyword"]."%' or product_id LIKE '%".$_GET["keyword"]."%' ) ";
    } else {
        $sql_product = "SELECT * FROM product ";
    }
    // Select DB - product
    $query_product = $connect->query($sql_product);
    $numrows_product = $query_product->num_rows;
    $Per_Page = 20;
    $Page = $_GET["p"];
    if(!$_GET["p"]){
        $Page=1;
    }
    $Prev_Page = $Page-1;
    $Next_Page = $Page+1;
    $Page_Start = (($Per_Page*$Page)-$Per_Page);
    if($numrows_product<=$Per_Page){
        $Num_Pages =1;
    } elseif(($numrows_product % $Per_Page)==0){
        $Num_Pages =($numrows_product/$Per_Page) ;
    } else {
        $Num_Pages =($numrows_product/$Per_Page)+1;
        $Num_Pages = (int)$Num_Pages;
    }
    $sql_product .= " ORDER BY product_sale_status != 1, product_id ASC LIMIT $Page_Start , $Per_Page";
    $query_product = $connect->query($sql_product);
?>
<div class="col-lg-12 table-responsive" style="margin-top: 20px;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center col-md-1">รหัสสินค้า</th>
                <th class="text-center col-md-2"></th>
                <th class="col-md-7">ชื่อ/รายละเอียด</th>
                <th class="text-right col-md-1">ราคา/ชิ้น</th>
                <th class="text-center col-md-1">จัดการ</th>
            </tr>
        </thead>
        <?php if($numrows_product == '0'): ?>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">ไม่พบรายการสินค้า</td>
            </tr>
        </tbody>
        <?php else: ?>
        <tbody>
        <?php 
        while($product = $query_product->fetch_assoc()):
            // สถานะสินค้า
            if($product['product_status'] == '1'){
                $product_status = '<a href="?page=product&action=soldout&id='.$product['product_id'].'" title="เปลี่ยนเป็นสินค้าหมด?"><span class="label label-default">พร้อมส่ง</span></a>';
            } else {
                $product_status = '<a href="?page=product&action=availability&id='.$product['product_id'].'" title="เปลี่ยนเป็นพร้อมส่ง?"><span class="label label-danger">สินค้าหมด</span></a>';
            }
            // สินค้าแนะนำ
            if($product['product_recomment'] == '0'){
                $product_recomment = '<a href="?page=product&action=activerecomment&id='.$product['product_id'].'" title="Active?"><span class="label label-default">Deactive</span></a>';
            } else {
                $product_recomment = '<a href="?page=product&action=deactiverecomment&id='.$product['product_id'].'" title="Deactive?"><span class="label label-success">Active</span></a>';
            }
            // สินค้าลดราคา
            if($product['product_sale_status'] == "1"){
                $product_sale_status = 'class="success"';
                $product_sale_timer_create = date_create($product['product_sale_timer']);
                $product_sale_timer = date_format($product_sale_timer_create, 'd/m/Y H:i');
            } else {
                $product_sale_status = '';
            }
        ?>
            <tr <?php echo $product_sale_status; ?>>
                <td class="text-center"><?php echo $product['product_id']; ?></td>
                <td class="text-center"><img src="images/product/<?php echo $product['product_image']; ?>" class="img-responsive img-rounded"/></td>
                <td>
                    <strong><?php echo $product['product_name']; ?></strong>
                    <?php
                    if($product['product_sale_status'] == "1"){
                        echo ' <small>(โปรโมชั่นถึง : '.$product_sale_timer.')</small>';
                    }
                    ?><br/>
                    สถานะ : 
                    <?php
                        $sql_autosend_chk_stock_status = 'SELECT * FROM orderreceived WHERE orderreceived_product_id = "'.$product['product_id'].'" AND orderreceived_order_id = ""';
                        $query_autosend_chk_stock_status = $connect->query($sql_autosend_chk_stock_status);
                        $autosend_chk_stock_status = $query_autosend_chk_stock_status->num_rows;
                        if($autosend_chk_stock_status > '0'){
                            echo "<span class='label label-success'>ส่งอัตโนมัติ ($autosend_chk_stock_status)</span>";
                        } else {
                            echo $product_status;
                        }
                    ?>
                    <br/>
                    สินค้าแนะนำ : <?php echo $product_recomment; ?>
                </td>
                <td class="text-right"><?php echo $product['product_price']; ?> : <?php echo $product['product_rate']; ?></td>
                <td class="text-center">
                    <a href="?page=product&action=edit&id=<?=$product['product_id'];?>" title="แก้ไขสินค้า"><i class="glyphicon glyphicon-edit"></i></a>
                    <a href="?page=product&action=delete&p=<?=$Page;?>&id=<?=$product['product_id'];?>" title="ลบทิ้ง" onclick="return confirm('คุณกำลังจะลบสินค้าภายในระบบ คุณแน่ใจแล้วหรือไม่?')"><i class="glyphicon glyphicon-trash"></i></a>
                    <a href="<?=$config['site_url'];?>/product.php?id=<?=$product['product_id'];?>" title="ดูสินค้า" target="_blank"><i class="glyphicon glyphicon-search"></i></a>
                </td>
            </tr>
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