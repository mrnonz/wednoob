<?php
    session_start();
    unset($_SESSION['member_uid']);
    unset($_SESSION['member_status']);
    unset($_SESSION['member_username']);
    header('location:'.$_GET['to']);
    exit();