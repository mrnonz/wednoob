<?php

/* 
 *  This site Design with Bootstrap and Develop source code by. DJdai Yodsapon
 *  Can contact DJdai at y.wongchuen@gmail.com or +66800257281 (Thailand Number)
 */
    
require_once 'application/_config.php';
 if(isset($_SESSION['member_uid'])){
        if($_SESSION['member_status'] == '5'){
            
            if($_GET['func'] == 'ordersending'){
                $sumrecord_dashboard = $connect->query('SELECT * FROM `order` WHERE `order_status` = "0"');
                $rs_sumrecord_dashboard  = $sumrecord_dashboard->num_rows;
                echo $rs_sumrecord_dashboard;
            } elseif($_GET['func'] == 'ordercompleted'){
                $sumrecord_dashboard = $connect->query('SELECT * FROM `order` WHERE `order_status` = "1"');
                $rs_sumrecord_dashboard  = $sumrecord_dashboard->num_rows;
                echo $rs_sumrecord_dashboard;
            } elseif($_GET['func'] == 'refillpending'){
                $sumrecord_dashboard = $connect->query('SELECT * FROM `refill` WHERE `refill_status` = "0"');
                $rs_sumrecord_dashboard  = $sumrecord_dashboard->num_rows;
                echo $rs_sumrecord_dashboard;
            } elseif($_GET['func'] == 'refillapproval'){
                $sumrecord_dashboard = $connect->query('SELECT * FROM `refill` WHERE `refill_status` = "1"');
                $rs_sumrecord_dashboard  = $sumrecord_dashboard->num_rows;
                echo $rs_sumrecord_dashboard;
            } elseif($_GET['func'] == 'allcredit'){
                $sumrecord_dashboard = $connect->query('SELECT sum(credit) as sum_credit FROM `member`');
                $rs_sumrecord_dashboard = $sumrecord_dashboard->fetch_assoc();
                echo number_format($rs_sumrecord_dashboard['sum_credit'],2);
            } /*elseif($_GET['func'] == 'updatestatusstore'){
                $strRecive = stripslashes($_POST["updateStatusStore"]);
                $arrData = json_decode($strRecive,true);
                $arrReturn = array();
                if($strRecive != NULL){
                    $connect->query('UPDATE `webinfo` SET `webinfo_modstatus` = "'.$arrData["setStatusStore"].'" WHERE `webinfo_id` = "1"');
                    echo json_encode($arrReturn);
                }
            }*/

        } else {
            header('location:'.$config['site_url']);
        }
    } else {
        header('location:'.$config['site_url']);
    }
?>