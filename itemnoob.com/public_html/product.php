<?php

/* 
 *  This site Design with Bootstrap and Develop source code by. DJdai Yodsapon
 *  Can contact DJdai at y.wongchuen@gmail.com or +66800257281 (Thailand Number)
 */

require_once 'application/_config.php';
include_once 'template/header.php';
$sql_detail_product = 'SELECT * FROM product WHERE product_id = "'.$connect->real_escape_string($_GET['id']).'"';
$query_detail_product = $connect->query($sql_detail_product);
$detail_product = $query_detail_product->fetch_assoc();
if($detail_product){
    include_once 'template/listproduct_detail.php';  
} else {
    include_once 'application/error/404.php';
}
include_once 'template/footer.php';