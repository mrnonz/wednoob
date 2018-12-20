<?php

// Function Insert
if(isset($_POST['new_bank_btn'])){
    $new_banking_name = $_POST['new_bank_name'];
    $new_banking_account = $_POST['new_bank_account'];
    $new_banking_ownername = $_POST['new_bank_ownername'];
    $new_banking_branch = $_POST['new_bank_branch'];
    $sql_new_banking = 'INSERT INTO payment_banking (bankname,bankaccount,bankownername,bankbranch) VALUES ("'.$new_banking_name.'","'.$new_banking_account.'","'.$new_banking_ownername.'","'.$new_banking_branch.'") ';
    $query_new_banking = $connect->query($sql_new_banking);
    if($query_new_banking){
        echo '<script>history.back();</script>';
    }
}

// Function Delete
if($_GET['action'] == 'delete'){
    $sql_delete_banking = 'DELETE FROM payment_banking WHERE bankid = "'.$_GET['id'].'"';
    $query_delete_banking = $connect->query($sql_delete_banking);
    if($query_delete_banking){
        echo '<script>history.back();</script>';
    }
}
?>
<h4>การเงิน</h4><hr/>
<div class="col-md-12">
    <h4>บัญชีธนาคาร</h4>
    <div class="table-responsive">
        <form name="new_bank_frm" method="post" action="">
            <?php
                $sql_paymentbanking = 'SELECT * FROM payment_banking ORDER BY bankid ASC';
                $query_paymentbanking = $connect->query($sql_paymentbanking);
            ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="col-md-3">ธนาคาร</th>
                    <th class="col-md-3 text-center">เลขที่บัญชี</th>
                    <th class="col-md-3 text-center">ชื่อบัญชี</th>
                    <th class="col-md-3 text-center">สาขา</th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while($bank_account = $query_paymentbanking->fetch_assoc()): 
                    if($bank_account['bankname'] == 'kbank'){
                        $bank = 'ธนาคารกสิกรไทย';
                    } elseif($bank_account['bankname'] == 'ktb'){
                        $bank = 'ธนาคารกรุงไทย';
                    } elseif($bank_account['bankname'] == 'scb'){
                        $bank = 'ธนาคารไทยพาณิชย์';
                    } elseif($bank_account['bankname'] == 'bbl'){
                        $bank = 'ธนาคารกรุงเทพ';
                    } elseif($bank_account['bankname'] == 'bay'){
                        $bank = 'ธนาคารกรุงศรีอยุธยา';
                    } elseif($bank_account['bankname'] == 'tmb'){
                        $bank = 'ธนาคารทหารไทย';
                    }
                ?>
                <tr>
                    <td><img src="images/icon/bank/<?= $bank_account['bankname'] ?>.png" width="18px;"/> <?= $bank; ?></td>
                    <td class="text-center"><?php echo $bank_account['bankaccount'];?></td>
                    <td class="text-center"><?php echo $bank_account['bankownername'];?></td>
                    <td class="text-center"><?php echo $bank_account['bankbranch'];?></td>
                    <td class="text-center">
                        <a href="?page=finance&action=delete&id=<?php echo $bank_account['bankid'];?>" onclick="return confirm('คุณกำลังจะลบบัญชีธนาคาร คุณแน่ใจแล้วหรือไม่?')"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td><input type="text" name="new_bank_name" class="form-control" autocomplete="off" required="" placeholder="ตัวย่อธนาคาร"/></td>
                    <td><input type="text" name="new_bank_account" class="form-control" autocomplete="off" required="" placeholder="เลขที่บัญชี"/></td>
                    <td><input type="text" name="new_bank_ownername" class="form-control" autocomplete="off" required="" placeholder="ชื่อบัญชี"/></td>
                    <td><input type="text" name="new_bank_branch" class="form-control" autocomplete="off" required="" placeholder="สาขา"/></td>
                    <td class="text-center"><input type="submit" name="new_bank_btn" class="btn btn-primary" value="บันทึก"/></td>
                </tr>
                <tr>
                    <td colspan="6">
                        <small>
                            *ตัวย่อ :
                            scb = ไทยพาณิชย์ | 
                            ktb = กรุงไทย | 
                            kbank = กสิกรไทย | 
                            bbl = กรุงเทพ | 
                            tmb = ทหารไทย | 
                            bay = กรุงศรีอยุธยา
                        </small>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
    </div>
</div>

