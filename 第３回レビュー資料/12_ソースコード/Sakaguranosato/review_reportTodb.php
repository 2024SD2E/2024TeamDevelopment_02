<?php
session_start();
ob_start();
// 前のページのURLを取得
$previousUrl = $_SERVER['HTTP_REFERER'] ?? 'default_page.php';

$pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
dbname=LAA1557125-sakagura;charset=utf8',
'LAA1557125',
'Pass2301386');

$review_id = $_POST['review_id'];
$report_sql = $pdo -> prepare('UPDATE review SET report = 1 WHERE review_id = ?');
$report_sql -> execute([$review_id]);

header("Location: $previousUrl");
exit;
?>