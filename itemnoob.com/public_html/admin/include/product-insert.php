<div class="col-md-12" style="margin-bottom: 10px;">
    <div class="col-md-6">
        <h4><i class="glyphicon glyphicon-plus"></i> เพิ่มสินค้าใหม่</h4>
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-primary" onClick="location.href='?page=product'"><i class="glyphicon glyphicon-chevron-left"></i> กลับไปหน้ารายการสินค้า</button>
    </div>
</div>
<?php
    if(isset($_POST['insert_product_btn'])){
        $insert_product_id = $_POST['insert_product_id'];
        $insert_product_name = $_POST['insert_product_name'];
        $insert_product_price = $_POST['insert_product_price'];
        $insert_product_category = $_POST['insert_product_category'];
        $insert_product_platform = $_POST['insert_product_platform'];
        $insert_product_type = $_POST['insert_product_type'];
        $insert_product_status = $_POST['insert_product_status'];
        $insert_product_tag = $_POST['insert_product_tag'];
        $insert_product_detail = htmlentities($_POST['insert_product_detail']);
        $insert_product_video = 'None';
        $insert_product_recomment = '0';
        $insert_product_sale_status = '0';
        $insert_product_rate = $_POST['insert_product_rate'];
        if(empty($insert_product_id) || empty($insert_product_name) || empty($insert_product_price) || empty($insert_product_tag)){
            echo '<script>alert("Error! กรุณากรอกข้อมูลในช่องสำคัญให้ครบ"); window.history.back();</script>';
        } else {
            // Rename Image
            $explde_file = explode(".",$_FILES["insert_product_image"]["name"]); // แยกชื่อไฟล์กับสกุลเก็บในแบบ Array
            $rename_file =  $insert_product_id."_image.".$explde_file['1']; // ตั้งชื่อใหม่
            
            // Resize Image
            $resize_image = $_FILES["insert_product_image"]["tmp_name"];
            $width=460;
            $size=GetimageSize($resize_image);
            $height=215;
            $images_orig = ImageCreateFromJPEG($resize_image);
            $photoX = ImagesX($images_orig);
            $photoY = ImagesY($images_orig);
            $images_fin = ImageCreateTrueColor($width, $height);
            ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
            ImageJPEG($images_fin,"images/product/".$rename_file);
            ImageDestroy($images_fin);
            
            // Insert
            $sql_insert_product = 'INSERT INTO product ';
            $sql_insert_product .= ' (product_id,product_name,product_price,product_category,product_platform,product_type,product_status,product_image,product_tag,product_detail,product_video,product_recomment,product_sale_status,product_rate) ';
            $sql_insert_product .= ' VALUES ';
            $sql_insert_product .= ' ("'.$insert_product_id.'","'.$insert_product_name.'","'.$insert_product_price.'","'.$insert_product_category.'","'.$insert_product_platform.'","'.$insert_product_type.'","'.$insert_product_status.'","'.$rename_file.'","'.$insert_product_tag.'","'.$insert_product_detail.'","'.$insert_product_video.'","'.$insert_product_recomment.'","'.$insert_product_sale_status.'","'.$insert_product_rate.'") ';
            $query_insert_product = $connect->query($sql_insert_product);
            if($query_insert_product){
                echo '<div class="col-md-12" style="margin-bottom: 10px;"><div class="alert alert-success" role="alert"><strong>Succes!</strong> เพิ่มสินค้า '.$insert_product_name.' เข้าร้านค้าเรียบร้อยแล้ว | Refresh in 3 sec.</div></div>';
                header("Refresh:3");
            } 
        }
    }
?>
<!-- <div class="col-md-12">
    <small>**รหัสสินค้าสำหรับ Platform STEAM ให้ใช้รหัสสินค้าของ STEAM โดยตรง</small>
</div> -->
<form name="insert_product_frm" method="POST" action="" enctype="multipart/form-data">
    <div class="col-md-2">
        <div class="form-group">
            <label>รหัสสินค้า* : </label>
            <input type="text" name="insert_product_id" class="form-control" maxlength="12" onkeypress="return NumbersOnly(event);" autocomplete="off" required=""/>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label>ชื่อสินค้า* : </label>
            <input type="text" name="insert_product_name" class="form-control" maxlength="255" autocomplete="off" required=""/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>ราคาสินค้า* : </label>
            <div class="input-group">
                <input type="text" name="insert_product_price" class="form-control text-right" maxlength="13" onkeypress="return NumbersandDot(event);" autocomplete="off" required=""/>
                <div class="input-group-addon">บาท</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>หมวดหมู่สินค้า* :</label>
            <select class="form-control" name="insert_product_category">
            <?php
                $sql_product_category = 'SELECT * FROM product_category ORDER BY category_id';
                $query_product_category = $connect->query($sql_product_category);
                while($product_category = $query_product_category->fetch_assoc()){
                    echo '<option value="'.$product_category['category_id'].'">'.$product_category['category_name'].'</option>';
                }
            ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Platform* :</label>
            <select class="form-control" name="insert_product_platform">
            <?php
                $sql_product_platform = 'SELECT * FROM product_platform ORDER BY platform_id';
                $query_product_platform = $connect->query($sql_product_platform);
                while($product_platform = $query_product_platform->fetch_assoc()){
                    echo '<option value="'.$product_platform['platform_id'].'">'.$product_platform['platform_name'].'</option>';
                }
            ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>รูปแบบสินค้า* :</label>
            <select class="form-control" name="insert_product_type">
            <?php
                $sql_product_type = 'SELECT * FROM product_type ORDER BY type_id';
                $query_product_type = $connect->query($sql_product_type);
                while($product_type = $query_product_type->fetch_assoc()){
                    echo '<option value="'.$product_type['type_id'].'">'.$product_type['type_name'].'</option>';
                }
            ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>สถานะสินค้า* :</label>
            <select class="form-control" name="insert_product_status">
            <?php
                $sql_product_status = 'SELECT * FROM product_status ORDER BY status_id';
                $query_product_status = $connect->query($sql_product_status);
                while($product_status = $query_product_status->fetch_assoc()){
                    echo '<option value="'.$product_status['status_id'].'">'.$product_status['status_name'].'</option>';
                }
            ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>รูปภาพสินค้า* : </label>
            <input type="file" class="form-control" name="insert_product_image">
        </div>
    </div>
    <!-- <div class="col-md-6">
        <div class="form-group">
            <label>Youtube Video ID : </label>
            <input type="text" name="insert_product_video" class="form-control" maxlength="20" autocomplete="off"/>
        </div>
    </div> -->
    <div class="col-md-4">
        <div class="form-group">
            <label>คำค้น* : </label>
            <input type="text" name="insert_product_tag" class="form-control" maxlength="255" autocomplete="off" placeholder="เช่น GTA V , GTAV , ..."/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>เรทสินค้า 1 บาท = กี่ชิ้น* :</label>
            <input type="text" name="insert_product_rate" class="form-control" placeholder="อย่างเช่น 6.5"/>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label>รายละเอียดสินค้า : </label>
            <textarea name="insert_product_detail"></textarea>
            <script>CKEDITOR.replace( 'insert_product_detail' );</script>
        </div>
    </div>
    <div class="col-md-12 text-center">
        <input type="submit" name="insert_product_btn" class="btn btn-primary" style="margin: 20px;" value="เพิ่มสินค้าใหม่"/>
    </div>
</form>