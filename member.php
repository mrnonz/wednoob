<?php

/* 
 *  This site Design with Bootstrap and Develop source code by. DJdai Yodsapon
 *  Can contact DJdai at y.wongchuen@gmail.com or +66800257281 (Thailand Number)
 */

require_once 'application/_config.php';
include_once 'template/header.php';
if($_GET['page'] == NULL){
    include_once 'application/error/404.php';
} elseif ($_GET['page'] == 'login') {
    include_once 'member/login.php';
} elseif ($_GET['page'] == 'logout') {
    include_once 'member/logout.php';
} elseif ($_GET['page'] == 'register') {
    include_once 'member/register.php';
} elseif ($_GET['page'] == 'forgot') {
    include_once 'member/forgot.php';
} elseif ($_GET['page'] == 'refill') {
    include_once 'member/refill.php';
} elseif ($_GET['page'] == 'profile') {
    include_once 'member/profile.php';
} elseif ($_GET['page'] == 'historyorder') {
    include_once 'member/historyorder.php';
} elseif ($_GET['page'] == 'historyrefill') {
    include_once 'member/historyrefill.php';
} elseif ($_GET['page'] == 'vieworder') {
    include_once 'member/vieworder.php';
} else {
    include_once 'application/error/404.php';
}
include_once 'template/footer.php';