<div class="col-md-12" style="margin-bottom: 20px;">
    <?php
        $sql_truewallet_select = 'SELECT webinfo_truewallet FROM webinfo WHERE webinfo_id = "1"';
        $query_truewallet_select = $connect->query($sql_truewallet_select);
        $truewallet_select = $query_truewallet_select->fetch_assoc();
        
        if(isset($_POST['edittruewallet_btn'])){
            $truewallet_number = $_POST['edittruewallet_number'];
            $sql_truewallet_update = 'UPDATE webinfo SET webinfo_truewallet = "'.$truewallet_number.'" WHERE webinfo_id = "1"';
            $truewallet_update = $connect->query($sql_truewallet_update);
            if($truewallet_update){
                echo '<script>history.back();</script>';
            }
        }
    ?>
    <h4>บัญชี True Wallet</h4>
    <form name="edittruewallet_frm" method="post" action="">
        <div class="input-group">
            <input type="text" name="edittruewallet_number" class="form-control" autocomplete="off" required="" value="<?php echo $truewallet_select['webinfo_truewallet']; ?>" maxlength="10">
            <div class="input-group-btn">
                <input type="submit" name="edittruewallet_btn" class="btn btn-primary" value="บันทึก"/>
            </div>
        </div>
        <br/>
        <?  
            $query_webinfo_ja = $connect->query("SELECT * FROM webinfo WHERE webinfo_id = '1'");
            $fetch_webinfo = $query_webinfo_ja->fetch_assoc();

            if(isset($_POST['change_type_tw']))
            {
                if($fetch_webinfo['webinfo_wallettype'] == '1')
                {
                    $type_change_tw = "0";
                }

                if($fetch_webinfo['webinfo_wallettype'] == '0')
                {
                    $type_change_tw = "1";
                }

                $update_type_tw = $connect->query("UPDATE webinfo SET webinfo_wallettype = '".$type_change_tw."' WHERE webinfo_id = '1'");
                if($update_type_tw)
                {
                    echo '<script>history.back();</script>';
                }
            }
        ?>
        <div class="form-group">
            <center>
            <?
                if($fetch_webinfo['webinfo_wallettype'] == '1')
                {
                    ?>
                    <input name="change_type_tw" value="เปิดระบบ TrueWallet" type="submit" class="btn btn-success"/> <small>(ขณะนี้ระบบปิดอยู่)</small>
                    <?
                }

                if($fetch_webinfo['webinfo_wallettype'] == '0')
                {
                    ?>
                    <input name="change_type_tw" value="ปิดระบบ TrueWallet" type="submit" class="btn btn-danger"/> <small>(ขณะนี้ระบบเปิดอยู่)</small>
                    <?
                }
            ?>
            </center>
        </div>
    </form>
</div>
<h4> รายการเดินบัญชีธนาคาร</h4>
<div class="row">
    <div class="col-lg-12">
                <?php
                $sql_list = "SELECT * FROM bank_payment ";
                $query_list = $connect->query($sql_list);
                $numrows_list = $query_list->num_rows;
                $Per_Page = 30; // จำนวนต่อหน้า
                $Page = $_GET["p"];
                if(!$_GET["p"]){
                    $Page=1;
                }
                $Prev_Page = $Page-1;
                $Next_Page = $Page+1;
                $Page_Start = (($Per_Page*$Page)-$Per_Page);
                if($numrows_list<=$Per_Page){
                    $Num_Pages =1;
                } elseif(($numrows_list % $Per_Page)==0){
                    $Num_Pages =($numrows_list/$Per_Page) ;
                } else {
                    $Num_Pages =($numrows_list/$Per_Page)+1;
                    $Num_Pages = (int)$Num_Pages;
                }
                $sql_list .= " ORDER BY time DESC LIMIT $Page_Start , $Per_Page";
                $query_list = $connect->query($sql_list);
                ?>
            <h5>
                <i class="fa fa-circle" style="color:#dbe4ea"></i><small> = แจ้งชำระเงินแล้ว</small>
                <i class="fa fa-circle" style="color:#bdc3c7"></i><small> = ยังไม่แจ้งชำระเงิน</small>
            </h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">วัน/เวลา ที่โอน</th>
                            <th>ธนาคาร</th>
                            <th scope="col">ช่องทาง</th>
                            <th scope="col" class="text-right">จำนวนเงิน</th>
                            <th scope="col">รายละเอียด</th>
                            <th scope="col" class="text-center">สถานะ</th>
                        </tr>
                    </thead>
                    <?php
                    if($numrows_list == '0'):
                        echo '<tbody><tr><td colspan="7" class="text-center">ไม่พบรายการเดินบัญชี</td><tr></tbody>';
                    else:
                    ?>
                    <tbody>
                        <?php
                            while($list = $query_list->fetch_assoc()):
                                
                                if($list['status'] == '1'){
                                    $list_status = 'แจ้งแล้ว';
                                    $list_status_bg = '#dbe4ea';
                                } else {
                                    $list_status = 'ยังไม่แจ้ง';
                                    $list_status_bg = '#bdc3c7';
                                }
                                
                                // แยก เงินเข้า - เงินออก
                                $explodevalue_list = explode('-',$list['value']);
                                if($explodevalue_list['0'] != NULL):
                        ?>
                        <tr>
                            <td scope="row"><?= date('d/m/Y H:i', $list['time']); ?></td>
                            <td class="text-center"><img src="images/icon/bank/<?= $list['bank'] ?>.png" width="20px;"/></td>
                            <td><?= $list['channel']; ?></td>
                            <td class="text-right"><?= $list['value']; ?></td>
                            <td class="text-left"><?= $list['detail']; ?></td>
                            <td class="text-center" style="background-color:<?= $list_status_bg; ?>;"><?= $list_status; ?></td>
                        </tr>
                        <?php 
                                endif; 
                            endwhile; 
                        ?>
                    </tbody>
                    <?php endif; ?>
                </table>
            </div>
        
            <?php 
            // Pagination
            if($Num_Pages != '1'){
                echo '<ul class="pagination justify-content-center">';
                for($i = 1; $i <= $Num_Pages; $i++){
                    if($i != $Page){
                        echo "<li class='page-item'><a class='page-link' href='?page=".$_GET['page']."&p=$i'>$i</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><a class='page-link' href='?page=".$_GET['page']."&p=$i'>$i</a></li>";
                    }
                }
                echo '</ul>';
            }
            ?>
    </div>
</div>