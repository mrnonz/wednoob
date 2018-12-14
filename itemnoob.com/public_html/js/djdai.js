/* 
 * This site Design with Bootstrap and Develop source code by. DJdai Yodsapon
 * Can contact DJdai at y.wongchuen@gmail.com or +66800257281 (Thailand Number)
 */

$('#datetimepicker').datetimepicker({
    dayOfWeekStart : 1,
    format:"Y/m/d H:i",
    lang: 'th',
    startDate: 'date("now")'
});
$('#datetimepicker').datetimepicker({step:1});

// ตรวจสอบค่าในช่อง input กำหนดให้ใส่ได้เฉพาะภาษาอังกฤษและตัวเลขเท่านั้น โดนใส่ onkeypress="return EnglishandNumbsers(event);" ใน input
function EnglishandNumbsers(e){
    var keynum;
    var keychar;
    var numcheck;
    if(window.event) {// IE
        keynum = e.keyCode;
    } else if(e.which) {// Netscape/Firefox/Opera
        keynum = e.which;
    }
    if(keynum == 13 || keynum == 8 || typeof(keynum) == "undefined"){
        return true;
    }
    keychar= String.fromCharCode(keynum);
    numcheck = /^[0-9A-Za-z-_]$/;  // อยากจะพิมพ์อะไรได้มั่ง เติม regular expression ได้ที่ line นี้เลยคับ
    return numcheck.test(keychar);
}


// ตรวจสอบค่าในช่อง input กำหนดให้ใส่ได้เฉพาะตัวเลขเท่านั้น โดนใส่ onkeypress="return NumbersOnly(event);" ใน input
function NumbersOnly(e){
    var keynum;
    var keychar;
    var numcheck;
    if(window.event) {// IE
        keynum = e.keyCode;
    } else if(e.which) {// Netscape/Firefox/Opera
        keynum = e.which;
    }
    if(keynum == 13 || keynum == 8 || typeof(keynum) == "undefined"){
        return true;
    }
    keychar= String.fromCharCode(keynum);
    numcheck = /^[0-9]$/;  // อยากจะพิมพ์อะไรได้มั่ง เติม regular expression ได้ที่ line นี้เลยคับ
    return numcheck.test(keychar);
}

// ตรวจสอบค่าในช่อง input กำหนดให้ใส่ได้เฉพาะตัวเลขและจุดเท่านั้น โดนใส่ onkeypress="return NumbersandDot(event);" ใน input
function NumbersandDot(e){
    var keynum;
    var keychar;
    var numcheck;
    if(window.event) {// IE
        keynum = e.keyCode;
    } else if(e.which) {// Netscape/Firefox/Opera
        keynum = e.which;
    }
    if(keynum == 13 || keynum == 8 || typeof(keynum) == "undefined"){
        return true;
    }
    keychar= String.fromCharCode(keynum);
    numcheck = /^[0-9.]$/;  // อยากจะพิมพ์อะไรได้มั่ง เติม regular expression ได้ที่ line นี้เลยคับ
    return numcheck.test(keychar);
}

function send_steamurl() {
    $.ajax({
        type: "POST",
        url: "application/steam_api/get_steam_url.php",
        data: $("#browse_steamurl_frm").serialize(),
        beforeSend: function() {
            document.getElementById("browse_steamurl_btn").disabled = true;
            document.getElementById("steam_url").disabled = true;
            $("#browse_steamurl_btn").html('<i class="glyphicon glyphicon-refresh fa-spin fa-lg"></i>');
        },
        success: function(data) {
            document.getElementById("browse_steamurl_btn").disabled = false;
            document.getElementById("steam_url").disabled = false;
            $("#browse_steamurl_btn").html('ค้นหา');
            $("#browse_steamurl_rs").fadeOut(400, function() {$(this).fadeIn(400).html(data);});
        }
    })
}

