<?php
    // Function Insert
    if(isset($_POST['new_type_btn'])){
        $new_type_name = $_POST['new_type_name'];
        $sql_new_type = 'INSERT INTO product_type (type_name) VALUES ("'.$new_type_name.'")';
        $query_new_type = $connect->query($sql_new_type);
        if($query_new_type){
            echo '<script>history.back();</script>';
        } else {
            echo '<script>alert("Error! ไม่สามารถเพิ่มข้อมูลลงฐานข้อมูลได้"); window.history.back();</script>';
        }
    }
    
    // Function Delete
    if($_GET['delete'] == 'yes'){
        $sql_product_delete_type = 'SELECT * FROM product WHERE product_type = "'.$_GET['id'].'"';
        $query_product_delete_type = $connect->query($sql_product_delete_type);
        $product_delete_type = $query_product_delete_type->fetch_assoc();
        if(!$product_delete_type){
            $sql_delete_type = 'DELETE FROM product_type WHERE type_id = "'.$_GET['id'].'"';
            $delete_type = $connect->query($sql_delete_type);
            if($delete_type){
                echo '<script>history.back();</script>';
            }
        } else {
            echo '<script>alert("Error! ยังมีสินค้าที่อยู่ในรูปแบบนี้ กรุณาลบสินค้าที่อยู่ในรูปแบบนี้ทั้งหมดก่อน"); window.history.back();</script>';
        }
    }
?>
<div class="col-md-12" style="margin-bottom: 10px;">
    <div class="col-md-6">
        <h4><i class="glyphicon glyphicon-edit"></i> จัดการรูปแบบสินค้า</h4>
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-primary" onClick="location.href='?page=product'"><i class="glyphicon glyphicon-chevron-left"></i> กลับไปหน้ารายการสินค้า</button>
    </div>
</div>
<?php
    $sql_product_type = 'SELECT * FROM product_type ORDER BY type_id ASC';
    $query_product_type = $connect->query($sql_product_type);
?>
<div class="col-md-12">
    <div class="table-responsive">
        <form name="new_type_rank" method="post" action="">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="col-md-11">รูปแบบสินค้า</th>
                    <th class="col-md-1 text-center"></th>
                </tr>
            </thead>
            <tbody>
                <?php while($product_type = $query_product_type->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $product_type['type_name'];?></td>
                    <td class="text-center">
                        <a href="?page=product&action=mn_type&edit=yes&id=<?php echo $product_type['type_id'];?>"><i class="glyphicon glyphicon-edit"></i></a>
                        <a href="?page=product&action=mn_type&delete=yes&id=<?php echo $product_type['type_id'];?>" onclick="return confirm('คุณกำลังจะลบหมวดหมู่สินค้า คุณแน่ใจแล้วหรือไม่?')"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td><input type="text" name="new_type_name" class="form-control" autocomplete="off" required="" placeholder="รูปแบบสินค้า"/></td>
                    <td class="text-center"><input type="submit" name="new_type_btn" class="btn btn-primary" value="เพิ่ม!!"/></td>
                </tr>
            </tbody>
        </table>
        </form>
    </div>
</div>

<?php
    if(isset($_GET['edit']) == 'yes'):
        $sql_product_type = 'SELECT * FROM product_type WHERE type_id = "'.$_GET['id'].'"';
        $query_product_type = $connect->query($sql_product_type);
        $product_type = $query_product_type->fetch_assoc();
        if(isset($_POST['edit_type_btn'])){
            $edit_type_name = $_POST['edit_type_name'];
            $sql_edit_type = 'UPDATE product_type SET type_name = "'.$edit_type_name.'" WHERE type_id = "'.$_GET['id'].'"';
            $query_edit_type = $connect->query($sql_edit_type);
            if($query_edit_type){
                echo '<script>history.back();</script>';
            }
        }
?>
<h4>แก้ไขรูปแบบสินค้า - <?php echo $product_type['type_name'];?></h4>
<form name="edit_type_frm" method="post" action="">
    <div class="col-md-11 form-group">
        <label>รูปแบบสินค้า : </label>
        <input type="text" name="edit_type_name" class="form-control" autocomplete="off" required="" value="<?php echo $product_type['type_name'];?>"/>
    </div>
    <div class="col-md-1 form-group">
        <label>แก้ไข : </label>
        <input type="submit" name="edit_type_btn" class="btn btn-primary" value="แก้ไข!!"/>
    </div>
</form>
<?php endif; ?>