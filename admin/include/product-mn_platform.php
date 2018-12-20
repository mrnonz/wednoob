<?php
    // Function Insert
    if(isset($_POST['new_platform_btn'])){
        $new_platform_name = $_POST['new_platform_name'];
        $sql_new_platform = 'INSERT INTO product_platform (platform_name) VALUES ("'.$new_platform_name.'")';
        $query_new_platform = $connect->query($sql_new_platform);
        if($query_new_platform){
            echo '<script>history.back();</script>';
        } else {
            echo '<script>alert("Error! ไม่สามารถเพิ่มข้อมูลลงฐานข้อมูลได้"); window.history.back();</script>';
        }
    }
    
    // Function Delete
    if($_GET['delete'] == 'yes'){
        $sql_product_delete_platform = 'SELECT * FROM product WHERE product_platform = "'.$_GET['id'].'"';
        $query_product_delete_platform = $connect->query($sql_product_delete_platform);
        $product_delete_platform = $query_product_delete_platform->fetch_assoc();
        if(!$product_delete_platform){
            $sql_delete_platform = 'DELETE FROM product_platform WHERE platform_id = "'.$_GET['id'].'"';
            $delete_platform = $connect->query($sql_delete_platform);
            if($delete_platform){
                echo '<script>history.back();</script>';
            }
        } else {
            echo '<script>alert("Error! ยังมีสินค้าที่อยู่ใน Platform นี้ กรุณาลบสินค้าที่อยู่ใน Platform นี้ทั้งหมดก่อน"); window.history.back();</script>';
        }
    }
?>
<div class="col-md-12" style="margin-bottom: 10px;">
    <div class="col-md-6">
        <h4><i class="glyphicon glyphicon-edit"></i> จัดการ Platform</h4>
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-primary" onClick="location.href='?page=product'"><i class="glyphicon glyphicon-chevron-left"></i> กลับไปหน้ารายการสินค้า</button>
    </div>
</div>
<?php
    $sql_product_platform = 'SELECT * FROM product_platform ORDER BY platform_id ASC';
    $query_product_platform = $connect->query($sql_product_platform);
?>
<div class="col-md-12">
    <div class="table-responsive">
        <form name="new_platform_frm" method="post" action="">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="col-md-11">Platform</th>
                    <th class="col-md-1 text-center"></th>
                </tr>
            </thead>
            <tbody>
                <?php while($product_platform = $query_product_platform->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $product_platform['platform_name'];?></td>
                    <td class="text-center">
                        <a href="?page=product&action=mn_platform&edit=yes&id=<?php echo $product_platform['platform_id'];?>"><i class="glyphicon glyphicon-edit"></i></a>
                        <a href="?page=product&action=mn_platform&delete=yes&id=<?php echo $product_platform['platform_id'];?>" onclick="return confirm('คุณกำลังจะลบหมวดหมู่สินค้า คุณแน่ใจแล้วหรือไม่?')"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td><input type="text" name="new_platform_name" class="form-control" autocomplete="off" required="" placeholder="Platform"/></td>
                    <td class="text-center"><input type="submit" name="new_platform_btn" class="btn btn-primary" value="เพิ่ม!!"/></td>
                </tr>
            </tbody>
        </table>
        </form>
    </div>
</div>

<?php
    if(isset($_GET['edit']) == 'yes'):
        $sql_product_platform = 'SELECT * FROM product_platform WHERE platform_id = "'.$_GET['id'].'"';
        $query_product_platform = $connect->query($sql_product_platform);
        $product_platform = $query_product_platform->fetch_assoc();
        if(isset($_POST['edit_platform_btn'])){
            $edit_platform_name = $_POST['edit_platform_name'];
            $sql_edit_platform = 'UPDATE product_platform SET platform_name = "'.$edit_platform_name.'" WHERE platform_id = "'.$_GET['id'].'"';
            $query_edit_platform = $connect->query($sql_edit_platform);
            if($query_edit_platform){
                echo '<script>history.back();</script>';
            }
        }
?>
<h4>แก้ไข Platform - <?php echo $product_platform['platform_name'];?></h4>
<form name="edit_platform_frm" method="post" action="">
    <div class="col-md-11 form-group">
        <label>Platform : </label>
        <input type="text" name="edit_platform_name" class="form-control" autocomplete="off" required="" value="<?php echo $product_platform['platform_name'];?>"/>
    </div>
    <div class="col-md-1 form-group">
        <label>แก้ไข : </label>
        <input type="submit" name="edit_platform_btn" class="btn btn-primary" value="แก้ไข!!"/>
    </div>
</form>
<?php endif; ?>