<?php
    $sql_product = 'SELECT * FROM product WHERE product_id = "'.$_GET['id'].'"';
    $query_product = $connect->query($sql_product);
    $product = $query_product->fetch_assoc();
    
    if(isset($_POST['edit_product_btn'])){
        $edit_product_name = $_POST['edit_product_name'];
        $edit_product_price = $_POST['edit_product_price'];
        $edit_product_category = $_POST['edit_product_category'];
        $edit_product_platform = $_POST['edit_product_platform'];
        $edit_product_type = $_POST['edit_product_type'];
        $edit_product_status = $_POST['edit_product_status'];
        $edit_product_tag = $_POST['edit_product_tag'];
        $edit_product_detail = htmlentities($_POST['edit_product_detail']);
        $edit_product_video = 'None';
        $edit_product_sale_status = $_POST['edit_product_sale_status'];
        $edit_product_sale_timer = $_POST['edit_product_sale_timer'];
        $edit_product_rate = $_POST['edit_product_rate'];
        $edit_product_stock = $_POST['edit_product_stock'];
        if(empty($edit_product_name) || empty($edit_product_price) || empty($edit_product_tag)){
            echo '<script>alert("Error! กรุณากรอกข้อมูลในช่องสำคัญให้ครบ"); window.history.back();</script>';
        } else {
            $sql_update_product = 'UPDATE product SET ';
            $sql_update_product .= ' product_name = "'.$edit_product_name.'" , ';
            $sql_update_product .= ' product_price = "'.$edit_product_price.'" , ';
            $sql_update_product .= ' product_category = "'.$edit_product_category.'" , ';
            $sql_update_product .= ' product_platform = "'.$edit_product_platform.'" , ';
            $sql_update_product .= ' product_type = "'.$edit_product_type.'" , ';
            $sql_update_product .= ' product_status = "'.$edit_product_status.'" , ';
            $sql_update_product .= ' product_tag = "'.$edit_product_tag.'" , ';
            $sql_update_product .= ' product_detail = "'.$edit_product_detail.'" , ';
            $sql_update_product .= ' product_video = "'.$edit_product_video.'" , ';
            $sql_update_product .= ' product_sale_status = "'.$edit_product_sale_status.'" , ';
            $sql_update_product .= ' product_sale_timer = "'.$edit_product_sale_timer.'" ,';
            $sql_update_product .= ' product_rate = "'.$edit_product_rate.'" ,';
            $sql_update_product .= ' product_stock = "'.$edit_product_stock.'" ';
            $sql_update_product .= ' WHERE product_id = "'.$_GET['id'].'"';
            $query_update_product = $connect->query($sql_update_product);
            if($query_update_product){
                echo '<div class="alert alert-success" role="alert"><strong>Succes!</strong> แก้ไขสินค้า '.$edit_product_name.' เรียบร้อยแล้ว</div>';
                echo '<script>history.back();</script>';
            }
            else
            {
                echo '<div class="alert alert-danger" role="alert"><strong>ERROR!</strong> แก้ไขสินค้า '.$edit_product_name.' ไม่เสร็จสิ้น</div>';
            }
        }
    }
?>
<div class="col-md-12" style="margin-bottom: 10px;">
    <div class="col-md-6">
        <h4><i class="glyphicon glyphicon-edit"></i> แก้ไขสินค้า - <?php echo $product['product_name']; ?></h4>
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-primary" onClick="location.href='?page=product'"><i class="glyphicon glyphicon-chevron-left"></i> กลับไปหน้ารายการสินค้า</button>
    </div>
</div>

