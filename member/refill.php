<?php if(isset($_SESSION['member_uid']) != NULL): ?>
<?php
    if($_GET['way'] == 'bank') {
        $refill_active_bank = 'active';
    } elseif($_GET['way'] == 'truemoney') {
        $refill_active_truemoney = 'active';
    } elseif($_GET['way'] == 'truewallet') {
        $refill_active_truewallet = 'active';
    } elseif($_GET['way'] == 'tmtopup') {
        $refill_active_tmtopup = 'active';
    }
    $query_user_name = $connect->query("SELECT * FROM member WHERE uid = '".$_SESSION["member_uid"]."'");
    $fetch_user_name = $query_user_name->fetch_assoc();
    if($fetch_user_name)
    {
        $user_name = $fetch_user_name['username'];
    }
    else
    {
        $user_name = $member['username'];
    }
?>
    <h4>เติมเงินเข้าบัญชี <?php echo $user_name; ?></h4>
    <div class="row" style="padding: 10px 10px;">
        <div class="col-md-3">
            <div class="list-group">
                <a href="?page=refill&way=bank" class="list-group-item <?php echo $refill_active_bank; ?>"><strong>โอนเงินผ่าน บัญชีธนาคาร</strong></a>
                <a href="?page=refill&way=truewallet" class="list-group-item <?php echo $refill_active_truewallet; ?>"><strong>โอนเงินผ่าน True Wallet</strong></a>
                <a href="?page=refill&way=truemoney" class="list-group-item <?php echo $refill_active_truemoney; ?>"><strong>เติมเงินด้วยบัตร Truemoney</strong></a>
            </div>
            <?
                if($_GET['way'] == 'truemoney')
                {
                    ?>
                    <div class="panel panel-primary" style="border-color:#da4453">
                        <div class="panel-heading" style="color:#fff;background-color:#da4453;border-color:#da4453"><center>ยอดการเติม TrueMoney ล่าสุด</center></div>
                        <div classs="panel-body">
                            <div class="list-group">
                                <a class="list-group-item ">
                                    <strong>ชื่อผู้ใช้:</strong> 
                                    <?
                                    $query_last_topup = $connect->query("SELECT * FROM refill WHERE refill_type = 'Truemoney' and refill_status = '1' ORDER BY refill_id DESC LIMIT 1");
                                    $fetch_last_topup = $query_last_topup->fetch_assoc();
                                    ?>
                                    <span class="badge"><?php echo $fetch_last_topup['refill_username'];?></span>
                                </a>
                                <a class="list-group-item ">
                                    <strong>จำนวน:</strong>
                                    <span class="badge"><?php echo $fetch_last_topup['refill_credit'];?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?
                }
            ?>
        </div>
        <div class="col-md-9">
            <div id="rs_refill"></div>
            <?php
            if($_GET['way'] == NULL){
                header('location: member.php?page=refill&way=bank');
            } elseif($_GET['way'] == 'bank') {
                include_once 'refill/bank.php';
            } elseif($_GET['way'] == 'truemoney') {
                include_once 'refill/truemoney.php';
            } elseif($_GET['way'] == 'truewallet') {
                include_once 'refill/truewallet.php';
                //header('location: http://topup.itemnoob.com');

                /*
                if($_SESSION['member_status'] != '5')
                {
                    header('location: http://topup.itemnoob.com');
                }
                else
                {
                    include_once 'refill/truewallet.php';
                }
                */
            } elseif($_GET['way'] == 'testjamebies') {
                include_once 'refill/testjamebies.php';
            } else {
                echo '<div class="well well-sm text-center" style="margin: 0px 5px;">กรุณาเลือกช่องทางที่ต้องการเติมเงิน</div>';
            }
            ?>
        </div>
    </div>
<?php
    else :
        header('location:'.$config['site_url'].'/member.php?page=login&to='.$config['site_url'].'/member.php?page=refill');
    endif;
?>