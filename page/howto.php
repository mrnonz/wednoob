<h4>วิธีสั่งซื้อ</h4>
<div class="row" style="padding: 0px 10px 0px 10px;">
<?php
    $sql_howto = 'SELECT * FROM howto WHERE howto_id = "1"';
    $query_howto = $connect->query($sql_howto);
    $howto = $query_howto->fetch_assoc();
    if($howto['howto_youtube'] != NULL){
        echo '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://www.youtube.com/embed/'.$howto['howto_youtube'].'?showinfo=0" frameborder="0" allowfullscreen></iframe></div><br/>';
    }
?>
    <?php echo html_entity_decode($howto['howto_detail']); ?>
</div>