<?php
include 'class/adminclass.php';
$admin = new Admin();
$admin->logout();

header("Location: login.php");
exit();
?>
