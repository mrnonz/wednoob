<?php if(isset($_SESSION['member_uid']) != NULL): ?>
    <h4>ประวัติการสั่งซื้อ</h4>
    <div class="row" style="padding: 10px 10px;">
        <div class="table-responsive">
            <?php
            $sql_historyorder = 'SELECT * FROM `order` WHERE `order_uid` = "'.$_SESSION['member_uid'].'" ORDER BY `order_id` DESC';
            $query_historyorder = $connect->query($sql_historyorder);
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-lg-1 text-center">Order ID</th>
                        <th class="col-lg-6">ชื่อสินค้า</th>
                        <th></th>
                        <th class="col-lg-1">จำนวนคิวที่รอ</th>
                        <th class="col-lg-2 text-center" colspan="2">จำนวนเงิน</th>
                        <th class="col-lg-2 text-center" colspan="2">สถานะ</th>
                    </tr>
                </thead>
                <?php
                    $numrows_historyorder = $query_historyorder->num_rows;
                    if($numrows_historyorder == '0'):
                ?>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center">คุณยังไม่มีประวัติการสั่งซื้อ</td>
                    </tr>
                </tbody>
                <?php
                    else:
                        while($historyorder = $query_historyorder->fetch_assoc()):
                            $sql_orderreceived = 'SELECT * FROM `orderreceived` WHERE `orderreceived_order_id` = "'.$historyorder['order_id'].'"';
                            $query_orderreceived = $connect->query($sql_orderreceived);
                            $orderreceived = $query_orderreceived->fetch_assoc();
                ?>
                <tbody>
                    <tr>
                        <td class="text-center"><?php echo $historyorder['order_id']; ?></td>
                        <td><?php echo $historyorder['order_product_name']; ?></td>
                        <td>
                            <a href="javascript:showotherdetail('<?php echo $historyorder['order_id']; ?>');" title="รายละเอียดเพิ่มเติม">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <?php
                                $sql_asc_order = "SELECT * FROM `order` WHERE order_status = '0' ORDER BY order_id ASC LIMIT 1";
                                $query_asc_order = $connect->query($sql_asc_order);
                                $asc_order = $query_asc_order->fetch_assoc();
                                $num_order = $query_asc_order->num_rows;
                                
                                if($num_order == 0)
                                {
                                    echo 'ถึงคิวของคุณแล้ว';
                                }
                                else
                                {
                                    $q_order = $historyorder['order_id'] - $asc_order['order_id'];
                                    if($q_order <= 0)
                                    {
                                        echo 'ถึงคิวของคุณแล้ว';
                                    }
                                    else
                                    {
                                        echo $q_order.' คิว';
                                    }
                                }
                            ?>
                        </td>
                        <td class="text-right"><?php echo $historyorder['order_total']; ?></td>
                        <td class="text-center">บาท</td>
                        <td class="text-center">
                            <?php
                            if($historyorder['order_status'] == '0'){
                                echo '<span class="label label-warning">รอการจัดส่ง</span>';
                            } elseif($historyorder['order_status'] == '1'){
                                echo '<span class="label label-success">ส่งเรียบร้อย</span>';
                            } else {
                                echo '<span class="label label-danger">ยกเลิกรายการ</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                            if($historyorder['order_status'] == '1'){
                                echo '<a href="member.php?page=vieworder&orderid='.$orderreceived['orderreceived_order_id'].'&key='.$orderreceived['orderreceived_key'].'" class="vieworder"><div class="label label-default">รับสินค้า</div></a>';
                            } else {
                                echo '<div class="label label-default" style="background-color: #d0d0d0;">รับสินค้า</div>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr id="<?php echo $historyorder['order_id']; ?>" style="display:none">
                        <td colspan="7">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">วัน/เวลาที่สั่งซื้อ</th>
                                        <th class="text-center">วัน/เวลาที่จัดส่ง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="col-lg-5 text-center">
                                            <?php 
                                            // Convert datetime Format
                                            $rs_order_date = date_create($historyorder['order_date']);
                                            echo date_format($rs_order_date, "d/m/Y H:i:s"); 
                                            ?>
                                        </td>
                                        <td class="col-lg-5 text-center">
                                            <?php 
                                            if($historyorder['order_senddate'] == "0000-00-00 00:00:00"){
                                                echo 'อยู่ระหว่างการจัดส่ง';
                                            } else {
                                                // Convert datetime Format
                                                $rs_order_senddate = date_create($historyorder['order_senddate']);
                                                echo date_format($rs_order_senddate, "d/m/Y H:i:s"); 
                                            }
                                            ?>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
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
        header('location:'.$config['site_url'].'/member.php?page=login&to='.$config['site_url'].'/member.php?page=historyorder');
    endif;
?>