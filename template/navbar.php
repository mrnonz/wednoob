        <!-- Navbar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
            <div class="container container-table">
                <!-- Nav Header -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbaridcollapse-1" aria-expanded="false" style="color:#ffffff;">
                        <i class="fa fa-bars"></i> เมนูหลัก
                    </button>
                    <a class="navbar-brand" href="<?php echo $config['site_url']; ?>">
                        <img src="images/logo.png" style="border-radius: 8px 8px 8px 8px;" height="40px"/>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="navbaridcollapse-1">
                    <!-- Nav Menu Left -->
                    <ul class="nav navbar-nav navbar-left">
                        <li class="active"><a class="btn btn-success" style="border-radius: 15px 15px 15px 15px; border-color:#da4453" href="<?php echo $config['site_url']; ?>"><i class="fa fa-home" aria-hidden="true"></i> หน้าแรก</a></li>
                        <? /*
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list" aria-hidden="true"></i> หมวดหมู่สินค้า <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php
                                    // Select DB - product_category
                                    $sql_product_category = 'SELECT * FROM product_category ORDER BY category_id ASC';
                                    $query_product_category = $connect->query($sql_product_category);
                                    while($product_category = $query_product_category->fetch_assoc()):
                                ?>
                                    <li><a href="category.php?page=<?php echo $product_category['category_url']; ?>" title="<?php echo $product_category['category_name']; ?>"><?php echo $product_category['category_name']; ?></a></li>
                                <?php endwhile; ?>
                                <li><a href="<?php echo $config['site_url']; ?>/?page=product-sale">สินค้าลดราคา</a></li>
                                <li><a href="<?php echo $config['site_url']; ?>/?page=product-all">สินค้าทั้งหมด</a></li>
                            </ul>
                        </li>
                        */ ?>
                        <li><a href="<?php echo $config['site_url']; ?>/member.php?page=refill"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> เติมเงิน</a></li>
                        <li><a href="<?php echo $config['site_url']; ?>/?page=howto"><i class="fa fa-book" aria-hidden="true"></i> วิธีสั่งซื้อ</a></li>
                        <?
                        if($_SESSION['member_uid'] != NULL)
                        {
                            ?>
                            <li><a href="<?php echo $config['site_url']; ?>/member.php?page=historyorder"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i> ประวัติการสั่งซื้อ</a></li>
                            <li><a href="<?php echo $config['site_url']; ?>/member.php?page=historyrefill"><i class="fa fa-history" aria-hidden="true"></i> ประวัติการเติมเงิน</a></li>
                            <?
                        }
                        ?>
                        
                    </ul>
                    <!-- /Nav Menu Left -->
                    
                    <!-- Search Box -->
                    <form class="navbar-form navbar-left" role="search" method="get" action="search.php">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" style="border-color:#da4453" name="keyword" id="keyword" class="form-control" placeholder="ค้นหาสินค้า.." value="<?php echo $_GET['keyword']; ?>" autocomplete="off">
                                <div class="input-group-btn">
                                    <button type="submit" style="border-color:#da4453" class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- /Search Box -->
                    
                    <!-- Nav Menu Right -->
                    <ul class="nav navbar-nav navbar-right">
                        <?php if($_SESSION['member_uid'] == NULL): ?>
                            <li><a href="<?php echo $config['site_url']; ?>/member.php?page=login&to=<?php echo $config["site_url"].$_SERVER["REQUEST_URI"]; ?>"><i class="fa fa-sign-in" aria-hidden="true"></i> เข้าสู่ระบบ</a></li>
                            <li><a href="<?php echo $config['site_url']; ?>/member.php?page=register"><i class="fa fa-user-plus" aria-hidden="true"></i> สมัครสมาชิก</a></li>
                        <?php 
                            else : 
                                $sql_member = 'SELECT * FROM member WHERE uid = "'.$_SESSION['member_uid'].'"';
                                $query_member = $connect->query($sql_member);
                                $member = $query_member->fetch_assoc();

                                $query_user_name = $connect->query("SELECT * FROM member WHERE uid = '".$_SESSION["member_uid"]."'");
                                $fetch_user_name = $query_user_name->fetch_assoc();
                                if($fetch_user_name)
                                {
                                    $user_name = $fetch_user_name['username'];
                                    $user_point = $fetch_user_name['credit'];
                                }
                                else
                                {
                                    $user_name = $member['username'];
                                    $user_point = $fetch_user_name['credit'];
                                }

                                // IF ISSET _SESSION (USERNAME)
                                
                                if($_SESSION["username"] != NULL)
                                {
                                    //$sql_username_check = 'SELECT * FROM member WHERE uid = "'$_SESSION["member_uid"]'"';
                                    //$query_username_check = $connect->query($sql_username_check);
                                    //$fetch_username_check = $query_username_check->fetch_assoc();
                                    
                                    if($_SESSION["username"] != $fetch_user_name['username'])
                                    {
                                        header('location:'.$config["site_url"].$_SERVER["REQUEST_URI"]);
                                    }
                                }
                        ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> <?php echo $user_name; ?> <b class="caret"></b></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="text-center" style="color:#ffffff;font-size:15px;">
                                        <a href="<?php echo $config['site_url']; ?>/member.php?page=refill"><b><?php echo number_format($user_point,2); ?> บาท</b></a>
                                    </li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo $config['site_url']; ?>/member.php?page=profile"><i class="glyphicon glyphicon-info-sign"></i> ข้อมูลส่วนตัว</a></li>
                                    <li><a href="<?php echo $config['site_url']; ?>/member.php?page=refill"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> เติมเงิน</a></li>
                                    <?/*
                                    <li><a href="<?php echo $config['site_url']; ?>/member.php?page=historyorder">ประวัติการสั่งซื้อ</a></li>
                                    <li><a href="<?php echo $config['site_url']; ?>/member.php?page=historyrefill">ประวัติการเติมเงิน</a></li>
                                    <li class="divider"></li>
                                    */?>
                                    <li class="divider"></li>
                                    <?php
                                        if($_SESSION['member_status'] == "5"){
                                            echo '<li><a href="'.$config['site_url'].'/admin.php"><i class="glyphicon glyphicon-cog"></i> จัดการเว็บไซต์</a></li>';
                                        }
                                    ?>
                                    <li><a href="<?php echo $config['site_url']; ?>/member.php?page=logout&to=<?php echo $config["site_url"].$_SERVER["REQUEST_URI"]; ?>"><i class="glyphicon glyphicon-off"></i> ออกจากระบบ</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <!-- /Nav Menu Right -->
                </div>
                <!-- /Nav Header -->
            </div>
            </div>
        </nav>
        <!-- /Navbar -->