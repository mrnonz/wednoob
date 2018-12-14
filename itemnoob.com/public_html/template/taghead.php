<?php
    // Select DB - webinfo
    $sql_webinfo = 'SELECT * FROM webinfo WHERE webinfo_id = "1"';
    $query_webinfo = $connect->query($sql_webinfo);
    $webinfo = $query_webinfo->fetch_assoc();
    
    $sql_get_namepage_product = 'SELECT * FROM product WHERE product_id = "'.$connect->real_escape_string($_GET['id']).'"';
    $query_get_namepage_product = $connect->query($sql_get_namepage_product);
    $get_namepage_product = $query_get_namepage_product->fetch_assoc();
    
    if($connect->real_escape_string($_GET['id']) != NULL){
        if($get_namepage_product){
            $title_namepage = $get_namepage_product['product_name'].' - ';
        } else {
            $strrsub_steamurl = $connect->real_escape_string($_GET['id']);
            include_once 'application/steam_api/get_steam.php';
            if ($steam_search_success == 1){
                $title_namepage = $steam_search_pname.' - ';
            }
        }
    } elseif($connect->real_escape_string($_GET['page']) == 'product-all') {
        $title_namepage = 'สินค้าทั้งหมด - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'product-sale') {
        $title_namepage = 'สินค้าลดราคา - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'refill') {
        $title_namepage = 'เติมเงิน - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'howto') {
        $title_namepage = 'วิธีสั่งซื้อ - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'login') {
        $title_namepage = 'เข้าสู่ระบบ - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'register') {
        $title_namepage = 'สมัครสมาชิก - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'forgot') {
        $title_namepage = 'ลืมรหัสผ่าน - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'profile') {
        $title_namepage = 'ข้อมูลส่วนตัว - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'historyrefill') {
        $title_namepage = 'ประวัติการเติมเงิน - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'historyorder') {
        $title_namepage = 'ประวัติการสั่งซื้อ - ';
    } elseif($connect->real_escape_string($_GET['page']) == 'vieworder') {
        $title_namepage = 'รับสินค้า - ';
    } elseif($connect->real_escape_string($_GET['page']) != NULL) {
        // Select DB - Category
        $product_category_title = $connect->query('SELECT * FROM product_category WHERE category_url = "'.$_GET['page'].'"')->fetch_assoc();
        if($product_category_title){
            $title_namepage = $product_category_title['category_name'].' - ';
        }
    } elseif($connect->real_escape_string($_GET['keyword']) != NULL) {
        $title_namepage = 'ผลการค้นหา '.$connect->real_escape_string($_GET['keyword']).' - ';
    } 
?>
<meta charset="<?php echo $config['charset_language']; ?>"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title_namepage.$webinfo['webinfo_title']; ?></title>
<meta name="description" content="<?php echo $webinfo['webinfo_description']; ?>">
<meta name="keywords" content="<?php echo $webinfo['webinfo_keyword']; ?>">
<meta name="copyright" content="DJdai Yodsapon">
<link rel="stylesheet" href="<?php echo $config['site_url'] ?>/css/bootstrap.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $config['site_url'] ?>/css/djdai_style.css">
<link rel="stylesheet" href="<?php echo $config['site_url'] ?>/css/datetimepicker.css">
<link rel="icon" type="image/png" href="<?php echo $config['site_url']; ?>/images/logo.png" />
<script type="text/javascript" src="<?php echo $config['site_url'] ?>/js/jquery-1.12.4.js"></script>
<script type="text/javascript" src='https://www.google.com/recaptcha/api.js'></script>

<!-- Start Meta Tag for Share on Facebook -->
<meta property="og:url" content="<?php echo $config['site_url']?><?php echo $_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:type" content="product" />
<meta property="og:title" ontent="<?php echo $title_namepage.$webinfo['webinfo_title']; ?>" />
<meta property="og:description" content="<?php echo $title_namepage.$webinfo['webinfo_description']; ?>" />
<?php
    if($_GET['id'] != NULL){
        $select_fb_img = $connect->query('SELECT * FROM '.product.' WHERE product_id = '.$_GET['id'])->fetch_assoc();
        if($select_fb_img){
            echo '<meta property="og:image" content="'.$config['site_url'].'/images/product/'.$get_namepage_product['product_image'].'" />';
        } else {
            if ($steam_search_success == 1){
                echo '<meta property="og:image" content="'.$steam_search_pimageurl.'" />';
            }
        }
    } else {
        echo '<meta property="og:image" content="'.$config['site_url'].'/images/logo.png" />';
    }
?>
<!-- End Meta Tag for Share on Facebook -->

<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->