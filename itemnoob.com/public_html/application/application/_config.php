<?php

/*
 *  This site Design with Bootstrap and Develop source code by.  itemnoob
 */
    ob_start();
    session_start();
    /*error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);*/
    
/* Website Setting */
$config = array(
    'site_url' => 'https://www.itemnoob.com', // โดเมนหลัก (ไม่ต้องมี / ปิดท้าย)
    'charset_language' => 'UTF-8',
);
    
/* Database Setting */
$db_host = "itemnoob.com";// Host for connent database
$db_username = "admin_itemnoob"; // Username for connent database
$db_password = "itemnoob9090store"; // Password for connent database
$db_name = "admin_itemnoob"; // Database name

/* TMTOPUP Setting */
$tmtopup['uid'] = "217008";
$tmtopup['API_PASSKEY'] = "itemnoobkey";
$tmtopup['credit'][50] = 42.50;
$tmtopup['credit'][90] = 76.50;
$tmtopup['credit'][150] = 127.50;
$tmtopup['credit'][300] = 255.00;
$tmtopup['credit'][500] = 425.00;
$tmtopup['credit'][1000] = 850.00;
    
/* Autobank Setting */
$autobank = array(
    'LICENSE'   => '', // License Key
    'SECRET'    => '', // Secret Key
    
    // **ธนาคารไหนไม่มีให้เว้นว่างไว้
    'KBANK' => array(
        'username'  => '',
        'password'  => '',
        'account'   => ''
    ),
    'SCB' => array(
        'username'  => '',
        'password'  => '',
        'account'   => ''
    ),
    'BBL' => array(
        'username'  => '',
        'password'  => '',
        'account'   => ''
    ),
    'KTB' => array(
        'username'  => '',
        'password'  => '',
        'account'   => ''
    ),
    'TMB' => array(
        'username'  => '',
        'password'  => '',
        'account'   => ''
    ),
    'BAY' => array(
        'username'  => '',
        'password'  => '',
        'account'   => ''
    )
);
    
    $connect = new mysqli($db_host,$db_username,$db_password,$db_name);
    $connect->query('SET names utf8');
    if($connect->connect_errno){
        echo $connect->connect_error;
    }

    # ป้องกัน sql injection จาก $_GET
    foreach($_GET as $key => $value){
        $_GET[$key]=addslashes(strip_tags(trim($value)));
    }
    if($_GET['id'] !=''){ 
        $_GET['id']=(int) $_GET['id'];
    }
    extract($_GET);