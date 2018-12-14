<?php 
    if(isset($_SESSION['member_uid']) == NULL):

?>
<h4>ลืมรหัสผ่าน</h4>
    <div class="row" style="padding: 10px 10px;">
        <div class="col-sm-6 col-sm-offset-3">
        <div id="rs_forgot"></div>
        <form name="forgot_frm" id="forgot_frm" method="post" action="javascript:void(0);">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
                    <input type="text" class="form-control" name="forgot_username" id="forgot_username" placeholder="Username" maxlength="20" required="" autocomplete="off"/>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></div>
                    <input type="email" class="form-control" name="forgot_email" id="forgot_email" placeholder="Email" maxlength="100" required="" autocomplete="off"/>
                </div>
            </div>
            <div class="text-center">
                <input type="submit" name="forgot_btn" id="forgot_btn" class="btn btn-primary" onclick="Forgot()" value="ขอรหัสผ่านใหม่" />
                <button type="button" class="btn btn-default" onclick="location.href='member.php?page=login'">เข้าสู่ระบบ</button>
            </div>
        </form>
        </div>
    </div>
<?php
    else : 
        header('location:'.$config['site_url']);
    endif;
?>
