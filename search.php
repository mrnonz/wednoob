<?php
    require_once 'application/_config.php';
    include_once 'template/header.php';
?>
<h4>ผลการค้นหา <?php echo strip_tags($_GET["keyword"]);?></h4>
<div class="row" style="padding: 10px 10px;">
<?php 
        if(strip_tags($_GET['keyword']) != NULL){
            $sql_product = "SELECT * FROM product WHERE (product_name LIKE '%".$_GET["keyword"]."%' or product_tag LIKE '%".$_GET["keyword"]."%' or product_id LIKE '%".$_GET["keyword"]."%' )";
            $query_product = $connect->query($sql_product);
            if($query_product->num_rows < 1){
                echo '<div class="well well-sm text-center" style="margin: 0px 5px;">ไม่พบสินค้าที่คุณกำลังค้นหา! (ลองค้นหาด้วย Steam URL สิ)</div>';
            } else {
                while ($product = $query_product->fetch_assoc()){
                    include 'template/listproduct_box.php';
                }
            }
        }  else {
            echo '<div class="col-md-12"><div class="alert" style="background-color:#e9e9e9;">ช่องค้นหาว่างเปล่า กรุณาระบุคำที่ต้องการค้นหา!</div></div>';
        }
?>
</div>
<?php
    include_once 'template/footer.php';
?>