<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(-1);*/

require_once '../_config.php';

$_CONFIG['mysql']['dbhost'] = $db_host;
$_CONFIG['mysql']['dbuser'] = $db_username;
$_CONFIG['mysql']['dbpw'] = $db_password;
$_CONFIG['mysql']['dbname'] = $db_name;
$_CONFIG['mysql']['tbl_insert'] = 'refill'; //ตารางแจ้งเติมเงิน
$_CONFIG['mysql']['tbl_update'] = 'member'; //ตารางสมาชิก

$_CONFIG['TMN'][50]['point'] = 42.50;
$_CONFIG['TMN'][90]['point'] = 76.50;
$_CONFIG['TMN'][150]['point'] = 127.50;
$_CONFIG['TMN'][300]['point'] = 255.00;
$_CONFIG['TMN'][500]['point'] = 425.00;
$_CONFIG['TMN'][1000]['point'] = 850.00;

// Select DB - tmtopupkey
$sql_tmtopupkey = 'SELECT webinfo_tmtopupkey FROM webinfo WHERE webinfo_id = "1"';
$query_tmtopupkey = $connect->query($sql_tmtopupkey);
$tmtopupkey = $query_tmtopupkey->fetch_assoc();

require_once('AES.php');
define('API_PASSKEY', $tmtopupkey['webinfo_tmtopupkey']);

$truemoney_connect_DB = mysqli_connect($_CONFIG['mysql']['dbhost'],$_CONFIG['mysql']['dbuser'],$_CONFIG['mysql']['dbpw'],$_CONFIG['mysql']['dbname']) or die('ERROR|DB_CONN_ERROR|' . mysql_error());

if($_SERVER['REMOTE_ADDR'] == '203.146.127.115' && isset($_GET['request'])){
	$aes = new Crypt_AES();
	$aes->setKey(API_PASSKEY);
	$_GET['request'] = base64_decode(strtr($_GET['request'], '-_,', '+/='));
	$_GET['request'] = $aes->decrypt($_GET['request']);

	if($_GET['request'] != false){
		parse_str($_GET['request'],$request);
		$truemoney_username = base64_decode($request['Ref1']);
                $truemoney_uid = base64_decode($request['Ref2']);
                $truemoney_refill_type = 'Truemoney';
                $truemoney_refill_datetime = date('d/m/Y H:i');
                $truemoney_refill_credit = $_CONFIG['TMN'][$request['cashcard_amount']]['point'];
                $truemoney_refill_pass = $request['cashcard_password'];

                    /* Database connection | Begin */
                    $sql_truemoneyin = 'INSERT INTO '.$_CONFIG['mysql']['tbl_insert'].' (refill_uid,refill_username,refill_type,refill_datetime,refill_credit,refill_detail,refill_status) VALUES ';
                    $sql_truemoneyin .= ' ("'.$truemoney_uid.'","'.$truemoney_username.'","'.$truemoney_refill_type.'","'.$truemoney_refill_datetime.'","'.$truemoney_refill_credit.'","'.$truemoney_refill_pass.'","1")';
                    $query_truemoneyin = mysqli_query($truemoney_connect_DB, $sql_truemoneyin);

                    $sql_truemoneyup = 'UPDATE '.$_CONFIG['mysql']['tbl_update'].' SET ';
                    $sql_truemoneyup .= ' credit = credit+"'.$_CONFIG['TMN'][$request['cashcard_amount']]['point'].'" ';
                    $sql_truemoneyup .= ' WHERE uid = "'.$truemoney_uid.'" ';
                    $query_truemoneyup = mysqli_query($truemoney_connect_DB, $sql_truemoneyup);
                    echo 'SUCCESS | (Username: '.$truemoney_username.')';
	} else {
		echo 'ERROR|INVALID_PASSKEY';
	}
} else {
    echo 'ERROR|ACCESS_DENIED';
}
?>
