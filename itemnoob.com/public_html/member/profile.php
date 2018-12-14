<?php if(isset($_SESSION['member_uid']) != NULL): ?>
    <h4>ข้อมูลส่วนตัว</h4>
    <div class="row" style="padding: 10px 10px;">
        <div class="col-sm-6 col-sm-offset-3">
            <div id="rs_editprofile"></div>
            <form name="editprofile_frm" id="editprofile_frm" method="post" action="javascript:void(0);">
                <div class="form-group col-md-12">
                    <label>ชื่อ - นามสกุล : </label>
                    <input type="text" name="editprofile_name" id="editprofile_name" class="form-control" value="<?php echo $member['name']; ?>"/>
                    <input type="hidden" name="editprofile_uid" id="editprofile_uid" value="<?php echo $member['uid']; ?>"/>
                </div>
                <div class="form-group col-md-12">
                    <div class="input-group">
                        <div class="input-group-addon">จำนวนเงินของคุณ : </div>
                        <input type="text" class="form-control disabled text-right" disabled="" value="<?php echo $member['credit']; ?>"/>
                        <div class="input-group-addon">บาท</div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label>ที่อยู่ : </label>
                    <input type="text" name="editprofile_address" id="editprofile_address" class="form-control" autocomplete="off" maxlength="200" value="<?php echo $member['address']; ?>"/>
                </div>
                <div class="form-group col-md-6">
                    <label>E-Mail : </label>
                    <input type="email" name="editprofile_email" id="editprofile_email" class="form-control" autocomplete="off" maxlength="100" value="<?php echo $member['email']; ?>"/>
                </div>
                <div class="form-group col-md-6">
                    <label>เบอร์โทรศัพท์ : </label>
                    <input type="tel" name="editprofile_telephone" id="editprofile_telephone" class="form-control" autocomplete="off" maxlength="10" value="<?php echo $member['telephone']; ?>" onkeypress="return NumbersOnly(event);"/>
                </div>
                <div class="form-group col-md-6">
                    <label>วัน/เวลา ที่สมัครสมาชิก : </label>
                    <input type="text" class="form-control disabled" disabled="" value="<?php echo $member['joined']; ?>"/>
                </div>
                <div class="form-group col-md-6">
                    <label>วัน/เวลา ที่เข้าสู่ระบบล่าสุด : </label>
                    <input type="text" class="form-control disabled" disabled="" value="<?php echo $member['lastlogin']; ?>"/>
                </div>
                <div class="col-md-12 text-center" style="margin-bottom: 10px;">
                    <button type="submit" name="editprofile_btn" id="editprofile_btn" class="btn btn-primary" onclick="EditProfile()">แก้ไขข้อมูลส่วนตัว</button>
                    <button type="submit" name="editpassword" id="editpassword" class="btn btn-default" onclick="showotherdetail('changepassword');">เปลี่ยนรหัสผ่าน <i class="fa fa-caret-down" aria-hidden="true"></i></button>
                </div>
            </form>
            <div id="changepassword" style="display:none;">
                <form name="changepassword_frm" id="changepassword_frm" method="post" action="javascript:void(0);">
                    <div class="form-group col-md-6">
                    <label>รหัสผ่านใหม่ : </label>
                        <input type="password" name="changepassword_new1" id="changepassword_new1" class="form-control" autocomplete="off" maxlength="20"/>
                    </div>
                    <div class="form-group col-md-6">
                    <label>รหัสผ่านใหม่อีกครั้ง : </label>
                        <input type="password" name="changepassword_new2" id="changepassword_new2" class="form-control" autocomplete="off" maxlength="20"/>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="submit" name="changepassword_btn" id="changepassword_btn" class="btn btn-primary" onclick="EditPassword()">บันทึกรหัสผ่าน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
else : 
    header('location:'.$config['site_url'].'/member.php?page=login&to='.$config['site_url'].'/member.php?page=profile');
endif;
?>