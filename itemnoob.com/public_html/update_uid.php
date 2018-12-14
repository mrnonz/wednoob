<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

require_once 'application/_config.php';

$a = $connect->query('SELECT * FROM refill WHERE refill_uid = "" ');
while($aa = $a->fetch_assoc()){
    //print_r($aa);
    $b = $connect->query('SELECT * FROM member WHERE username = "'.$aa['refill_username'].'"')->fetch_assoc();
    $c = $connect->query('UPDATE refill SET refill_uid = "'.$b['uid'].'" WHERE refill_username = "'.$aa['refill_username'].'"');
}