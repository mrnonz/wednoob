<?php
    $webinfo = $connect->query('SELECT * FROM `webinfo` WHERE webinfo_id = "1"')->fetch_assoc();
    $howto = $connect->query('SELECT * FROM `howto` WHERE howto_id = "1"')->fetch_assoc();
?>
<h4>จัดการข้อมูลร้านค้า</h4><hr/>
<?php
    if(isset($_POST['webinfo_btn'])){
        $webinfo_title = $_POST['webinfo_title'];
        $webinfo_description = $_POST['webinfo_description'];
        $webinfo_keyword = $_POST['webinfo_keyword'];
        $webinfo_about = $_POST['webinfo_about'];
        $webinfo_phone = $_POST['webinfo_phone'];
        $webinfo_email = $_POST['webinfo_email'];
        $webinfo_facebook = $_POST['webinfo_facebook'];
        $webinfo_ratesteam = $_POST['webinfo_ratesteam'];
        $webinfo_line = $_POST['webinfo_line'];
        
        $sql_update_webinfo = 'UPDATE webinfo SET ';
        $sql_update_webinfo .= ' webinfo_title = "'.$webinfo_title.'" , ';
        $sql_update_webinfo .= ' webinfo_description = "'.$webinfo_description.'" , ';
        $sql_update_webinfo .= ' webinfo_keyword = "'.$webinfo_keyword.'" , ';
        $sql_update_webinfo .= ' webinfo_about = "'.$webinfo_about.'" , ';
        $sql_update_webinfo .= ' webinfo_phone = "'.$webinfo_phone.'" , ';
        $sql_update_webinfo .= ' webinfo_email = "'.$webinfo_email.'" , ';
        $sql_update_webinfo .= ' webinfo_facebook = "'.$webinfo_facebook.'" , ';
        $sql_update_webinfo .= ' webinfo_ratesteam = "'.$webinfo_ratesteam.'" , ';
        $sql_update_webinfo .= ' webinfo_line = "'.$webinfo_line.'" WHERE webinfo_id = "1"';
        $update_webinfo = $connect->query($sql_update_webinfo);
        echo '<script>history.back();</script>';
    }
    
    if(isset($_POST['howto_btn'])){
        $howto_youtube = $_POST['howto_youtube'];
        $howto_detail = htmlentities($_POST['howto_detail']);
        
        $sql_update_howto = 'UPDATE howto SET ';
        $sql_update_howto .= ' howto_youtube = "'.$howto_youtube.'" , ';
        $sql_update_howto .= ' howto_detail = "'.$howto_detail.'" WHERE howto_id = "1"';
        $update_howto= $connect->query($sql_update_howto);
        echo '<script>history.back();</script>';
    }
?>
<form name="webinfo_frm" method="post" action="">
    <div class="col-md-6 form-group">
        <label>Title Site :</label>
        <input type="text" name="webinfo_title" class="form-control" autocomplete="off" required="" value="<?php echo $webinfo['webinfo_title']; ?>" maxlength="255"/>
    </div>
    <div class="col-md-6 form-group">
        <label>Description Site :</label>
        <input type="text" name="webinfo_description" class="form-control" autocomplete="off" required="" value="<?php echo $webinfo['webinfo_description']; ?>" maxlength="255"/>
    </div>
    <div class="col-md-12 form-group">
        <label>Keywords Site :</label>
        <input type="text" name="webinfo_keyword" class="form-control" autocomplete="off" required="" value="<?php echo $webinfo['webinfo_keyword']; ?>" maxlength="255"/>
    </div>
    <div class="col-md-12 form-group">
        <label>เกี่ยวกับเรา :</label>
        <input type="text" name="webinfo_about" class="form-control" autocomplete="off" required="" value="<?php echo $webinfo['webinfo_about']; ?>"/>
    </div>
    <div class="col-md-2 form-group">
        <label>เบอร์โทร :</label>
        <input type="text" name="webinfo_phone" class="form-control" autocomplete="off" required="" maxlength="10" value="<?php echo $webinfo['webinfo_phone']; ?>"/>
    </div>
    <div class="col-md-3 form-group">
        <label>Email :</label>
        <input type="text" name="webinfo_email" class="form-control" autocomplete="off" required="" value="<?php echo $webinfo['webinfo_email']; ?>"/>
    </div>
    <div class="col-md-3 form-group">
        <label>Facebook URL :</label>
        <input type="text" name="webinfo_facebook" class="form-control" autocomplete="off" required="" value="<?php echo $webinfo['webinfo_facebook']; ?>"/>
    </div>
    <div class="col-md-2 form-group">
        <label>Line ID :</label>
        <input type="text" name="webinfo_line" class="form-control" autocomplete="off" required="" value="<?php echo $webinfo['webinfo_line']; ?>"/>
    </div>
    <div class="col-md-2 form-group">
        <label>Rate for STEAM URL :</label>
        <input type="text" name="webinfo_ratesteam" class="form-control" autocomplete="off" required="" value="<?php echo $webinfo['webinfo_ratesteam']; ?>"/>
    </div>
    <div class="col-md-12 text-center" style="margin-bottom: 20px;">
        <input type="submit" name="webinfo_btn" value="แก้ไขข้อมูล" class="btn btn-primary"/>
        <input type="reset" name="webinfo_btn_reset" value="รีเซ็ต!"  class="btn btn-default"/>
    </div>
</form>

<h4>จัดการวิธีการสั่งซื้อ</h4><hr/>
<form name="howto_frm" method="post" action="">
    <div class="col-md-12 form-group">
        <label>Youtube วีดีโอแนะนำวิธีการสั่งซื้อ :</label>
        <input type="text" name="howto_youtube" class="form-control" autocomplete="off" placeholder="เว้นว่างไว้หากไม่ต้องการแสดงวีดีโอแนะนำ" value="<?php echo $howto['howto_youtube']; ?>"/>
    </div>
    <div class="col-md-12 form-group">
        <label>รายละเอียดวิธีการสั่งซื้อ :</label>
        <textarea name="howto_detail"><?php echo $howto['howto_detail']; ?></textarea>
        <script>CKEDITOR.replace( 'howto_detail' );</script>
    </div>
    <div class="col-md-12 text-center" style="margin-bottom: 20px;">
        <input type="submit" name="howto_btn" value="แก้ไขข้อมูล" class="btn btn-primary"/>
        <input type="reset" name="howto_btn_reset" value="รีเซ็ต!"  class="btn btn-default"/>
    </div>
</form>



