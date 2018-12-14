<?
    if($_GET['action'] == 'delete')
    {
        if($_GET['id'])
        {
            $delete_event = $connect->query("DELETE FROM truewallet_user WHERE id = '".$_GET['id']."'");
            if($delete_event)
            {
                echo '<script>history.back();</script>';
            }
        }
        else
        {
            echo "ERROR";
        }
    }
?>

<h4>จัดการบัญชี TrueWallet</h4>
<hr/>
    <?
    @ob_start();
    include ('class.truewallet.php');

    $qury_account = $connect->query("SELECT * FROM truewallet_user WHERE email != 'krang01@jamebies.com'");
    $num_account = $qury_account->num_rows;
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="col-md-2">EMAIL</th>
                <th class="col-md-3">PASSWORD</th>
                <th class="col-md-3">BALANCE</th>
                <th class="text-center">EDIT</th>
            </tr>
        </thead>
    <?
    if($num_event == '0')
    {
        ?>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">ไม่พบ TrueWallet Account</td>
            </tr>
        </tbody>
        <?
    }
    else
    {   
        ?>
            <tbody>
                <?
                while($account_tw = $qury_account->fetch_assoc())
                {   
                    echo "<tr>";
                    @$user = $account_tw['email'];
                    @$pass = $account_tw['password'];
                    $wallet = new TrueWallet(($user),($pass),'email');
                    $token = json_decode($wallet->GetToken(),true)['data']['accessToken'];
                    $profile = json_decode($wallet->Profile($token));
                    ?>
                    <td><? echo $account_tw['id'];?></td>
                    <td><? echo $account_tw['email'];?></td>
                    <td>••••••••••••••</td>
                    <td><? echo number_format($profile->data->currentBalance,2); ?></td>
                    <td>
                        <center>
                            <a href="?page=twaccount&action=delete&id=<?=$account_tw['id'];?>" onclick="return confirm('คุณกำลังจะลบ Account คุณแน่ใจแล้วหรือไม่?')">ลบ
                            </a>
                        </center>
                    </td>
                    <?
                    echo "</tr>";
                }
                ?>
                
            </tbody>
        <?
    }
    ?>
    </table>
    <?
?>
<br/>
<?
    if(isset($_POST['tw_submit']))
    {
        if(empty($_POST['tw_email']))
        {
            exit("<meta http-equiv='refresh' content='1 ;url=./admin.php?page=twaccount'>");
        }
        if(empty($_POST['tw_password']))
        {
            exit("<meta http-equiv='refresh' content='1 ;url=./admin.php?page=twaccount'>");
        }
        else
        {
            $sql_insert_twU = 'INSERT INTO truewallet_user (email,password) VALUES ("'.$_POST['tw_email'].'","'.$_POST['tw_password'].'")';
            $query_insert_twU = $connect->query($sql_insert_twU);
            if($query_insert_twU)
            {
                echo "SUCCED";
                echo "<meta http-equiv='refresh' content='1 ;url=./admin.php?page=twaccount'>";
            }
            else
            {
                echo "FAILED";
            }
        }
    }
?>
<h4>เพิ่มบัญชี TrueWallet</h4>
<hr/>
    <form name="add_usertw" method="POST">
        <div class="form-group col-md-12">
            <label for="tw_email">EMAIL: </label>
            <input name="tw_email" type="text" id="tw_email" class="form-control" placeholder="EMAIL TrueWallet"/>
        </div>
        <div class="form-group col-md-12">
            <label for="tw_password">PASSWORD: </label>
            <input name="tw_password" type="password" id="tw_password" class="form-control" placeholder="PASSWORD TrueWallet"/>
        </div>
        <div class="col-md-12 text-center">
            <input type="submit" name="tw_submit" style="width:135px;" class="btn btn-success" value="เพิ่ม"/>
            <input type="reset" name="tw_reset" class="btn btn-default" value="ยกเลิก" />
        </div>
    </form>