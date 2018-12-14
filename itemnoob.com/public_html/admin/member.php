<?php
// Function Delete
if($_GET['action'] == 'delete'){
    $sql_delete_member = 'DELETE FROM member WHERE uid = "'.$_GET['uid'].'"';
    $query_delete_member = $connect->query($sql_delete_member);
    if($query_delete_member){
        echo '<script>history.back();</script>';
    }
}
?>
<h4>รายชื่อสมาชิก</h4><hr/>
<div class="col-lg-12">
    <form method="GET" class="form-horizontal" role="form" action="">
        <div class="input-group">
            <input type="hidden" class="form-control" name="page" value="member"/>
            <input type="text" class="form-control" name="keyword" value="<?php echo $_GET["keyword"];?>" placeholder="ค้นหาสินค้าโดยใช้ Name" autocomplete="off" required=""/>
            <span class="input-group-btn">
                <input type="submit" class="btn btn-primary" value="ค้นหา"/>
            </span>
        </div>
    </form>
</div>
<?php
    if($_GET['keyword'] != NULL){
        $sql_member = "SELECT * FROM member WHERE (name LIKE '%".$_GET["keyword"]."%' || username LIKE '%".$_GET["keyword"]."%' ) || uid LIKE '%".$_GET["keyword"]."%'";
    } else {
        $sql_member = "SELECT * FROM member ";
    }
    // Select DB - member
    $query_member = $connect->query($sql_member);
    $numrows_member = $query_member->num_rows;
    $Per_Page = 30;
    $Page = $_GET["p"];
    if(!$_GET["p"]){
        $Page=1;
    }
    $Prev_Page = $Page-1;
    $Next_Page = $Page+1;
    $Page_Start = (($Per_Page*$Page)-$Per_Page);
    if($numrows_member<=$Per_Page){
        $Num_Pages =1;
    } elseif(($numrows_member % $Per_Page)==0){
        $Num_Pages =($numrows_member/$Per_Page) ;
    } else {
        $Num_Pages =($numrows_member/$Per_Page)+1;
        $Num_Pages = (int)$Num_Pages;
    }
    $sql_member .= " ORDER BY uid ASC LIMIT $Page_Start , $Per_Page";
    $query_member = $connect->query($sql_member);
?>
<div class="col-lg-12 table-responsive" style="margin-top: 10px;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">UID</th>
                <th class="col-md-2">Username</th>
                <th class="col-md-3">Name</th>
                <th class="col-md-3">Email</th>
                <th class="col-md-2">เบอร์โทร</th>
                <th class="text-right col-md-2">จำนวนเงินคงเหลือ</th>
                <th class="text-center">จัดการ</th>
            </tr>
        </thead>
        <?php if($numrows_member == '0'): ?>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">ไม่พบสมาชิก</td>
            </tr>
        </tbody>
        <?php else: ?>
        <tbody>
        <?php while($member = $query_member->fetch_assoc()): ?>
            <tr>
                <td class="text-center"><?php echo $member['uid']; ?></td>
                <td><?php echo $member['username']; ?></td>
                <td><?php echo $member['name']; ?></td>
                <td><?php echo $member['email']; ?></td>
                <td><?php echo $member['telephone']; ?></td>
                <td class="text-right"><?php echo $member['credit']; ?> บาท</td>
                <td class="text-center">
                    <a href="?page=member&action=delete&p=<?=$Page;?>&uid=<?=$member['uid'];?>" title="ลบทิ้ง" onclick="return confirm('คุณกำลังจะลบสมาชิก คุณแน่ใจแล้วหรือไม่?')"><i class="glyphicon glyphicon-trash"></i></a>
                    <a href="javascript:showotherdetail('member-<?=$member['uid'];?>');" title="รายละเอียดเพิ่มเติม"><i class="glyphicon glyphicon-chevron-down"></i></a>
                </td>
            </tr>
            <tr id="member-<?=$member['uid'];?>" style="display:none">
                <td colspan="7">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="col-md-8">ที่อยู่</th>
                                <th class="col-md-2 text-center">วัน/เวลา ที่สมัคร</th>
                                <th class="col-md-2 text-center">เข้าสู่ระบบล่าสุด</th>
                            </tr>
                            <tr>
                                <td><?php echo $member['address']; ?></td>
                                <td class="text-center"><?php echo $member['joined']; ?></td>
                                <td class="text-center"><?php echo $member['lastlogin']; ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <?php endif; ?>
    </table>
</div>
<div class="col-lg-12">
    <nav class="text-center">
        <ul class="pagination">
        <?php
            for($i=1; $i<=$Num_Pages; $i++){
                if($i != $Page){
                    echo "<li><a href='?page=".$_GET['page']."&p=$i'>$i</a></li>";
                } else {
                    echo "<li class='active'><a href='?page=".$_GET['page']."&p=$i'>$i</a></li>";
                }
            }
        ?>
        </ul>
    </nav>
</div>