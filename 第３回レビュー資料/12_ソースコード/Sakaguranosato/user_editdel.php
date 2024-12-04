<?php
session_start();
ob_start();

$pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
dbname=LAA1557125-sakagura;charset=utf8',
'LAA1557125',
'Pass2301386');
$address_id = $_POST['address_id'];

$delete_sql = $pdo -> prepare('DELETE FROM address WHERE address_id = ?');
$delete_sql -> execute([$address_id]);

header("Location: mypage.php");
exit;
?>