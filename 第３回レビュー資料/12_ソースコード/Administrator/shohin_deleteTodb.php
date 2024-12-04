<?php
session_start();
ob_start();

$pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
dbname=LAA1557125-sakagura;charset=utf8',
'LAA1557125',
'Pass2301386');

$shohin_id = $_POST['shohin_id'];

$delete_sql = $pdo->prepare('DELETE FROM shohin WHERE shohin_id = ?');
$delete_sql -> execute([$shohin_id]);

$delete_image = $pdo-> prepare('DELETE FROM shohin_images WHERE shohin_id = ?');
$delete_image -> execute([$shohin_id]);

header('Location: shohin_deletesubmit.php');
exit;
?>