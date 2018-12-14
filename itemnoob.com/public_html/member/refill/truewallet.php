<script type="text/javascript">
    function DontClick(){
        $("#refilltruewallet_btn").html('<i class="fa fa-spinner fa-spin fa-lg"></i> กำลังแจ้งโอนเงิน');
    }
</script>

<?
                            if($webinfo['webinfo_wallettype'] == '0' /*$_SESSION['member_status'] != '5'*/)
                            {
                                echo "<center><b>ขณะนี้ระบบ TrueWallet ปิดปรับปรุง</b></center>";
                            }
                            else
                            {
                                if(isset($_POST['refilltruewallet_btn']))
                                {
                                    include ('class.truewallet.php');
                                    @$user = "waltalkingjames@outlook.com";
                                    @$pass = "itemnoobstore90";

                                    if(empty($_POST['refilltruewallet_transaction']))
                                    {
                                        exit();
                                        header('location: member.php?page=refill&way=truewallet');
                                    }
                                    if(empty($_POST['frm_username']))
                                    {
                                        exit();
                                        header('location: member.php?page=refill&way=truewallet');
                                    }
                                    if(empty($_POST['frm_uid']))
                                    {
                                        exit();
                                        header('location: member.php?page=refill&way=truewallet');
                                    }
                                    if($_POST['refilltruewallet_transaction'] < 1)
                                    {
                                        header('location: member.php?page=refill&way=truewallet');
                                        exit();
                                    }

                                    $sql_logs = 'SELECT * FROM truewallet_logs WHERE transactionid = "'.$_POST['refilltruewallet_transaction'].'"';
                                    $query_logs = $connect->query($sql_logs);
                                    $num_logs = $query_logs->num_rows;
                                    if($num_logs != 0)
                                    {
                                        $fetch_logs = $query_logs->fetch_assoc();
                                        $query_check_tran = $connect->query('SELECT * FROM refill WHERE refill_tw_id = "'.$_POST['refilltruewallet_transaction'].'" LIMIT 1');
                                        $num_tran = $query_check_tran->num_rows;
                                        if($num_tran == 1)
                                        {
                                            echo '<div class="alert alert-danger"><strong>Error: </strong>มีการใช้ Transaction นี้แจ้งเข้ามาในระบบแล้ว</div>';
                                        }
                                        else
                                        {
                                            $sql_insert_transaction = 'INSERT INTO refill (refill_uid,refill_username,refill_type,refill_datetime,refill_credit,refill_detail,refill_tw_id,refill_status) VALUES ("'.$_POST['frm_uid'].'","'.$_POST['frm_username'].'","True Wallet","'.$fetch_logs['date_time'].'","'.$fetch_logs['amount'].'","'.$fetch_logs['phone_number'].'","'.$fetch_logs['transactionid'].'","1")';
                                            $query_insert_transaction = $connect->query($sql_insert_transaction);
                                            if($query_insert_transaction)
                                            {
                                                $sql_update_point = 'UPDATE member SET credit = credit+"'.$fetch_logs['amount'].'" WHERE uid = "'.$_SESSION['member_uid'].'"';
                                                $query_update_point = $connect->query($sql_update_point);
                                                if($query_update_point)
                                                {
                                                    echo '<div class="alert alert-success"><strong>SUCCEED: </strong>ยอดเงินได้เพิ่มเข้าบัญชี '.$_POST['frm_username'].' แล้ว กด F5 เพื่อเช็คจำนวนเงิน <b>'.$fetch_logs['amount'].'</b> บาท ระบบกำลังรีเฟรชหน้าเว็บของท่าน..</div>';
                                                    echo '<meta http-equiv="refresh" content="2;">';
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        //* DAY Setup
                                        $today_day =  date("d");
                                        $today_month = date("m");
                                        $today_year =  date("Y");
                                        $today_year_s = $today_year - 1;
                                        $today_use_check_s = $today_year_s."-".$today_month."-".$today_day;
                                        $today_year_e = $today_year + 1;
                                        $today_use_check_e = $today_year_e."-".$today_month."-".$today_day;
                                        //* END DAY Setup

                                        $wallet = new TrueWallet(($user),($pass),'email'); // LOGIN

                                        $sql_select_user = 'SELECT * FROM truewallet_token WHERE email = "'.$user.'"';
                                        $query_select_user = $connect->query($sql_select_user);
                                        $check_select_user = $query_select_user->num_rows;

                                        if($check_select_user == 0)
                                        {
                                            $gettoken = json_decode($wallet->GetToken(),true)['data']['accessToken'];
                                            $insert_select_user = $connect->query('INSERT INTO truewallet_token (email,token) VALUES ("'.$user.'","'.$gettoken.'")');
                                            $token = $gettoken;
                                        }
                                        else
                                        {
                                            $select_user = $query_select_user->fetch_assoc();
                                            $profile = json_decode($wallet->Profile($select_user['token']));

                                            if($profile->code == '10001')
                                            {
                                                $gettoken = json_decode($wallet->GetToken(),true)['data']['accessToken'];
                                                $token = $gettoken;

                                                $update_select_user = $connect->query('UPDATE truewallet_token SET token = "'.$token.'"');
                                            }
                                            elseif($profile->code == '20000')
                                            {
                                                $token = $select_user['token'];
                                            }
                                            else
                                            {
                                                $gettoken = json_decode($wallet->GetToken(),true)['data']['accessToken'];
                                                $token = $gettoken;

                                                $update_select_user = $connect->query('UPDATE truewallet_token SET token = "'.$token.'"');
                                            }
                                        }

                                        if(empty($_POST['refilltruewallet_transaction']))
                                        {
                                            exit();
                                            header('location: member.php?page=refill&way=truewallet');
                                        }
                                        else
                                        {
                                            if($getTran = json_decode($wallet->getTran($token,$today_use_check_s,$today_use_check_e)))
                                            {
                                                $Tran = $getTran->data->activities;
                                                //echo "<pre>";
                                                //print_r($Tran);
                                                //echo "</pre>";
                                            }

                                            if($Tran[0]&&$Checkreport = json_decode($wallet->CheckTran($token,$Tran[0]->reportID)))
                                            {
                                                $report = $Checkreport->data;
                                            }

                                            $tran_type_want = "creditor";
                                            $fti_u = "0";
                                            foreach($Tran as $transaction)
                                            {
                                                $tran_type = $transaction->text3En;
                                                if($tran_type == $tran_type_want)
                                                {
                                                    $last_report = $transaction->reportID;

                                                    $sql_reportid = 'SELECT * FROM truewallet_logs WHERE reportid = "'.$last_report.'"';
                                                    $query_reportid = $connect->query($sql_reportid);
                                                    $num_reportid = $query_reportid->num_rows;

                                                    if($full_last_report = json_decode($wallet->CheckTran($token,$last_report)))
                                                    {
                                                        $flr = $full_last_report->data;
                                                        $fti = $flr->section4->column2->cell1->value;
                                                        $ftam = $flr->amount;
                                                        $ftm = $flr->personalMessage->value;
                                                        $ftphone = $flr->ref1;
                                                        $ftdate = $flr->section4->column1->cell1->value;
                                                    }

                                                    if($num_reportid == 0)
                                                    {
                                                        $sql_insert = 'INSERT INTO truewallet_logs (reportid,transactionid,phone_number,date_time,amount,messages) VALUES ("'.$last_report.'","'.$fti.'","'.$ftphone.'","'.$ftdate.'","'.$ftam.'","'.$ftm.'")';
                                                        $query_insert = $connect->query($sql_insert);
                                                    }

                                                    if($fti == $_POST['refilltruewallet_transaction'])
                                                    {
                                                        if($ftphone == $_POST['refilltruewallet_number'])
                                                        {
                                                            $fti_u = $fti;
                                                            $ftam_u = $ftam;
                                                            $ftm_u = $ftm;
                                                            $ftphone_u = $ftphone;
                                                            $ftdate_u = $ftdate;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }

                                            if($fti_u == $_POST['refilltruewallet_transaction'])
                                            {
                                                /*
                                                echo "หมายเลขอ้างอิง: ".$fti_u."<br/>";
                                                echo "จำนวนเงิน: ".$ftam_u."<br/>";
                                                echo "ข้อความ: ".$ftm_u."<br/>";
                                                echo $ftphone_u."<br/>";
                                                echo $ftdate_u."<br/>";
                                                */
                                                $query_check_tran = $connect->query('SELECT * FROM refill WHERE refill_tw_id = "'.$fti_u.'" LIMIT 1');
                                                $num_tran = $query_check_tran->num_rows;
                                                if($num_tran == 1)
                                                {
                                                    exit('<div class="alert alert-danger"><strong>Error: </strong>มีการใช้ Transaction นี้แจ้งเข้ามาในระบบแล้ว</div>');
                                                }
                                                else
                                                {
                                                    $sql_insert_transaction = 'INSERT INTO refill (refill_uid,refill_username,refill_type,refill_datetime,refill_credit,refill_detail,refill_tw_id,refill_status) VALUES ("'.$_POST['frm_uid'].'","'.$_POST['frm_username'].'","True Wallet","'.$ftdate_u.'","'.$ftam_u.'","'.$ftphone_u.'","'.$fti_u.'","1")';
                                                    $query_insert_transaction = $connect->query($sql_insert_transaction);
                                                    if($query_insert_transaction)
                                                    {
                                                        $sql_update_point = 'UPDATE member SET credit = credit+"'.$ftam_u.'" WHERE uid = "'.$_POST['frm_uid'].'"';
                                                        $query_update_point = $connect->query($sql_update_point);
                                                        if($query_update_point)
                                                        {
                                                            echo '<div class="alert alert-success"><strong>SUCCEED: </strong>ยอดเงินได้เพิ่มเข้าบัญชี '.$_POST['frm_username'].' แล้ว กด F5 เพื่อเช็คจำนวนเงิน <b>'.$ftam_u.'</b> บาท ระบบกำลังรีเฟรชหน้าเว็บของท่าน..</div>';
                                                            echo '<meta http-equiv="refresh" content="2;">';
                                                        }
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                exit('<div class="alert alert-danger"><strong>Error: </strong>ไม่พบ Transaction นี้ หรือ ขณะระบบขัดข้อง</div>');
                                            }

                                        }
                                    }
                                }
                                ?>
                                    <span style="font-size: 20px;">ก่อนแจ้งเติมเงิน โอนเงินมาที่  True Wallet : <u><strong><?php echo $webinfo['webinfo_truewallet']; ?></strong></u></span>
                                    <div class="col-sm-6 col-sm-offset-3">
                                        <form name="refilltruewallet_frm" id="refilltruewallet_frm" method="POST">
                                            <input type="hidden" name="type_topup" id="type_topup" value="1"/>
                                            <input type="hidden" name="frm_username" id="frm_username" value="<?echo $_SESSION['member_username'];?>"/>
                                            <input type="hidden" name="frm_uid" id="frm_uid" value="<?echo $_SESSION['member_uid'];?>"/>
                                            <div class="form-group col-md-12">
                                                <label class="control-label">เบอร์บัญชีทรูวอตเล็ตของคุณที่ใช้โอน: </label>
                                                <input type="text" name="refilltruewallet_number" id="refilltruewallet_number" class="form-control" required="" autocomplete="off" onkeypress="return NumbersOnly(event);" maxlength="10"/>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="control-label">หมายเลขอ้างอิง (ได้หลังจากโอนเงินต้องแจ้งภายใน 5 นาที): </label>
                                                    <input type="text" name="refilltruewallet_transaction" id="refilltruewallet_transaction" class="form-control" required="" autocomplete="off" onkeypress="return NumbersandDot(event);" maxlength="20"/>
                                            </div>
                                            <!-- <div class="form-group col-md-12">
                                                <label class="control-label">Captcha : </label>
                                                <div class="g-recaptcha" id="refill_recaptcha" data-sitekey="6LeG2yITAAAAAKknoj91Ww0tWFlx6gyoFYBBIvQd"></div>
                                            </div> -->
                                            <img src="images/truewallet.jpg" width="100%" style="border-radius: 8px 8px 8px 8px;" alt="วิธีการเติมเงินด้วย TrueWallet"/>
                                            <div class="col-md-12 text-center">
                                                <button type="submit" name="refilltruewallet_btn" style="margin-top:5px;" id="refilltruewallet_btn" class="btn btn-primary" onclick="DontClick();">แจ้งเติมเงิน</button>
                                            </div>
                                        </form>

                                    </div>
                                <?
                            }
                        ?>