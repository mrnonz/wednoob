<h4>จัดการสินค้า</h4><hr/>
<?php
    if($_GET['action'] == NULL){ 
        include_once 'include/product-list.php';
    } elseif ($_GET['action'] == 'insert') {
        include_once 'include/product-insert.php';
    } elseif ($_GET['action'] == 'edit') {
        include_once 'include/product-edit.php';
    } elseif ($_GET['action'] == 'delete') {
        $sql_delete_product = 'DELETE FROM product WHERE product_id = "'.$_GET['id'].'"';
        $query_delete_product = $connect->query($sql_delete_product);
        echo '<script>history.back();</script>';
    } elseif ($_GET['action'] == 'availability') {
        $sql_update_statusproduct = 'UPDATE product SET product_status = "1" WHERE product_id = "'.$_GET['id'].'"';
        $query_update_statusproduct = $connect->query($sql_update_statusproduct);
        echo '<script>history.back();</script>';
    } elseif ($_GET['action'] == 'soldout') {
        $sql_update_statusproduct = 'UPDATE product SET product_status = "2" WHERE product_id = "'.$_GET['id'].'"';
        $query_update_statusproduct = $connect->query($sql_update_statusproduct);
        echo '<script>history.back();</script>';
    }  elseif ($_GET['action'] == 'activerecomment') {
        $sql_update_recommentproduct = 'UPDATE product SET product_recomment = "1" WHERE product_id = "'.$_GET['id'].'"';
        $query_update_recommentproduct = $connect->query($sql_update_recommentproduct);
        echo '<script>history.back();</script>';
    } elseif ($_GET['action'] == 'deactiverecomment') {
        $sql_update_recommentproduct = 'UPDATE product SET product_recomment = "0" WHERE product_id = "'.$_GET['id'].'"';
        $query_update_recommentproduct = $connect->query($sql_update_recommentproduct);
        echo '<script>history.back();</script>';
    } elseif ($_GET['action'] == 'mn_category') {
        include_once 'include/product-mn_category.php';
    } elseif ($_GET['action'] == 'mn_platform') {
        include_once 'include/product-mn_platform.php';
    } elseif ($_GET['action'] == 'mn_type') {
        include_once 'include/product-mn_type.php';
    } else {
        include_once 'product-list.php';
    }
?>