function showotherdetail(id){
    if (document.getElementById){
        obj = document.getElementById(id);
        if (obj.style.display == "none"){
            obj.style.display = "";
        } else {
            obj.style.display = "none";
        }
    }
}

function LoadingPage() {
    var div = document.createElement('div');
        div.innerHTML = "<div class='loading' id='loading'><i class='fa fa-spinner fa-spin fa-4x'></i><br/>Loading...</div>";
        div.style.cssText = 'position:fixed; top:0px; left:0px; background:#000000; color:#ffffff; width:100%; height:100%; opacity:0.75; filter:alpha(opacity=75); -moz-opacity:0.75; z-index:1050; text-align:center;';
    document.body.appendChild(div);
    return true;
}

function Login() {
    var login_username = $("#login_username").val();
    var login_password = $("#login_password").val();
    if(login_username == ''){
        $("#rs_login").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง Username</div>');
        });
        $("#login_username").focus();
        return false;
    } else if(login_password == '') {
        $("#rs_login").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง Password</div>');
        });
        $("#login_password").focus();
        return false;
    }
    $.ajax({
        type: "POST",
        url: "application/autoload.php?func=login",
        data: $("#login_frm").serialize(),
        beforeSend: function() {
            document.getElementById("login_btn").disabled = true;
            document.getElementById("login_username").disabled = true;
            document.getElementById("login_password").disabled = true;
            $("#login_btn").html('<i class="fa fa-spinner fa-spin fa-lg"></i> เข้าสู่ระบบ');
        },
        success: function(data) {
            if(data==1){
                $("#rs_login").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-success"><strong>Success: </strong>เข้าสู่ระบบเรียบร้อยแล้ว | <i class="fa fa-spinner fa-spin fa-lg"></i> Loading Page...</div>');
                });
                setTimeout(function(){location.href = login_success},3000);
            } else {
                $("#rs_login").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>Username หรือ Password ไม่ถูกต้อง</div>');
                });
            }
            document.getElementById("login_btn").disabled = false;
            document.getElementById("login_username").disabled = false;
            document.getElementById("login_password").disabled = false;
            $("#login_btn").html('เข้าสู่ระบบ');
        }
    })
}

function Forgot() {
    var forgot_username = $("#forgot_username").val();
    var forgot_email = $("#forgot_email").val();
    if(forgot_username == ''){
        $("#rs_forgot").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง Username</div>');
        });
        $("#forgot_username").focus();
        return false;
    } else if(forgot_email == '') {
        $("#rs_forgot").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง Email</div>');
        });
        $("#forgot_password").focus();
        return false;
    }
    $.ajax({
        type: "POST",
        url: "application/autoload.php?func=forgot",
        data: $("#forgot_frm").serialize(),
        beforeSend: function() {
            document.getElementById("forgot_btn").disabled = true;
            document.getElementById("forgot_username").disabled = true;
            document.getElementById("forgot_email").disabled = true;
            $("#forgot_btn").html('<i class="fa fa-spinner fa-spin fa-lg"></i> กำลังสร้างรหัสผ่านใหม่');
        },
        success: function(data) {
            if(data==1){
                $("#rs_forgot").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-success"><strong>Success: </strong>รหัสผ่านใหม่ได้ถูกส่งไปที่อีเมลของคุณเรียบร้อยแล้ว');
                });
            } else {
                $("#rs_forgot").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>Username หรือ Email ไม่ถูกต้อง</div>');
                });
            }
            document.getElementById("forgot_btn").disabled = false;
            document.getElementById("forgot_username").disabled = false;
            document.getElementById("forgot_email").disabled = false;
            $("#forgot_btn").html('ขอรหัสผ่านใหม่');
        }
    })
}

