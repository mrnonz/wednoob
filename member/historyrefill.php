<?php if(isset($_SESSION['member_uid']) != NULL): ?>
    <h4>ประวัติการเติมเงิน</h4>
    <div class="row" style="padding: 10px 10px;">
        <div class="table-responsive">
            <?php
            $sql_historyrefill = 'SELECT * FROM `refill` WHERE `refill_uid` = "'.$_SESSION['member_uid'].'" ORDER BY `refill_id` DESC';
            $query_historyrefill = $connect->query($sql_historyrefill);
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-lg-1 text-center">Refill ID</th>
                        <th class="col-lg-2 text-center">วัน/เวลา ที่เติมเงิน</th>
                        <th class="col-lg-2">ช่องทาง</th>
                        <th class="col-lg-4">รายละเอียด</th>
                        <th class="col-lg-2 text-center" colspan="2">จำนวนเงิน</th>
                        <th class="col-lg-1 text-center">สถานะ</th>
                    </tr>
                </thead>
                <?php
                    $numrows_historyrefill = $query_historyrefill->num_rows;
                    if($numrows_historyrefill == '0'):
                ?>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">คุณยังไม่มีประวัติการเติมเงิน</td>
                    </tr>
                </tbody>
                <?php
                    else:
                        while($historyrefill = $query_historyrefill->fetch_assoc()):
                ?>
                <tbody>
                    <tr>
                        <td class="text-center"><?php echo $historyrefill['refill_id']; ?></td>
                        <td class="text-center"><?php echo $historyrefill['refill_datetime']; ?></td>
                        <td><?php echo $historyrefill['refill_type']; ?></td>
                        <td><?php echo $historyrefill['refill_detail']; ?></td>
                        <td class="text-right"><?php echo $historyrefill['refill_credit']; ?></td>
                        <td class="text-center">บาท</td>
                        <td class="text-center">
                            <?php
                            if($historyrefill['refill_status'] == '0'){
                                echo '<span class="label label-warning">รออนุมัติ</span>';
                            } elseif($historyrefill['refill_status'] == '1'){
                                echo '<span class="label label-success">อนุมัติแล้ว</span>';
                            } else {
                                echo '<span class="label label-danger">ไม่อนุมัติ</span>';
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
                <?php
                        endwhile;
                    endif;
                ?>
            </table>
        </div>
    </div>
<?php
    else :
        header('location:'.$config['site_url'].'/member.php?page=login&to='.$config['site_url'].'/member.php?page=historyrefill');
    endif;
?>