<?
    if($_GET['action'] == 'delete')
    {
        if($_GET['id'])
        {
            $delete_event = $connect->query("DELETE FROM product_event WHERE event_id = '".$_GET['id']."'");
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

<h4>จัดการ EVENT PLATFORM</h4>
<hr/>
<?
    $query_event = $connect->query("SELECT * FROM product_event");
    $num_event = $query_event->num_rows;
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Event ID</th>
                <th class="col-md-2">Event Name</th>
                <th class="col-md-3">PLATFORM ID</th>
                <th class="col-md-3">Go</th>
                <th class="text-center">Edit</th>
            </tr>
        </thead>
    <?
    if($num_event == '0')
    {
        ?>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">ไม่พบ Event</td>
            </tr>
        </tbody>
        <?
    }
    else
    {   
        ?>
            <tbody>
                <?
                while($event = $query_event->fetch_assoc())
                {
                    ?>
                    <tr>
                    <td><? echo $event['event_id'];?></td>
                    <td><? echo $event['event_platform_name'];?></td>
                    <td><? echo $event['event_type'];?></td>
                    <td><? echo $event['event_go'];?></td>
                    <td><center><a href="?page=eventplatform&action=delete&id=<?=$event['event_id'];?>" onclick="return confirm('คุณกำลังจะลบ Event คุณแน่ใจแล้วหรือไม่?')">ลบ</a></center></td>
                    </tr>
                    <?
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
<h4>เพิ่ม Event</h4>
<hr/>
<?
    if(isset($_POST['event_add']))
    {
        if(empty($_POST['event_name']))
        {
            exit("<meta http-equiv='refresh' content='1 ;url=./admin.php?page=eventplatform'>");
        }
        if(empty($_POST['event_id_platform']))
        {
            exit("<meta http-equiv='refresh' content='1 ;url=./admin.php?page=eventplatform'>");
        }
        if(empty($_POST['event_url']))
        {
            exit("<meta http-equiv='refresh' content='1 ;url=./admin.php?page=eventplatform'>");
        }
        else
        {
            $add_event_query = $connect->query("INSERT INTO product_event (event_platform_name,event_type,event_go) VALUES ('".$_POST['event_name']."','".$_POST['event_id_platform']."','".$_POST['event_url']."')");
            if($add_event_query)
            {
               echo "<meta http-equiv='refresh' content='1 ;url=./admin.php?page=eventplatform'>";
            }
            else
            {
                echo "<meta http-equiv='refresh' content='1 ;url=./admin.php?page=eventplatform'>";
            }
        }
    }
?>
<form name="add_event" method="POST">
    <div class="form-group col-md-12">
        <label>ชื่อ Event : </label>
        <input type="text" name="event_name" id="event_name" class="form-control" autocomplete="off" maxlength="100"/>
    </div>
    <div class="form-group col-md-3">
        <label>ID Platform : </label>
        <input type="text" name="event_id_platform" id="event_id_platform" class="form-control" autocomplete="off" maxlength="1"/>
    </div>
    <div class="form-group col-md-9">
        <label>URL : </label>
        <input type="text" name="event_url" id="event_url" class="form-control" autocomplete="off" maxlength="200"/>
    </div>
    <div class="col-md-6 text-center">
        <input type="submit" name="event_add" style="width:135px;" class="btn btn-success" value="เพิ่ม"/>
        <input type="reset" name="event_reset" class="btn btn-default" value="ยกเลิก" />
    </div>
</form>