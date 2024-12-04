<?php
session_start();
ob_start();
session_unset();
setcookie('PHPSESSID', session_id(), time()-3600);
session_destroy();
header('Location:administrator_login.php');
exit;
?>