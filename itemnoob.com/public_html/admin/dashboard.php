<?php
    $sumrecord_dashboardOrder = $connect->query('SELECT * FROM `order` WHERE `order_status` = 0')->num_rows;
    $sumrecord_dashboardRefill = $connect->query('SELECT * FROM `refill` WHERE `refill_status` = 0')->num_rows;
    $sumrecord_dashboardProduct = $connect->query('SELECT * FROM `product`')->num_rows;
    $sumrecord_dashboardMember = $connect->query('SELECT * FROM `member`')->num_rows;
    $webinfo_dashboard = $connect->query('SELECT * FROM `webinfo` WHERE `webinfo_id` = 1')->fetch_assoc();
?>
<script type="text/javascript">
        /* START Notification System on ajax by.jQuery*/
        // Function for Change Title Page
        function notificationTitle() {
            document.title = "Web Controller - by DJdai";
        }
        // Forction for play notification sound.
        function notificationPlay(){
            var notificationSound = document.createElement('audio');
            notificationSound.setAttribute('src', 'admin/template/sound/notification_sound.mp3');
            notificationSound.setAttribute('autoplay', 'autoplay');
            notificationSound.load();
        }
        var old_NumOrder = <?php echo $sumrecord_dashboardOrder; ?>; // กำหนดตัวแปร old_NumOrder
        var old_NumRefill = <?php echo $sumrecord_dashboardRefill; ?>; // กำหนดตัวแปร old_NumRefill
        $(function(){
            setInterval(function(){
                // Ajax for Order Sending 
                $.ajax({
                    type: "GET",
                    url: "autoload.php?func=ordersending",
                    async:false,
                    success:function(getNumOrder) {
                        if(old_NumOrder != getNumOrder) {
                            notificationPlay(); // เรียกใช้ฟังค์ชั่นเล่นเสียง
                            document.title = "("+ getNumOrder +") Web Controller - by DJdai"; // เปลี่ยน Title Page
                        } old_NumOrder = getNumOrder;
                        $("#showOrderSending").html(getNumOrder); // นำข้อมูลมาแสดง
                    }
                });
                // Ajax for Order Completed
                $.ajax({
                    type: "GET",
                    url: "autoload.php?func=ordercompleted",
                    async:false,
                    success:function(getNumOrderCompleted) {
                        $("#showOrderCompleted").html(getNumOrderCompleted); // นำข้อมูลมาแสดง
                    }
                }); 
                // Ajax for Refill Pending
                $.ajax({
                    type: "GET",
                    url: "autoload.php?func=refillpending",
                    async:false,
                    success:function(getNumRefill) {
                        if(old_NumRefill != getNumRefill) {
                            notificationPlay(); // เรียกใช้ฟังค์ชั่นเล่นเสียง
                            document.title = "("+ getNumRefill +") Web Controller - by DJdai"; // เปลี่ยน Title Page
                        } old_NumRefill = getNumRefill;
                        $("#showRefillPending").html(getNumRefill); // นำข้อมูลมาแสดง
                    }
                });
                // Ajax for Refill Approval
                $.ajax({
                    type: "GET",
                    url: "autoload.php?func=refillapproval",
                    async:false,
                    success:function(getNumRefillApproval) {
                        $("#showRefillApproval").html(getNumRefillApproval); // นำข้อมูลมาแสดง
                    }
                });
                // Ajax for All Credit Member
                $.ajax({
                    type: "GET",
                    url: "autoload.php?func=allcredit",
                    async:false,
                    success:function(getNumCreditMember) {
                        $("#showCreditMember").html(getNumCreditMember); // นำข้อมูลมาแสดง
                    }
                });
            },10000);
        });
    /* END Notification System on ajax by.jQuery*/
</script>
<script type="text/javascript" src="<?=$config['site_url']?>/js/jquery.circlesload.js"></script>

