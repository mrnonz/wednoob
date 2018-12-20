    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">ธนาคาร</th>
                    <th class="text-center">เลขที่บัญชี</th>
                    <th class="text-center">ชื่อบัญชี</th>
                    <th class="text-center">สาขา</th>
                </tr>
            </thead>
    <?php
    $sql_listbank = 'SELECT * FROM payment_banking ORDER BY bankid ASC';
    $query_listbank = $connect->query($sql_listbank);
    while ($listbank = $query_listbank->fetch_assoc()):
                    if($listbank['bankname'] == 'kbank'){
                        $bank = 'ธนาคารกสิกรไทย';
                    } elseif($listbank['bankname'] == 'ktb'){
                        $bank = 'ธนาคารกรุงไทย';
                    } elseif($listbank['bankname'] == 'scb'){
                        $bank = 'ธนาคารไทยพาณิชย์';
                    } elseif($listbank['bankname'] == 'bbl'){
                        $bank = 'ธนาคารกรุงเทพ';
                    } elseif($listbank['bankname'] == 'bay'){
                        $bank = 'ธนาคารกรุงศรีอยุธยา';
                    } elseif($listbank['bankname'] == 'tmb'){
                        $bank = 'ธนาคารทหารไทย';
                    }
    ?>
            <tbody>
                <tr>
                    <td><img src="images/icon/bank/<?= $listbank['bankname'] ?>.png" width="18px;"/> <?= $bank; ?></td>
                    <td class="text-center"><?php echo $listbank['bankaccount']; ?></td>
                    <td class="text-center"><?php echo $listbank['bankownername']; ?></td>
                    <td class="text-center"><?php echo $listbank['bankbranch']; ?></td>
                </tr>
            </tbody>
    <?php endwhile; ?>
        </table>
    </div>

    <div class="well well-sm">หลังโอนเงินแล้วกรุณารออย่างน้อย 5 นาที ก่อนแจ้งโอนเงิน เพื่อให้ระบบอัพเดทรายการจากธนาคาร</div>
    
    <h4>แจ้งโอนเงิน</h4>
    <div class="col-sm-6 col-sm-offset-3">
        <form name="refillbank_frm" id="refillbank_frm" method="POST" action="javascript:void(0);">
            <input type="hidden" name="refillbank_username" id="refillbank_username" value="<?php echo $member['username']; ?>"/>
            <div class="form-group col-md-12">
                <label class="control-label">ธนาคาร : </label>
                    <select name="refillbank_bank" id="refillbank_bank" class="form-control" required="">
                        <optgroup label="เลือกธนาคาร">
                            <?php 
                                $sql_listbank = 'SELECT * FROM payment_banking ORDER BY bankid ASC';
                                $query_listbank = $connect->query($sql_listbank);
                                while ($listbank = $query_listbank->fetch_assoc()):
                                    if($listbank['bankname'] == 'kbank'){
                                        $bank2 = 'ธนาคารกสิกรไทย';
                                    } elseif($listbank['bankname'] == 'ktb'){
                                        $bank2 = 'ธนาคารกรุงไทย';
                                    } elseif($listbank['bankname'] == 'scb'){
                                        $bank2 = 'ธนาคารไทยพาณิชย์';
                                    } elseif($listbank['bankname'] == 'bbl'){
                                        $bank2 = 'ธนาคารกรุงเทพ';
                                    } elseif($listbank['bankname'] == 'bay'){
                                        $bank2 = 'ธนาคารกรุงศรีอยุธยา';
                                    } elseif($listbank['bankname'] == 'tmb'){
                                        $bank2 = 'ธนาคารทหารไทย';
                                    }
                            ?>
                            <option value="<?php echo $listbank['bankname']; ?>"><?php echo $bank2; ?> (<?php echo $listbank['bankaccount']; ?>)</option>
                            <?php endwhile; ?>
                        </optgroup>
                    </select>
            </div>
            <div class="form-group col-md-12">
                <label class="control-label">วัน/เวลา ที่โอน : </label>
                <input type="text" name="refillbank_datetime" id="datetimepicker" class="form-control" required="" autocomplete="off"/>
            </div>
            <div class="form-group col-md-12">
                <label class="control-label">จำนวนเงิน : </label>
                <div class="input-group">
                    <input type="text" name="refillbank_credit" id="refillbank_credit" class="form-control" required="" autocomplete="off" onkeypress="return NumbersandDot(event);"/>
                    <div class="input-group-addon">บาท</div>
                </div>
            </div>
            <!-- <div class="form-group col-md-12">
                <label class="control-label">Captcha : </label>
                <div class="g-recaptcha" id="refill_recaptcha" data-sitekey="6LeG2yITAAAAAKknoj91Ww0tWFlx6gyoFYBBIvQd"></div>
            </div> -->
            <div class="col-md-12 text-center">
                <button type="submit" name="refillbank_btn" id="refillbank_btn" class="btn btn-primary" onclick="RefillBank()">แจ้งเติมเงิน</button>
            </div>
        </form>
    </div>