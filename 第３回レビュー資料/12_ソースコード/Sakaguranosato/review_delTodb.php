<?php
session_start();
ob_start();

$pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
    dbname=LAA1557125-sakagura;charset=utf8',
    'LAA1557125',
    'Pass2301386');

$review_id = $_POST['review_id'];

$sql = $pdo -> prepare('DELETE FROM review WHERE review_id = ?');
$sql -> execute([$review_id]);

header('Location: review_delete.php');
exit;

?>