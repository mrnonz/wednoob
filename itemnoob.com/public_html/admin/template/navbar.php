<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" style="color:#ffffff;">
                <i class="fa fa-bars"></i> เมนูหลัก
            </button>
        </div>
        
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="./admin.php">ภาพรวมร้านค้า</a></li>
                    <li><a href="?page=product">สินค้า</a></li>
                    <li><a href="?page=listorder">รายการสั่งซื้อ</a></li>
                    <li><a href="?page=listrefill">รายการเติมเงิน</a></li>
                    <li><a href="?page=finance">การเงิน</a></li>
                    <li><a href="?page=member">สมาชิก</a></li>
                    <li><a href="?page=datastore">ข้อมูลร้านค้า</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                    $sql_adminnavbar = 'SELECT sum(credit) as sum_credit FROM member';
                    $adminnavbar = $connect->query($sql_adminnavbar)->fetch_assoc();
                ?>
                <li class="navbar-text">รวมยอดเงินในระบบ : <strong><?=number_format($adminnavbar['sum_credit'],2);?></strong> บาท</li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> MENU <b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="?page=twaccount">บัญชี TrueWallet</a></li>
                        <li><a href="?page=eventplatform">Event Platform</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $config['site_url']; ?>" target="_blank">ดูหน้าร้าน</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>