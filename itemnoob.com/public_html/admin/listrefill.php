<?php
    if($_GET['action'] == 'approval'){
        $select_refillstatus = $connect->query('SELECT * FROM refill WHERE refill_id = "'.$_GET['refillid'].'" ')->fetch_assoc();
        if($select_refillstatus['refill_status'] == '0'){
        	$update_refillstatus = $connect->query('UPDATE refill SET refill_status = "1" WHERE refill_id = "'.$_GET['refillid'].'" AND refill_status != "1"');
        	if($update_refillstatus){
            		$upcredit = $_GET['upcredit'];
            		$update_credittouser = $connect->query('UPDATE member SET credit = credit+"'.$upcredit.'" WHERE uid = "'.$_GET['uid'].'"');
            		if($update_credittouser){
                		header('location: ?page=listrefill&p='.$_GET['p']);
            		}
        	}
        } else {
	echo '<script>alert("รายการนี้ได้รับการอนุมัติยอดเงินแล้ว"); window.history.back();</script>';
        }
    } elseif($_GET['action'] == 'disapproval'){
        $update_refillstatus = $connect->query('UPDATE refill SET refill_status = "2" WHERE refill_id = "'.$_GET['refillid'].'"');
        if($update_refillstatus){
            header('location: ?page=listrefill&p='.$_GET['p']);
        }
    }

    $sql_allrefill_s = 'SELECT sum(refill_credit) as sum_refill_credit FROM refill WHERE refill_status = "1"';
    $query_allrefill_s = $connect->query($sql_allrefill_s)->fetch_assoc();
?>
<h4>รายการเติมเงิน <small>(ยอดการเติมเงินทั้งหมด: <?=number_format($query_allrefill_s['sum_refill_credit'],2);?>)</small></h4><hr/>
<?php
    // Select DB - refill
    $sql_refill = "SELECT * FROM refill ";
    $query_refill = $connect->query($sql_refill);
    $numrows_refill = $query_refill->num_rows;
    $Per_Page = 30;
    $Page = $_GET["p"];
    if(!$_GET["p"]){
        $Page=1;
    }
    $Prev_Page = $Page-1;
    $Next_Page = $Page+1;
    $Page_Start = (($Per_Page*$Page)-$Per_Page);
    if($numrows_refill<=$Per_Page){
        $Num_Pages =1;
    } elseif(($numrows_refill % $Per_Page)==0){
        $Num_Pages =($numrows_refill/$Per_Page) ;
    } else {
        $Num_Pages =($numrows_refill/$Per_Page)+1;
        $Num_Pages = (int)$Num_Pages;
    }
    $sql_refill .= " ORDER BY refill_id DESC LIMIT $Page_Start , $Per_Page";
    $query_refill = $connect->query($sql_refill);
?>
<div class="col-lg-12 table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Refill ID</th>
                <th class="col-md-2 text-center">วันที่/เวลา</th>
                <th class="col-md-2">Username</th>
                <th class="col-md-5">ช่องทาง</th>
                <th class="col-md-1 text-center">จำนวนเงิน</th>
                <th class="col-md-2 text-center" colspan="2">จัดการ</th>
            </tr>
        </thead>
        <?php if($numrows_refill == '0'): ?>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">ไม่พบรายการเติมเงิน</td>
            </tr>
        </tbody>
        <?php else: ?>
        <tbody>
            <?php 
                while($refill = $query_refill->fetch_assoc()):
                    $sql_member = "SELECT * FROM member WHERE username = '".$refill['refill_username']."'";
                    $query_member = $connect->query($sql_member);
                    $member_refill = $query_member->fetch_assoc();
            ?>
            <tr>
                <td class="text-center"><?php echo $refill['refill_id']; ?></td>
                <td class="text-center"><?php echo $refill['refill_datetime']; ?></td>
                <td><a href="?page=member&keyword=<?php echo $refill['refill_uid']; ?>" target="_blank"><?php echo $member_refill['username']; ?></a></td>
                <td><?php echo $refill['refill_type']; ?> : <?php echo $refill['refill_detail']; ?> <?php
                    if($refill['refill_code'] != NULL or $refill['refill_code'] != "")
                    {
                        echo "(".$refill['refill_code']." ";
                        if($refill['refill_code'] == "-1002")
                        {
                            echo "รหัสบัตรเติมเงินผิดพลาด)";
                        }
                        elseif($refill['refill_code'] == "-1")
                        {
                            echo "Truewallet is Blocked)";
                        }
                        else
                        {
                            echo ")";
                        }
                    }
                    else
                    {
                        if($refill['refill_rdmUser'] != NULL or $refill['refill_rdmUser'] != "")
                        {
                            echo "(".$refill['refill_rdmUser'].")";
                        }
                    }
                 ?></td>
                <td class="text-right"><?php echo $refill['refill_credit']; ?></td>
                <td class="text-center">
                    <?php
                    if($refill['refill_status'] == '0'){ 
                            echo '<a href="?page=listrefill&action=approval&p='.$Page.'&refillid='.$refill['refill_id'].'&uid='.$refill['refill_uid'].'&upcredit='.$refill['refill_credit'].'" title="อนุมัติ?" onclick="return confirm(\'อนุมัติยอดเงินจำนวน '.$refill["refill_credit"].' ให้กับ '.$member_refill["username"].'? \')"><span class="label label-warning">อนุมัติ</span></a> ';
                            echo '<a href="?page=listrefill&action=disapproval&p='.$Page.'&refillid='.$refill['refill_id'].'" title="ไม่อนุมัติ?" onclick="return confirm(\'ไม่อนุมัติยอดเงินจำนวน '.$refill["refill_credit"].' ให้กับ '.$member_refill["username"].'? \')"><span class="label label-danger">ไม่อนุมัติ</span></a>';
                        } elseif($refill['refill_status'] == '1') {
                            echo '<span class="label label-success">อนุมัติแล้ว</span>';
                        } else {
                            echo '<span class="label label-danger">ไม่อนุมัติ</span>';
                        }
                    ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <?php endif; ?>
    </table>
</div>
<div class="col-lg-12">
    <nav class="text-center">
        <ul class="pagination">
        <?php
            for($i=1; $i<=$Num_Pages; $i++){
                if($i != $Page){
                    echo "<li><a href='?page=".$_GET['page']."&p=$i'>$i</a></li>";
                } else {
                    echo "<li class='active'><a href='?page=".$_GET['page']."&p=$i'>$i</a></li>";
                }
            }
        ?>
        </ul>
    </nav>
</div>