<form name="edit_product_frm" method="POST" action="">
    <div class="col-md-2">
        <div class="form-group">
            <label>รหัสสินค้า* : </label>
            <input type="text" class="form-control disabled" disabled="" maxlength="12" onkeypress="return NumbersOnly(event);" value="<?php echo $product['product_id']; ?>"/>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label>ชื่อสินค้า* : </label>
            <input type="text" name="edit_product_name" class="form-control" maxlength="255" value="<?php echo $product['product_name']; ?>"  autocomplete="off" required=""/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>ราคาสินค้า* : </label>
            <div class="input-group">
                <input type="text" name="edit_product_price" class="form-control text-right" maxlength="13" onkeypress="return NumbersandDot(event);" value="<?php echo $product['product_price']; ?>"  autocomplete="off" required=""/>
                <div class="input-group-addon">บาท</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>หมวดหมู่สินค้า :</label>
            <select class="form-control" name="edit_product_category">
            <?php
                $sql_select_product_option = 'SELECT * FROM product_category ORDER BY category_id';
                $query_select_product_option = $connect->query($sql_select_product_option);
                while ($select_product_option = $query_select_product_option->fetch_assoc()):
            ?>
                <option <?php if($product['product_category'] == $select_product_option['category_id']){ ?>selected="selected" <?php } ?> value="<?php echo $select_product_option['category_id']; ?>"><?php echo $select_product_option['category_name']; ?></option>
            <?php endwhile; ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Platform* :</label>
            <select class="form-control" name="edit_product_platform">
            <?php
                $sql_select_product_option = 'SELECT * FROM product_platform ORDER BY platform_id';
                $query_select_product_option = $connect->query($sql_select_product_option);
                while ($select_product_option = $query_select_product_option->fetch_assoc()):
            ?>
                <option <?php if($product['product_platform'] == $select_product_option['platform_id']){ ?>selected="selected" <?php } ?> value="<?php echo $select_product_option['platform_id']; ?>"><?php echo $select_product_option['platform_name']; ?></option>
            <?php endwhile; ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>รูปแบบสินค้า* :</label>
            <select class="form-control" name="edit_product_type">
            <?php
                $sql_select_product_option = 'SELECT * FROM product_type ORDER BY type_id';
                $query_select_product_option = $connect->query($sql_select_product_option);
                while ($select_product_option = $query_select_product_option->fetch_assoc()):
            ?>
                <option <?php if($product['product_type'] == $select_product_option['type_id']){ ?>selected="selected" <?php } ?> value="<?php echo $select_product_option['type_id']; ?>"><?php echo $select_product_option['type_name']; ?></option>
            <?php endwhile; ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>สถานะสินค้า* :</label>
            <select class="form-control" name="edit_product_status">
            <?php
                $sql_select_product_option = 'SELECT * FROM product_status';
                $query_select_product_option = $connect->query($sql_select_product_option);
                while ($select_product_option = $query_select_product_option->fetch_assoc()):
            ?>
                <option <?php if($product['product_status'] == $select_product_option['status_id']){ ?>selected="selected" <?php } ?> value="<?php echo $select_product_option['status_id']; ?>"><?php echo $select_product_option['status_name']; ?></option>
            <?php endwhile; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>รูปภาพสินค้า* : </label>
            <input type="text" name="edit_product_image" class="form-control disabled" maxlength="20" value="<?php echo $config['site_url']; ?>/images/product/<?php echo $product['product_image']; ?>" disabled=""/>
        </div>
    </div>
    <!-- <div class="col-md-6">
        <div class="form-group">
            <label>Youtube Video ID : </label>
            <input type="text" name="edit_product_video" class="form-control" maxlength="20" value="<?php echo $product['product_video']; ?>" autocomplete="off"/>
        </div>
    </div> -->
    <div class="col-md-6">
        <div class="form-group">
            <label>คำค้น* : </label>
            <input type="text" name="edit_product_tag" class="form-control" maxlength="255" value="<?php echo $product['product_tag']; ?>"  autocomplete="off" required=""/>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>สถานะการลดราคา: </label>
            <select class="form-control" name="edit_product_sale_status">
            <?php if($product['product_sale_status'] == '0'): ?>
                <option value="0">ปิด</option>
                <option value="1">เปิด</option>
            <?php elseif ($product['product_sale_status'] == '1'): ?>
                <option value="1">เปิด</option>
                <option value="0">ปิด</option>
            <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>ลดราคาถึงวันที่ : </label>
            <input type="text" name="edit_product_sale_timer" class="form-control" placeholder="0000/00/00 00:00" value="<?php echo $product['product_sale_timer']; ?>"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>เรทสินค้า 1 บาท = กี่ชิ้น* :</label>
            <input type="text" name="edit_product_rate" class="form-control" placeholder="อย่างเช่น 6.5" value="<?php echo $product['product_rate']; ?>"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>สินค้าใน Stock* :</label>
            <input type="text" name="edit_product_stock" class="form-control" placeholder="อย่างเช่น 600" value="<?php echo $product['product_stock']; ?>"/>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label>รายละเอียดสินค้า : </label>
            <textarea name="edit_product_detail"><?php echo $product['product_detail']; ?></textarea>
            <script>CKEDITOR.replace( 'edit_product_detail' );</script>
        </div>
    </div>
    <div class="col-md-12 text-center">
        <input type="submit" name="edit_product_btn" class="btn btn-primary" style="margin: 20px;" value="แก้ไขสินค้า"/>
    </div>
</form>