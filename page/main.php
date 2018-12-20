<div class="row" style="padding: 0px 10px 0px 10px;">
    <?php
    // Select DB - product
    echo '<div class="panel panel-primary" style="border-color:#da4453">';
    echo '<div class="panel-heading" style="color:#fff;background-color:#da4453;border-color:#da4453">';
    echo "<b><h5>สินค้าลดราคา</h5></b>";
    echo '</div>';
    echo '<div class="panel-body">';
    $sql_product = "SELECT * FROM product WHERE product_sale_status = '1' ORDER BY product_id LIMIT 8";
    $query_product = $connect->query($sql_product);
    $numrows_product = $query_product->num_rows;
    if($numrows_product != '0'){
        while($product = $query_product->fetch_assoc()){
            include 'template/listproduct_box.php'; 
        }
    } else {
        echo '<div class="well well-sm text-center" style="margin: 0px 5px;">ขออภัย ไม่พบสินค้าในหมวดหมู่นี้</div>';
    }
    echo '</div>';
    echo '</div>';
    ?>
</div>
<div class="col-md-12 text-right">
    <a href="./?page=product-sale">ดูสินค้าลดราคาทั้งหมด..</a>
</div>
<hr/>



<div class="row" style="padding: 0px 10px 0px 10px;">
    <?php
    // Select DB - product
    $sql_category = "SELECT * FROM product_category";
    $query_category = $connect->query($sql_category);
    while($category = $query_category->fetch_assoc())
    {
        echo '<div class="panel panel-primary" style="border-color:#da4453">';
        echo '<div class="panel-heading" style="color:#fff;background-color:#da4453;border-color:#da4453">';
        echo "<b><h5>".$category['category_name']." | สินค้าแนะนำ</h5></b>";
        echo '</div>';
        echo '<div class="panel-body">';
        $sql_product = "SELECT * FROM product WHERE product_recomment = '1' and product_category = '".$category['category_id']."' ORDER BY product_name LIMIT 16";
        $query_product = $connect->query($sql_product);
        $numrows_product = $query_product->num_rows;
        echo '<div class="col-md-12">';
        if($numrows_product != '0'){
            while($product = $query_product->fetch_assoc()){
                include 'template/listproduct_box.php'; 
            }
        } else {
            echo '<div class="well well-sm text-center" style="margin: 0px 5px;">ขออภัย ไม่พบสินค้าในหมวดหมู่นี้</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>
<div class="col-md-12 text-right">
    <a href="./?page=product-all">ดูรายการสินค้าทั้งหมด..</a>
</div>