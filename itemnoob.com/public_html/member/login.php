<?php if(isset($_SESSION['member_uid']) == NULL): ?>
    <h4>เข้าสู่ระบบ</h4>
    <div class="row" style="padding: 10px 10px;">
        <div class="col-sm-6 col-sm-offset-3">
            <div id="rs_login"></div>
            <form name="login_frm" id="login_frm" method="post" action="javascript:void(0);">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
                        <input type="text" class="form-control" name="login_username" id="login_username" placeholder="Username" maxlength="20" autocomplete="off"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
                        <input type="password" class="form-control" name="login_password" id="login_password" placeholder="Password" maxlength="20" autocomplete="off"/>
                    </div>
                    <div class="text-right"><a href="member.php?page=forgot" title="ลืมรหัสผ่าน">ลืมรหัสผ่าน</a></div>
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" name="login_btn" id="login_btn" class="btn btn-primary" onclick="Login()">เข้าสู่ระบบ</button>
                    <button type="button" class="btn btn-default" onclick="location.href='member.php?page=register'">สมัครสมาชิก</button>
                </div>
            </form>
        </div>
    </div>
<script type="text/javascript">var login_success = "<?php echo $_GET['to']; ?>";</script>
<?php
    else :
        header('location:'.$config['site_url']);
    endif;
?>