function Register() {
    var regis_username = $("#regis_username").val();
    var regis_password = $("#regis_password").val();
    var regis_repassword = $("#regis_repassword").val();
    var regis_name = $("#regis_name").val();
    var regis_email = $("#regis_email").val();
    var regis_telephone = $("#regis_telephone").val();
    if(regis_username == ''){
        $("#rs_register").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง Username</div>');
        });
        $("#regis_username").focus();
        return false;
    } else if(regis_password == '') {
        $("#rs_register").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง Password</div>');
        });
        $("#regis_password").focus();
        return false;
    } else if(regis_repassword == '') {
        $("#rs_register").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง Re-Password</div>');
        });
        $("#regis_repassword").focus();
        return false;
    } else if(regis_name == '') {
        $("#rs_register").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง ชื่อ - นามสกุล</div>');
        });
        $("#regis_name").focus();
        return false;
    } else if(regis_email == '') {
        $("#rs_register").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง E-Mail</div>');
        });
        $("#regis_email").focus();
        return false;
    } else if(regis_telephone == '') {
        $("#rs_register").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง เบอร์โทรศัพท์</div>');
        });
        $("#regis_telephone").focus();
        return false;
    } else if(regis_password != regis_repassword) {
        $("#rs_register").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>Password และ Re-Password ไม่ตรงกัน</div>');
        });
        $("#regis_repassword").focus();
        return false;
    } /*else if(grecaptcha.getResponse() == 0) {
        $("#rs_register").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>reCAPTCHA ไม่ถูกต้อง</div>');
        });
        return false;
    }*/
    $.ajax({
        type: "POST",
        url: "application/autoload.php?func=register",
        data: $("#register_frm").serialize(),
        beforeSend: function() {
            document.getElementById("register_btn").disabled = true;
            document.getElementById("regis_username").disabled = true;
            document.getElementById("regis_password").disabled = true;
            document.getElementById("regis_repassword").disabled = true;
            document.getElementById("regis_name").disabled = true;
            document.getElementById("regis_email").disabled = true;
            document.getElementById("regis_telephone").disabled = true;
            $("#register_btn").html('<i class="fa fa-spinner fa-spin fa-lg"></i> สมัครสมาชิก');
        },
        success: function(data) {
            if(data==0){
                $("#rs_register").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>ระบบไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</div>');
                });
            } else if(data==1){
                $("#rs_register").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-success"><strong>Success: </strong>สมัครสมาชิกเรียบร้อยแล้ว <a href="member.php?page=login">เข้าสู่ระบบ</a></div>');
                });
            } else if(data==2){
                $("#rs_register").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>Username นี้มีผู้ใช้งานแล้ว</div>');
                });
            }
            document.getElementById("register_btn").disabled = false;
            document.getElementById("regis_username").disabled = false;
            document.getElementById("regis_password").disabled = false;
            document.getElementById("regis_repassword").disabled = false;
            document.getElementById("regis_name").disabled = true;
            document.getElementById("regis_email").disabled = false;
            document.getElementById("regis_telephone").disabled = false;
            $("#register_btn").html('สมัครสมาชิก');
        }
    })
}

