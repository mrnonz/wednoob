<?php

/* 
 *  This site Design with Bootstrap and Develop source code by. DJdai Yodsapon
 *  Can contact DJdai at y.wongchuen@gmail.com or +66800257281 (Thailand Number)
 */
    
    require_once 'application/_config.php';
    if(isset($_SESSION['member_uid'])){
        if($_SESSION['member_status'] == '5'){
            include_once 'admin/template/header.php';
            if(isset($_GET['page']) == NULL){
                include_once 'admin/dashboard.php';
            } elseif($_GET['page'] == 'product'){
                include_once 'admin/product.php';
            } elseif($_GET['page'] == 'listorder'){
                include_once 'admin/listorder.php';
            } elseif($_GET['page'] == 'listrefill'){
                include_once 'admin/listrefill.php';
            } elseif($_GET['page'] == 'finance'){
                include_once 'admin/finance.php';
            } elseif($_GET['page'] == 'member'){
                include_once 'admin/member.php';
            } elseif($_GET['page'] == 'datastore'){
                include_once 'admin/datastore.php';
            } elseif($_GET['page'] == 'eventplatform'){
                include_once 'admin/eventplatform.php';
            }
            elseif($_GET['page'] == 'twaccount'){
                include_once 'admin/twaccount.php';
            }
            include_once 'admin/template/footer.php';
        } else {
            header('location: index.php');
        }
    } else {
        header('location: index.php');
    }