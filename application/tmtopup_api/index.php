<?php
    require_once '../_config.php';

    $_CONFIG['mysql']['dbhost'] = $db_host;
    $_CONFIG['mysql']['dbuser'] = $db_username;
    $_CONFIG['mysql']['dbpw'] = $db_password;
    $_CONFIG['mysql']['dbname'] = $db_name;
    $_CONFIG['mysql']['tbl_insert'] = 'refill';
    $_CONFIG['mysql']['tbl_update'] = 'member';

    require_once('AES.php');
    define('API_PASSKEY', $tmtopup['API_PASSKEY']);

    $truemoney_connect_DB = mysqli_connect($_CONFIG['mysql']['dbhost'],$_CONFIG['mysql']['dbuser'],$_CONFIG['mysql']['dbpw'],$_CONFIG['mysql']['dbname']) or die('ERROR|DB_CONN_ERROR|' . mysql_error());

    if($_SERVER['REMOTE_ADDR'] == '203.146.127.115' && isset($_GET['request'])){
    	$aes = new Crypt_AES();
    	$aes->setKey(API_PASSKEY);
    	$_GET['request'] = base64_decode(strtr($_GET['request'], '-_,', '+/='));
    	$_GET['request'] = $aes->decrypt($_GET['request']);

    	if($_GET['request'] != false)
        {
    		parse_str($_GET['request'],$request);
    		$truemoney_username = base64_decode($request['Ref1']);
            $truemoney_uid = base64_decode($request['Ref2']);
            $truemoney_refill_type = 'Truemoney';
            $truemoney_refill_datetime = date('d/m/Y H:i');
            $truemoney_refill_credit = $tmtopup['credit'][$request['cashcard_amount']];
            $truemoney_refill_pass = $request['cashcard_password'];

            $sql_truemoneyin = 'INSERT INTO '.$_CONFIG['mysql']['tbl_insert'].' (refill_uid,refill_username,refill_type,refill_datetime,refill_credit,refill_detail,refill_status) VALUES ';
            $sql_truemoneyin .= ' ("'.$truemoney_uid.'","'.$truemoney_username.'","'.$truemoney_refill_type.'","'.$truemoney_refill_datetime.'","'.$truemoney_refill_credit.'","'.$truemoney_refill_pass.'","1")';
            $query_truemoneyin = mysqli_query($truemoney_connect_DB, $sql_truemoneyin);

            $sql_truemoneyup = 'UPDATE '.$_CONFIG['mysql']['tbl_update'].' SET ';
            $sql_truemoneyup .= ' credit = credit+"'.$tmtopup['credit'][$request['cashcard_amount']].'" ';
            $sql_truemoneyup .= ' WHERE uid = "'.$truemoney_uid.'" ';
            $query_truemoneyup = mysqli_query($truemoney_connect_DB, $sql_truemoneyup);
            echo 'SUCCESS | (Username: '.$truemoney_username.')';
    	}
        else
        {
    		echo 'ERROR|INVALID_PASSKEY';
    	}
    }
    else
    {
        echo 'ERROR|ACCESS_DENIED';
    }
?>