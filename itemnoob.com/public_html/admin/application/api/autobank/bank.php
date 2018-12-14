<?php
include_once('../../_config.php');

if(!isset($KOBChecker)) {
    die('Contact "DJdai" about License Key.');
}

$GLOBALS['license_key'] = $autobank['LICENSE'];
if($KOBChecker) {
    $GLOBALS['kbank'] = array(
        "username"  => $autobank['KBANK']['username'],
        "password"  => $autobank['KBANK']['password'],
        "acc_num"   => $autobank['KBANK']['account']
    );
    $GLOBALS['scb'] = array(
        "username"  => $autobank['SCB']['username'],
        "password"  => $autobank['SCB']['password'],
        "acc_num"   => $autobank['SCB']['account']
    );
    $GLOBALS['bbl'] = array(
        "username"  => $autobank['BBL']['username'],
        "password"  => $autobank['BBL']['password'],
        "acc_num"   => $autobank['BBL']['account']
    );
    $GLOBALS['ktb'] = array(
        "username"  => $autobank['KTB']['username'],
        "password"  => $autobank['KTB']['password'],
        "acc_num"   => $autobank['KTB']['account'],
        "is_netbank_acc"    => false
    );
    $GLOBALS['tmb'] = array(
        "username"  => $autobank['TMB']['username'],
        "password"  => $autobank['TMB']['password'],
        "acc_num"   => $autobank['TMB']['account']
    );
    $GLOBALS['bay'] = array(
        "username"  => $autobank['BAY']['username'],
        "password"  => $autobank['BAY']['password'],
        "acc_num"   => $autobank['BAY']['account']
    );
}
$GLOBALS['database'] = array(
    "host"=>$db_host,
    "username"=>$db_username,
    "password"=>$db_password,
    "dbname"=>$db_name
);
$GLOBALS['secret'] = array(
    "api" => $autobank['SECRET'],
);
