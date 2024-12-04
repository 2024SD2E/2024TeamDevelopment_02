<?php
session_start();
ob_start();
$previousUrl = $_SERVER['HTTP_REFERER'] ?? 'default_page.php';
session_unset();
session_destroy();
header("Location: $previousUrl");
exit;
?>
