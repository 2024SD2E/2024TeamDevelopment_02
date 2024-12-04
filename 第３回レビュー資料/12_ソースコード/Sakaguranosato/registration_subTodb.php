<?php
session_start();
// データベース接続のための準備
$pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
dbname=LAA1557125-sakagura;charset=utf8',
'LAA1557125',
'Pass2301386');
$name = $_POST['name'];
$birthdate = $_POST['ymd'];
$post_code = $_POST['post_code'];
$address = $_POST['address'];
$email = $_POST['email'];
$password = $_POST['password'];
$telephone_number = $_POST['telephone_number'];

if (isset($_POST['nickname']) && !empty($_POST['nickname'])) {
    $nickname = $_POST['nickname'];
    $sql = $pdo->prepare('INSERT INTO customer (customer_name, birthdate, email, password, nickname) VALUES (?, ?, ?, ?, ?)');
    $sql->execute([$name, $birthdate, $email, $password, $nickname]);

} else {
    $nickname = null;
    $sql = $pdo->prepare('INSERT INTO customer (customer_name, birthdate, email, password) VALUES (?, ?, ?, ?)');
    $sql->execute([$name, $birthdate, $email, $password]);

}

// 挿入されたcustomer_idを取得
$customerId = $pdo->lastInsertId();

// addressテーブルにデータを挿入
$sql = $pdo->prepare('INSERT INTO address (customer_id, name, post_code, address, telephone_number) VALUES (?, ?, ?, ?, ?)');
$sql->execute([$customerId, $name, $post_code, $address, $telephone_number]);

$_SESSION['customer'] = [
    'customer_id' => $customerId, 
    'customer_name' => $name,
    'email' => $email,
    'password' => $password,
    'nickname' => $nickname
];

// 正常に完了した場合、リダイレクト
header('Location: registration_submit.php');
exit;
?>