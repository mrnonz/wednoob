<?php if($_SESSION['member_uid'] == NULL): ?>
    <h4>สมัครสมาชิก</h4>
    <div class="row" style="padding: 10px 10px;">
        <div class="col-sm-6 col-sm-offset-3">
            <div id="rs_register"></div>
            <form name="register_frm" id="register_frm" method="post" action="javascript:void(0);">
                <div class="form-group col-md-12">
                    <label>Username : </label>
                    <input type="text" name="regis_username" id="regis_username" class="form-control" autocomplete="off" maxlength="20" onkeypress="return EnglishandNumbsers(event);"/>
                </div>
                <div class="form-group col-md-6">
                    <label>Password : </label>
                    <input type="password" name="regis_password" id="regis_password" class="form-control" autocomplete="off" maxlength="20"/>
                </div>
                <div class="form-group col-md-6">
                    <label>Re-Password : </label>
                    <input type="password" name="regis_repassword" id="regis_repassword" class="form-control" autocomplete="off" maxlength="20"/>
                </div>
                <div class="form-group col-md-12">
                    <label>ชื่อ - นามสกุล : </label>
                    <input type="text" name="regis_name" id="regis_name" class="form-control" autocomplete="off" maxlength="100"/>
                </div>
                <div class="form-group col-md-6">
                    <label>E-Mail : </label>
                    <input type="email" name="regis_email" id="regis_email" class="form-control" autocomplete="off" maxlength="100"/>
                </div>
                <div class="form-group col-md-6">
                    <label>เบอร์โทรศัพท์ : </label>
                    <input type="tel" name="regis_telephone" id="regis_telephone" class="form-control" autocomplete="off" maxlength="10" onkeypress="return NumbersOnly(event);"/>
                </div>
                <div class="col-md-12 text-center">
                    <!-- <div class="g-recaptcha col-md-12" id="regis_recaptcha" align="center" data-sitekey="6LeG2yITAAAAAKknoj91Ww0tWFlx6gyoFYBBIvQd"></div> -->
                    <button type="submit" name="register_btn" id="register_btn" class="btn btn-primary" onclick="Register()">สมัครสมาชิก</button>
                    <input type="reset" name="register_reset" class="btn btn-default" value="ยกเลิก" />
                </div>
            </form>
        </div>
    </div>
<?php
else : 
    header('location:'.$config['site_url']);
endif;
?>