function EditProfile() {
    var checkconfirm = confirm('ยืนยันการแก้ไขข้อมูลส่วนตัว?');
    if(checkconfirm == true){
        var editprofile_name = $("#editprofile_name").val();
        var editprofile_email = $("#editprofile_email").val();
        var editprofile_telephone = $("#editprofile_telephone").val();
        if(editprofile_name == ''){
            $("#rs_editprofile").fadeOut(400, function() {
                $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง ชื่อ - นามสกุล</div>');
            });
            $("#editprofile_name").focus();
            return false;
        } else if(editprofile_email == ''){
            $("#rs_editprofile").fadeOut(400, function() {
                $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง E-Mail</div>');
            });
            $("#editprofile_email").focus();
            return false;
        } else if(editprofile_telephone == ''){
            $("#rs_editprofile").fadeOut(400, function() {
                $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณากรอกข้อมูลในช่อง เบอร์โทรศัพท์</div>');
            });
            $("#editprofile_telephone").focus();
            return false;
        } 
        $.ajax({
            type: "POST",
            url: "application/autoload.php?func=editprofile",
            data: $('#editprofile_frm').serialize(),
            beforeSend: function() {
                document.getElementById("editprofile_btn").disabled = true;
                $("#editprofile_btn").html('<i class="fa fa-spinner fa-spin fa-lg"></i> แก้ไขข้อมูลส่วนตัว');
            },
            success: function(data) {
                if(data==0){
                    $("#rs_editprofile").fadeOut(400, function() {
                        $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>ไม่สามารถแก้ไขข้อมูลส่วนตัวได้ กรุณาลองใหม่อีกครั้ง</div>');
                    });
                } else if (data==1){
                    $("#rs_editprofile").fadeOut(400, function() {
                        $(this).fadeIn(400).html('<div class="alert alert-success"><strong>Success: </strong>แก้ไขข้อมูลส่วนตัวเรียบร้อยแล้ว</div>');
                    });
                }
                document.getElementById("editprofile_btn").disabled = false;
                $("#editprofile_btn").html('แก้ไขข้อมูลส่วนตัว');
            }
        })
    } else {
        return false
    }
}

function EditPassword() {
    var checkconfirm = confirm('ยืนยันการเปลี่ยนรหัสผ่าน?');
    if(checkconfirm == true){
        var editpassowrd_1 = $("#changepassword_new1").val();
        var editpassowrd_2 = $("#changepassword_new2").val();
        if(editpassowrd_1 != editpassowrd_2){
            $("#rs_editprofile").fadeOut(400, function() {
                $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>รหัสผ่านใหม่ไม่ตรงกัน</div>');
            });
            $("#changepassword_new1").focus();
            return false;
        } 
        $.ajax({
            type: "POST",
            url: "application/autoload.php?func=editpassowrd",
            data: $('#changepassword_frm').serialize(),
            beforeSend: function() {
                document.getElementById("changepassword_btn").disabled = true;
                $("#changepassword_btn").html('<i class="fa fa-spinner fa-spin fa-lg"></i> บันทึกรหัสผ่าน');
            },
            success: function(data) {
                if(data==0){
                    $("#rs_editprofile").fadeOut(400, function() {
                        $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</div>');
                    });
                } else if (data==1){
                    $("#rs_editprofile").fadeOut(400, function() {
                        $(this).fadeIn(400).html('<div class="alert alert-success"><strong>Success: </strong>บันทึกรหัสผ่านใหม่เรียบร้อยแล้ว</div>');
                    });
                    setTimeout(function(){location.href = 'member.php?page=login'},3000);
                }
                document.getElementById("changepassword_btn").disabled = false;
                $("#changepassword_btn").html('บันทึกรหัสผ่าน');
            }
        })
    } else {
        return false
    }
}

function NewOrder() {
    var checkconfirm = confirm('ยืนยันการชำระเงิน? (หลังยืนยันการชำระเงินระบบจะตัดยอดเงินของคุณโดยอัตโนมัติ)');
    if(checkconfirm == true){
        $.ajax({
            type: "POST",
            url: "application/autoload.php?func=neworder",
            data: $('#neworder_frm').serialize(),
            beforeSend: function() {
                document.getElementById("neworder_btn").disabled = true;
                $("#neworder_btn").html('<i class="fa fa-spinner fa-spin fa-lg"></i> ชำระเงินทันที');
            },
            success: function(data) {
                if(data==0){
                    $("#rs_neworder").fadeOut(400, function() {
                        $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>ไม่สามารถชำระเงินได้ กรุณาลองใหม่อีกครั้ง</div>');
                    });
                } else if(data==1){
                    $("#rs_neworder").fadeOut(400, function() {
                        $(this).fadeIn(400).html('<div class="alert alert-success"><strong>Success: </strong>ชำระเงินเรียบร้อยแล้ว ไปหน้า<a href="member.php?page=historyorder">ประวัติการสั่งซื้อ</a> (รับสินค้า)</div>');
                        setTimeout(function(){location.href = 'https://www.itemnoob.com/headerlocation.php'},1000);
                    });
                } else if(data==2){
                    $("#rs_neworder").fadeOut(400, function() {
                        $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>จำนวนเงินคงเหลือของคุณไม่พอสำหรับการชำระค่าสินค้า กรุณาเติมเงิน</div>');
                    });
                } else if(data==3){
                    $("#rs_neworder").fadeOut(400, function() {
                        $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>ขออภัยค่ะ ขณะนี้สินค้าหมด</div>');
                    });
                }
                document.getElementById("neworder_btn").disabled = false;
                $("#neworder_btn").html('ชำระเงินทันที');
            }
        })
    } else {
        return false;
    }
}

