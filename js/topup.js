function RefillTruewallettwo() {
    /* if(grecaptcha.getResponse() == 0) {
        $("#rs_refill").fadeOut(400, function() {
            $(this).fadeIn(400).html('<div class="alert alert-danger"><strong>Error: </strong>กรุณายืนยัน Captcha เพื่อป้องกันโปรแกรมอัตโนมัติ</div>');
        });
        return false;
    } */
    $.ajax({
        type: "POST",
        url: "https://edok-pro.ml/application/autoload.php?func=refilltruewallet",
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
                //setTimeout(function(){location.href = 'member.php?page=historyrefill'},3000);
            }
            document.getElementById("refilltruewallet_btn").disabled = false;
            $("#refilltruewallet_btn").html('แจ้งเติมเงิน');
        }
    })
}