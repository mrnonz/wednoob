<?php

/* 
 *  This site Design with Bootstrap and Develop source code by. DJdai Yodsapon
 *  Can contact DJdai at y.wongchuen@gmail.com or +66800257281 (Thailand Number)
 */

require_once 'application/_config.php';
include_once 'template/header.php';
if(isset($_GET['page']) == NULL){
    include_once 'page/main.php';
} elseif ($_GET['page'] == 'product-all') {
    include_once 'page/product-all.php';
} elseif ($_GET['page'] == 'product-sale') {
    include_once 'page/product-sale.php';
} elseif ($_GET['page'] == 'howto') {
    include_once 'page/howto.php';
} elseif ($_GET['page'] == 'test') {
	include_once 'test.php';
} else {
    include_once 'application/error/404.php';
}
include_once 'template/footer.php';