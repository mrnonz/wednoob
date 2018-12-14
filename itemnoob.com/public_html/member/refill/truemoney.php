<?
    if($webinfo['webinfo_wallettype'] == '1' /*$_SESSION['member_status'] != '5'*/)
    {
            echo "<center><b>ขณะนี้ระบบ Truemoney ปิดปรับปรุง</b></center>";
    }
    else
    {
?>
    <div class="text-center">
        <h3></h3>
        <div class="alert alert-warning" role="alert"><strong>คำเตือน!</strong> การเติมเงินด้วยบัตรเงินสดทรูมันนี่ มีค่าธรรมเนียม 15% ตามตารางข้างล่าง <br/>
        ระบบจะตรวจสอบบัตรเงินสดอัตโนมัติ และเงินจะเข้าระบบทันทีหลังทำรายการสำเร็จ</div>
        <h4>กรอกข้อมูลบัตรทรูมันนี่</h4>
        <strong>จำเป็น!</strong> ลูกค้าจำเป็นต้องใส่ ชื่อ - นามสกุล ให้เรียบร้อยก่อนถึงจะสามารถเติมเงินด้วย Truemoney ได้ ให้ไปที่''ข้อมูลส่วนตัว''<br/>
        <strong>จำเป็น!</strong> หาก Username ไม่ใช่ของตัวเองให้รีเฟรชหน้าเว็บ 1 รอบ
    </div>
    <div class="text-center my-3">
        <div class="form-horizontal">
            <input type="hidden" name="ref1" id="ref1" class="form-control" value="<?php echo $member['username']; ?>"/>
            <input type="hidden" name="ref2" id="ref2" class="form-control" value="<?php echo $member['uid']; ?>"/>
            <input type="hidden" name="ref3" id="ref3" class="form-control" value="<?php echo $member['email']; ?>"/>
            <div class="form-group">
                <label class="col-sm-4 control-label">บัตรทรูมันนี่ : </label>
                <div class="col-sm-6">
                    <input type="text" name="tmn_password" id="tmn_password" class="form-control" required="" autocomplete="off" maxlength="14" onkeypress="return NumbersOnly(event);" placeholder="รหัสบัตรทรูมันนี่ 14 หลัก"/>
                </div>
            </div>
            <p><input type="submit" name="refilltruemoney_btn" id="refilltruemoney_btn" onclick="submit_tmnc()" class="btn btn-primary" value="เติมเงินด้วยบัตรทรูมันนี่"/></p>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">ราคาบัตรทรูมันนี่</th>
                        <th class="text-center">ยอดเงินที่ได้รับ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>50 Truemoney</td>
                        <td><?php echo number_format($tmtopup['credit'][50],2) ?> บาท</td>
                    </tr>
                    <tr>
                        <td>90 Truemoney</td>
                        <td><?php echo number_format($tmtopup['credit'][90],2) ?> บาท</td>
                    </tr>
                    <tr>
                        <td>150 Truemoney</td>
                        <td><?php echo number_format($tmtopup['credit'][150],2) ?> บาท</td>
                    </tr>
                    <tr>
                        <td>300 Truemoney</td>
                        <td><?php echo number_format($tmtopup['credit'][300],2) ?> บาท</td>
                    </tr>
                    <tr>
                        <td>500 Truemoney</td>
                        <td><?php echo number_format($tmtopup['credit'][500],2) ?> บาท</td>
                    </tr>
                    <tr>
                        <td>1000 Truemoney</td>
                        <td><?php echo number_format($tmtopup['credit'][1000],2) ?> บาท</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?
    }
?>