function RefillBank() {
    /* if(grecaptcha.getResponse() == 0) {
        $("#rs_refill").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณายืนยัน Captcha เพื่อป้องกันโปรแกรมอัตโนมัติ</div>');
        });
        return false;
    } */
    $.ajax({
        type: "POST",
        url: "application/autoload.php?func=refillbank",
        data: $('#refillbank_frm').serialize(),
        beforeSend: function() {
            document.getElementById("refillbank_btn").disabled = true;
            $("#refillbank_btn").html('<i class="fa fa-spinner fa-spin fa-lg"></i> แจ้งเติมเงิน');
        },
        success: function(data) {
            if(data==0){
                $("#rs_refill").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</div>');
                });
            } else if (data==1){
                $("#rs_refill").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-success"><strong>Success: </strong>แจ้งเติมเงินเรียบร้อยแล้ว | <i class="fa fa-spinner fa-spin fa-lg"></i> Loading Page...</div>');
                });
                setTimeout(function(){location.href = 'member.php?page=historyrefill'},3000);
            } else if (data==2){
                $("#rs_refill").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-warning"><strong>Warning: </strong>กรุณาอย่างน้อย 5 นาที แล้วแจ้งโอนเงินอีกครั้บ</div>');
                });
            } else if (data==3){
                $("#rs_refill").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>ไม่พบรายการเงินดังกล่าวในระบบ</div>');
                });
            } else if (data==4){
                $("#rs_refill").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>รายการนี้ได้รับการอนุมัติแล้วไม่สามารถแจ้งโอนซ้ำได้</div>');
                });
            }
            document.getElementById("refillbank_btn").disabled = false;
            $("#refillbank_btn").html('แจ้งเติมเงิน');
        }
    })
}

function RefillTruewallet() {
    /* if(grecaptcha.getResponse() == 0) {
        $("#rs_refill").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณายืนยัน Captcha เพื่อป้องกันโปรแกรมอัตโนมัติ</div>');
        });
        return false;
    } */
    $.ajax({
        type: "POST",
        url: "application/autoload.php?func=refilltruewallet",
        data: $('#refilltruewallet_frm').serialize(),
        beforeSend: function() {
            document.getElementById("refilltruewallet_btn").disabled = true;
            $("#refilltruewallet_btn").html('<i class="fa fa-spinner fa-spin fa-lg"></i> กำลังแจ้งโอนเงิน');
        },
        success: function(data) {
            if(data==0){
                $("#rs_refill").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</div>');
                });
            } else if (data==1){
                $("#rs_refill").fadeOut(400, function() {
                    $(this).fadeIn(400).html('<div class="alert alert-success"><strong>Success: </strong>แจ้งเติมเงินเรียบร้อยแล้ว | <i class="fa fa-spinner fa-spin fa-lg"></i> Loading Page...</div>');
                });
                setTimeout(function(){location.href = 'member.php?page=historyrefill'},3000);
            }
            document.getElementById("refilltruewallet_btn").disabled = false;
            $("#refilltruewallet_btn").html('แจ้งเติมเงิน');
        }
    })
}