<?php
    $captcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $captcha_secretkey = '6LfELhgUAAAAAEhE09dsVmttN3z0P_PIXMdecz0F';
    $captcha_response = file_get_contents($captcha_url.'?secret='.$captcha_secretkey.'&response='.$_POST['g-recaptcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR']);
    $captcha_data = json_decode($captcha_response);
?>