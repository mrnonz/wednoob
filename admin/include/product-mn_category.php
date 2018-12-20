<?php
    // Function Insert
    if(isset($_POST['new_category_btn'])){
        $new_category_name = $_POST['new_category_name'];
        $new_category_url = $_POST['new_category_url'];
        $sql_new_category = 'INSERT INTO product_category (category_name,category_url) VALUES ("'.$new_category_name.'","'.$new_category_url.'")';
        $query_new_category = $connect->query($sql_new_category);
        if($query_new_category){
            echo '<script>history.back();</script>';
        } else {
            echo '<script>alert("Error! ไม่สามารถเพิ่มข้อมูลลงฐานข้อมูลได้"); window.history.back();</script>';
        }
    }
    
    // Function Delete
    if($_GET['delete'] == 'yes'){
        $sql_product_delete_category = 'SELECT * FROM product WHERE product_category = "'.$_GET['id'].'"';
        $query_product_delete_category = $connect->query($sql_product_delete_category);
        $product_delete_category = $query_product_delete_category->fetch_assoc();
        if(!$product_delete_category){
            $sql_delete_category = 'DELETE FROM product_category WHERE category_id = "'.$_GET['id'].'"';
            $delete_category = $connect->query($sql_delete_category);
            if($delete_category){
                echo '<script>history.back();</script>';
            }
        } else {
            echo '<script>alert("Error! ยังมีสินค้าที่อยู่ในหมวดหมู่นี้ กรุณาลบสินค้าที่อยู่ในหมวดหมู่นี้ทั้งหมดก่อน"); window.history.back();</script>';
        }
    }
?>
<div class="col-md-12" style="margin-bottom: 10px;">
    <div class="col-md-6">
        <h4><i class="glyphicon glyphicon-edit"></i> จัดการหมวดหมู่สินค้า</h4>
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-primary" onClick="location.href='?page=product'"><i class="glyphicon glyphicon-chevron-left"></i> กลับไปหน้ารายการสินค้า</button>
    </div>
</div>
<?php
    $sql_product_category = 'SELECT * FROM product_category ORDER BY category_id ASC';
    $query_product_category = $connect->query($sql_product_category);
?>
<div class="col-md-12">
    <div class="table-responsive">
        <form name="new_category_rank" method="post" action="" enctype="multipart/form-data">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="col-md-8">ชื่อหมวดหมู่</th>
                    <th class="col-md-3 text-center">URL</th>
                    <th class="col-md-1 text-center"></th>
                </tr>
            </thead>
            <tbody>
                <?php while($product_category = $query_product_category->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $product_category['category_name'];?></td>
                    <td><?php echo $product_category['category_url'];?></td>
                    <td class="text-center">
                        <a href="?page=product&action=mn_category&edit=yes&id=<?php echo $product_category['category_id'];?>"><i class="glyphicon glyphicon-edit"></i></a>
                        <a href="?page=product&action=mn_category&delete=yes&id=<?php echo $product_category['category_id'];?>" onclick="return confirm('คุณกำลังจะลบหมวดหมู่สินค้า คุณแน่ใจแล้วหรือไม่?')"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td><input type="text" name="new_category_name" class="form-control" autocomplete="off" required="" placeholder="ชื่อหมวดหมู่"/></td>
                    <td><input type="text" name="new_category_url" class="form-control"  onkeypress="return EnglishandNumbsers(event);" autocomplete="off" required="" placeholder="URL Link"/></td>
                    <td class="text-center"><input type="submit" name="new_category_btn" class="btn btn-primary" value="เพิ่ม!!"/></td>
                </tr>
            </tbody>
        </table>
        </form>
    </div>
</div>

<?php
    if(isset($_GET['edit']) == 'yes'):
        $sql_product_category = 'SELECT * FROM product_category WHERE category_id = "'.$_GET['id'].'"';
        $query_product_category = $connect->query($sql_product_category);
        $product_category = $query_product_category->fetch_assoc();
        if(isset($_POST['edit_category_btn'])){
            $edit_category_name = $_POST['edit_category_name'];
            $sql_edit_category = 'UPDATE '.product_category.' SET category_name = "'.$edit_category_name.'" WHERE category_id = "'.$_GET['id'].'"';
            $query_edit_category = $connect->query($sql_edit_category);
            if($query_edit_category){
                echo '<script>history.back();</script>';
            }
        }
?>
<h4>แก้ไขหมวดหมู่ - <?php echo $product_category['category_name'];?></h4>
<form name="edit_category_frm" method="post" action="">
    <div class="col-md-8 form-group">
        <input type="text" name="edit_category_name" class="form-control" autocomplete="off" required="" value="<?php echo $product_category['category_name'];?>"/>
    </div>
    <div class="col-md-3 form-group">
        <input type="text" class="form-control disabled" disabled="" readonly="" onkeypress="return EnglishandNumbsers(event);" autocomplete="off" required="" value="<?php echo $product_category['category_url'];?>"/>
    </div>
    <div class="col-md-1 form-group">
        <input type="submit" name="edit_category_btn" class="btn btn-primary" value="แก้ไข!!"/>
    </div>
</form>
<?php endif; ?>