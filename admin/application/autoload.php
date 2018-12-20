<?php

/* 
 *  
 *  
 */

/*error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
*/

require_once '_config.php';

//Import PHPMailer
require_once __DIR__ .'/api/phpmailer-5.2/PHPMailerAutoload.php';

if($_GET['func'] == 'login'){
    if($_POST['login_username'] != NULL && $_POST['login_password'] != NULL){
        $login_username = $_POST['login_username'];
        $login_password = md5($_POST['login_password']);
        $sql_login = 'SELECT * FROM member WHERE username = "'.$login_username.'" and password = "'.$login_password.'"';
        $query_login = $connect->query($sql_login);
        $login = $query_login->fetch_assoc();
        if($login){
            $_SESSION["member_uid"] = $login["uid"];
            $_SESSION["member_username"] = $login_username;
            $_SESSION["member_status"] = $login["status"];
            session_write_close();
            $connect->query('UPDATE member SET lastlogin = "'.date('d-m-Y H:i:s').'" WHERE uid = "'.$login["uid"].'"');
            echo '1';
       } else {
            echo '0';
        }
    } else {
        header('location:'.$config['site_url']);
    }
} elseif($_GET['func'] == 'forgot'){
    if($_POST['forgot_username'] != NULL && $_POST['forgot_email'] != NULL){
        $forgot_username = $_POST['forgot_username'];
        $forgot_email = $_POST['forgot_email'];
        $sql_forgot = 'SELECT * FROM member WHERE username = "'.$forgot_username.'" and email = "'.$forgot_email.'"';
        $query_forgot = $connect->query($sql_forgot);
        $forgot = $query_forgot->fetch_assoc();
        if(!$forgot){
            echo '0';
        } else {
            // function random new password
            function randtext($range){
                $char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ123456789';
                $start = rand(1,(strlen($char)-$range));
                $shuffled = str_shuffle($char);
                return substr($shuffled,$start,$range);
            }
            $newpassword = randtext(10);
            $sql_updatepassword = 'UPDATE member SET  password = "'.md5($newpassword).'" WHERE username = "'.$forgot_username.'"';
            $query_updatepassword = $connect->query($sql_updatepassword);
            if($query_updatepassword){

               $body = '
                    คุณได้ทำการขอรหัสผ่านใหม่<br/>
                    เมื่อเวลา : '.date('d-m-Y H:i:s').'<br/>
                    โดย IP Address : '.$_SERVER['REMOTE_ADDR'].'<br/>
                    ขณะนี้ระบบได้ทำการสร้างรหัสผ่านใหม่เรียบร้อยแล้ว ดังต่อไปนี้<br/>
                    <br/>
                    <b>รหัสผ่านใหม่ของคุณคือ : </b>'.$newpassword.'<br/>
                    <br/>
                    *หลังจากเข้าสู่ระบบแล้ว กรุณาเข้าไปเปลี่ยนรหัสผ่านเป็นของคุณในหน้า "ข้อมูลส่วนตัว"<br/>
                    ';

$mail = new PHPMailer();
$mail->CharSet = "utf-8";
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = true;
$mail->Host = "mail.itemnoob.com"; // SMTP server
$mail->SMTPSecure = "ssl";
$mail->Port = "465"; // พอร์ท
$mail->Username = "no-reply@itemnoob.com"; // account SMTP
$mail->Password = "too7eNDt4"; // รหัสผ่าน SMTP
$mail->SetFrom("no-reply@itemnoob.com", "No-Reply");
$mail->AddReplyTo("admin@itemnoob.com", "Itemnoob.com");
$mail->Subject = "Reset your password (No Reply)";
$mail->MsgHTML($body);
$mail->AddAddress($forgot_email); // ผู้รับ
if($mail->Send()) {
    echo "1";
} else {
    echo "Mailer Error: " . $mail->ErrorInfo;
}

                /*$strTo = $forgot_email;
                $strSubject = "Reset your password (No Reply)";
                $strHeader = "Content-type: text/html; charset=UTF-8\r\n"; 
                $strHeader .= "From: no-reply@chillchill.in.th\r\n";
                $strMessage = "<br/>";
                $body = '
                    คุณได้ทำการขอรหัสผ่านใหม่<br/>
                    เมื่อเวลา : '.date('d-m-Y H:i:s').'<br/>
                    โดย IP Address : '.$_SERVER['REMOTE_ADDR'].'<br/>
                    ขณะนี้ระบบได้ทำการสร้างรหัสผ่านใหม่เรียบร้อยแล้ว ดังต่อไปนี้<br/>
                    <br/>
                    <b>รหัสผ่านใหม่ของคุณคือ : </b>'.$newpassword.'<br/>
                    <br/>
                    *หลังจากเข้าสู่ระบบแล้ว กรุณาเข้าไปเปลี่ยนรหัสผ่านเป็นของคุณในหน้า "ข้อมูลส่วนตัว"<br/>
                    ';
                $flgSend = @mail($strTo,$strSubject,$strMessage,$strHeader);*/
            }
        }
    } else {
        header('location:'.$config['site_url']);
    }
} elseif($_GET['func'] == 'register') {
    if($_POST['regis_username'] != NULL && $_POST['regis_email'] != NULL){
        $regis_username = $connect->real_escape_string($_POST['regis_username']);
        $regis_password = $_POST['regis_password'];
        $regis_repassword = $_POST['regis_repassword'];
        $regis_name = $_POST['regis_name'];
        $regis_email = $_POST['regis_email'];
        $regis_telephone = $_POST['regis_telephone'];
        $regis_joined = date("d-m-Y H:i:s");
        $regis_status = "0";
        $regis_credit = "0.00";
        $sql_chk_username = 'SELECT * FROM member WHERE username = "'.$regis_username.'"';
        $query_chk_username = $connect->query($sql_chk_username);
        $chk_username = $query_chk_username->fetch_assoc();
        if($chk_username){
            echo '2';
        } else {
            $md5password = md5($regis_password);
            $sql_register = 'INSERT INTO member (username,password,name,email,telephone,joined,status,credit) VALUES ("'.$regis_username.'","'.$md5password.'","'.$regis_name.'","'.$regis_email.'","'.$regis_telephone.'","'.$regis_joined.'","'.$regis_status.'","'.$regis_credit.'")';
            $query_register = $connect->query($sql_register);
            if($query_register){
                echo '1';
            } else {
                echo '0';
            }
        }
    } else {
        header('location:'.$config['site_url']);
    }
} elseif($_GET['func'] == 'editprofile') {
    if($_POST['editprofile_email'] != NULL && $_POST['editprofile_telephone'] != NULL){
        $editprofile_uid = $_POST['editprofile_uid'];
        $editprofile_name = $_POST['editprofile_name'];
        $editprofile_address = $_POST['editprofile_address'];
        $editprofile_email = $_POST['editprofile_email'];
        $editprofile_telephone = $_POST['editprofile_telephone'];
        $sql_editprofile = 'UPDATE member SET ';
        $sql_editprofile .= ' name = "'.$editprofile_name.'" , ';
        $sql_editprofile .= ' address = "'.$editprofile_address.'" , ';
        $sql_editprofile .= ' email = "'.$editprofile_email.'" , ';
        $sql_editprofile .= ' telephone = "'.$editprofile_telephone.'" ';
        $sql_editprofile .= ' WHERE uid = "'.$editprofile_uid.'"';
        $query_editprofile = $connect->query($sql_editprofile);
        if($query_editprofile){
            echo '1';
        } else {
            echo '0';
        }
    } else {
        header('location:'.$config['site_url']);
    }
} elseif($_GET['func'] == 'editpassowrd') {
    if($_POST['changepassword_new1'] != NULL && $_POST['changepassword_new2'] != NULL){
        $update_password = $connect->query('UPDATE member SET password = "'.md5($_POST['changepassword_new1']).'" WHERE uid = "'.$_SESSION['member_uid'].'"');
        if($update_password){
            echo '1';
            unset($_SESSION['member_uid']);
            unset($_SESSION['member_status']);
        } else {
            echo '0';
        }
    } else {
        header('location:'.$config['site_url']);
    }
} elseif($_GET['func'] == 'neworder') {
    if($_POST['product_id'] != NULL && $_POST['product_name'] != NULL){
        $sql_neworder_member = 'SELECT * FROM member WHERE uid = "'.$_SESSION['member_uid'].'"';
        $query_neworder_member = $connect->query($sql_neworder_member);
        $neworder_member = $query_neworder_member->fetch_assoc();
        
        $order_uid = $neworder_member['uid'];
        $order_detail = $_POST['order_detail'];
        $order_product_id = $_POST['product_id'];
        $order_product_name = $_POST['product_name'];
        $order_product_platform = $_POST['product_platform'];
        $order_product_type = $_POST['product_type'];
        $product_in_stock = $_POST['product_in_stock'];
        
        if(!empty($order_detail))
        {
            if($_POST['order_rate'] == "1")
            {
                $amount_order = $_POST['order_amount_want'];
                $order_total = $_POST['order_amount_want'] * $_POST['order_money_want'];
            }
            else
            {
                $amount_order = $_POST['order_amount_want'];
                $order_total = $_POST['order_amount_want'] / $_POST['order_rate'];
            }
            
            if($order_total <= '0')
            {
                echo '0';
            }
            else
            {
                $order_status = '0';
                $order_senddate = '0000-00-00 00:00:00';
                $order_ip = $_SERVER['REMOTE_ADDR'];
                $order_member_credit = number_format($neworder_member['credit'],2,'.','');
                if(!empty($order_total))
                {
                    if($amount_order > $product_in_stock)
                    {
                        echo '3';
                    }
                    else
                    {
                        if($order_member_credit >= $order_total)
                        {
                            $sql_neworder = "INSERT INTO `order` (`order_uid`, `order_detail`, `order_product_id`, `order_product_name`, `order_product_platform`, `order_product_type`, `order_total`, `order_status`, `order_senddate`, `order_ip`, `order_total_piece`) ";
                            $sql_neworder .= " VALUES ";
                            $sql_neworder .= ' ("'.$order_uid.'","'.$order_detail.'","'.$order_product_id.'","'.$order_product_name.'","'.$order_product_platform.'","'.$order_product_type.'","'.$order_total.'","'.$order_status.'","'.$order_senddate.'","'.$order_ip.'","'.$amount_order.'")';
                            $query_neworder = $connect->query($sql_neworder);
                            if($query_neworder){
                                $sql_update_creditorder = 'UPDATE member SET credit = credit-"'.$order_total.'" WHERE uid = "'.$order_uid.'"';
                                $query_update_creditorder = $connect->query($sql_update_creditorder);
                                if($query_update_creditorder){
                                    echo '1';
                                } else {
                                    echo '0';
                                }
                            } else {
                                echo '0';
                            }
                        } else {
                            echo '2';
                        }
                    } 
                }
                else
                {
                    echo '0';
                }
            }
        }
        else
        {
            echo '0';
        }
    } else {
        header('location:'.$config['site_url']);
    }
        
} elseif($_GET['func'] == 'refillbank') {
    if($_POST['refillbank_username'] && $_POST['refillbank_credit']){
        $refillbanking_uid = $_SESSION['member_uid'];
        $refillbanking_username = $_POST['refillbank_username'];
        $refillbanking_bank = $_POST['refillbank_bank'];
        $refillbanking_datetime = $_POST['refillbank_datetime'];
        $refillbanking_credit = $_POST['refillbank_credit'];
        $refillbanking_type = 'Banking';
        $refillbanking_status = '1';
        if(empty($refillbanking_credit) || empty($refillbanking_datetime)){
            echo '0';
        } else {
            # ตรวจสอบ 5 นาที
            if(strtotime($refillbanking_datetime)+330 > strtotime('now')){
                echo '2';
            } else {
                # ตรวจสอบรายการเงินเข้าจากธนาคาร
                $bankauto_sql = 'SELECT * FROM bank_payment WHERE time BETWEEN "'.strtotime($refillbanking_datetime).'" AND "'.(strtotime($refillbanking_datetime)+60).'" AND bank = "'.strtolower($refillbanking_bank).'" AND value = "'.str_replace(',', '', number_format($refillbanking_credit,2)).'"';
                $bankauto = $connect->query($bankauto_sql)->fetch_assoc();
                if($bankauto){
                    if($bankauto['status'] == '1'){
                        echo '4';
                    } else {
                        // แทนค่าชื่อธนาคาร
                        switch ($refillbanking_bank){
                            case 'kbank';
                                $bankname = 'กสิกรไทย';
                                break;
                            case 'ktb';
                                $bankname = 'กรุงไทย';
                                break;
                            case 'scb';
                                $bankname = 'ไทยพาณิชย์';
                                break;
                            case 'bbl';
                                $bankname = 'กรุงเทพ';
                                break;
                            case 'bay';
                                $bankname = 'กรุงศรีอยุธยา';
                                break;
                            case 'tmb';
                                $bankname = 'ทหารไทย';
                                break;
                            default :
                                $bankname = 'อื่น ๆ';
                        }

                        $sql_refillbanking = 'INSERT INTO `refill` (`refill_uid`,`refill_username`,`refill_type`,`refill_datetime`,`refill_credit`,`refill_detail`,`refill_status`) VALUES ';
                        $sql_refillbanking .= ' ("'.$refillbanking_uid.'","'.$refillbanking_username.'","'.$refillbanking_type.'","'.$refillbanking_datetime.'","'.str_replace(',', '', number_format($refillbanking_credit,2)).'","'.$bankname.'","'.$refillbanking_status.'")'; 
                        $refillbanking = $connect->query($sql_refillbanking);
                        if($refillbanking){
                            $select_refill = $connect->query('SELECT * FROM refill WHERE refill_uid = "'.$refillbanking_uid.'" ORDER BY refill_id DESC')->fetch_assoc();
                            $update_bankauto = $connect->query('UPDATE bank_payment SET status = "1" , tranferer = "'.$select_refill['refill_id'].'" WHERE id = "'.$bankauto['id'].'"');
                            $update_credit = $connect->query('UPDATE member SET credit = credit+"'.$refillbanking_credit.'" WHERE uid = "'.$refillbanking_uid.'"');
                            if($update_bankauto && $update_credit){
                                echo '1';
                            } else {
                                echo '0';
                            }
                        } else {
                            echo '0';
                        }
                    }
                } else {
                    echo '3';
                }
            }
        }
    }
} elseif($_GET['func'] == 'refilltruewallet') {
        if($_POST['refilltruewallet_username'] && $_POST['refilltruewallet_credit']){
            $refilltruewallet_uid = $_SESSION['member_uid'];
            $refilltruewallet_username = $_POST['refilltruewallet_username'];
            $refilltruewallet_number = $_POST['refilltruewallet_number'];
            $refilltruewallet_datetime = $_POST['refilltruewallet_datetime'];
            $refilltruewallet_credit = $_POST['refilltruewallet_credit'];
            $refilltruewallet_type = 'True Wallet';
            $refilltruewallet_status = '0';
            if(empty($refilltruewallet_credit) || empty($refilltruewallet_datetime) || empty($refilltruewallet_number)){
                echo '0';
            } else {
                $sql_refilltruewallet = 'INSERT INTO `refill` (`refill_uid`,`refill_username`,`refill_type`,`refill_datetime`,`refill_credit`,`refill_detail`,`refill_status`) VALUES ';
                $sql_refilltruewallet .= ' ("'.$refilltruewallet_uid.'","'.$refilltruewallet_username.'","'.$refilltruewallet_type.'","'.$refilltruewallet_datetime.'","'.$refilltruewallet_credit.'","'.$refilltruewallet_number.'","'.$refilltruewallet_status.'")'; 
                $query_refilltruewallet = $connect->query($sql_refilltruewallet);
                if($query_refilltruewallet){
                    echo '1';
                } else {
                    echo '0';
                }
            }
        }
} else {
    header('location:'.$config['site_url']);
}