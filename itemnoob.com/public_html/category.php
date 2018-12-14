<?php

/* 
 *  This site Design with Bootstrap and Develop source code by. DJdai Yodsapon
 *  Can contact DJdai at y.wongchuen@gmail.com or +66800257281 (Thailand Number)
 */

require_once 'application/_config.php';
include_once 'template/header.php';
    // Select DB - product_category
    $sql_product_category_page = 'SELECT * FROM product_category WHERE category_url = "'.$_GET['page'].'"';
    $query_product_category_page = $connect->query($sql_product_category_page);
    $product_category_page = $query_product_category_page->fetch_assoc();
    if($product_category_page):
?>
<h4>หมวดหมู่ : <?php echo $product_category_page['category_name']; ?></h4>
<div class="row" style="padding: 0px 10px 0px 10px;">
<?php
    // Select DB - product
     $sql_product = "SELECT * FROM product WHERE product_category = '".$product_category_page['category_id']."' ";
    $query_product = $connect->query($sql_product);
    $numrows_product = $query_product->num_rows;
    if($numrows_product == '0'){
        echo '<div class="well well-sm text-center" style="margin: 0px 5px;">ขออภัย ไม่พบสินค้าในหมวดหมู่นี้</div>';
    }
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
    $sql_product .= " ORDER BY product_id ASC LIMIT $Page_Start , $Per_Page";
    $query_product = $connect->query($sql_product);
    while($product = $query_product->fetch_assoc()){
        include 'template/listproduct_box.php';
    }
    ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
</div>
<?php
    else :
        include_once 'application/error/404.php';
    endif;
include_once 'template/footer.php';
?>