<h4>ภาพรวมร้านค้า</h4><hr/>
<div class="col-lg-3">
    <div class="panel panel-default" style="border:0px;">
        <div class="panel-heading text-left" style="background:#0090d9;color:#ffffff;border:0px;border-radius:0px;"><strong><i class="fa fa-shopping-cart"></i> รายการสั่งซื้อ</strong></div>
        <div class="panel-body text-center" style="background:#0090d9;color:#ffffff;padding:20px 0px 40px 0px;">
            <div class="row">
                <div class="col-md-6 col-xs-6">
                    <small>จัดส่งแล้ว</small><br/><span id="showOrderCompleted" style="font-size:20px;"><i class="fa fa-spinner fa-pulse"></i></span><br/><small>รายการ</small>
                </div>
                <div class="col-md-6 col-xs-6">
                    <small>รอจัดส่ง</small><br/><span id="showOrderSending" style="font-size:20px;"><i class="fa fa-spinner fa-pulse"></i></span><br/><small>รายการ</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3">
    <div class="panel panel-default text-center" style="border:0px;">
        <div class="panel-heading text-left" style="background:#18a689;color:#ffffff;border:0px;border-radius:0px;"><strong><i class="fa fa-credit-card-alt"></i> รายการเติมเงิน</strong></div>
        <div class="panel-body text-center" style="background:#18a689;color:#ffffff;padding:20px 0px 40px 0px;">
            <div class="row">
                <div class="col-md-6 col-xs-6">
                    <small>อนุมัติแล้ว</small><br/><span id="showRefillApproval" style="font-size:20px;"><i class="fa fa-spinner fa-pulse"></i></span><br/><small>รายการ</small>
                </div>
                <div class="col-md-6 col-xs-6">
                    <small>รออนุมัติ</small><br/><span id="showRefillPending" style="font-size:20px;"><i class="fa fa-spinner fa-pulse"></i></span><br/><small>รายการ</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3 col-xs-6 text-center" style="color:#656565;">
    <strong>จำนวนสินค้าในร้าน</strong><br/>
    <div class="circle" id="circles-numProduct" style="display:inline-block; margin:1em;"></div>
    <script type="text/javascript">
        /* Start Circles chart stat */
        var circles = [];
        var circle = Circles.create({ // for numProduct
            id:         'circles-numProduct',
            value:      <?php echo $sumrecord_dashboardProduct; ?>, // ค่าปัจจุบัน
            maxValue:   500, // ค่ามากสุด
            radius:     65, // ขนาด
            width:      14, // ขนาดกรอบ
            colors:     ['#e5c9c9', '#c75757']
        });
        circles.push(circle);
        /* End Circles chart stat */
    </script>
</div>
<div class="col-lg-3 col-xs-6 text-center" style="color:#656565;">
    <strong>จำนวนสมาชิกทั้งหมด</strong><br/>
    <div class="circle" id="circles-numMember" style="display:inline-block; margin:1em;"></div>
    <script type="text/javascript">
        /* Start Circles chart stat */
        var circles = [];
        var circle = Circles.create({ // for numProduct
            id:         'circles-numMember',
            value:      <?php echo $sumrecord_dashboardMember; ?>, // ค่าปัจจุบัน
            maxValue:   100000, // ค่ามากสุด
            radius:     65, // ขนาด
            width:      14, // ขนาดกรอบ
            colors:     ['#cabbc8', '#7f4e77']
        });
        circles.push(circle);
        /* End Circles chart stat */
    </script>
</div>
<div class="col-md-12 col-xs-12">
    <div class="panel panel-default" style="border:0px;">
        <div class="panel-heading text-left" style="background:#3a404a;color:#ffffff;border:0px;border-radius:0px;"><strong><i class="fa fa-money"></i> รวมยอดเงินในระบบ</strong></div>
        <div class="panel-body text-right" style="background:#3a404a;color:#ffffff;padding:29px 15px 10px 15px;">
            <span id="showCreditMember" style="font-size:26px;"><i class="fa fa-spinner fa-pulse"></i></span> <small>บาท (THB)</small>
        </div>
    </div>
</div>
<div class="col-md-12">
    <strong>คำแนะนำเบื้องต้น : </strong>ให้เปิดหน้านี้ (ภาพรวมร้านค้า) ทิ้งไว้ เพื่อให้ได้รับการแจ้งเตือนเมื่อมีรายการสั่งซื้อ หรือเติมเงินใหม่เข้ามา
</div>