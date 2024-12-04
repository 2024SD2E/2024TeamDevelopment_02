<?php
session_start();
ob_start();
try {
    $pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
        dbname=LAA1557125-sakagura;charset=utf8',
        'LAA1557125',
        'Pass2301386');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('データベース接続エラー: ' . $e->getMessage());
}

if($_POST['address_update']){
    $address_id = $_POST['address_id'];  // 選択された住所のIDを取得
    $name = $_POST['name'][$address_id]; // 選択された住所の受取人名
    $post_code = $_POST['post_code'][$address_id];    // 郵便番号
    $address = $_POST['address'][$address_id]; // 選択された住所
    $telephone_number = $_POST['telephone_number'][$address_id]; // 電話番号

    // 住所情報を更新する
    $address_update = $pdo->prepare('UPDATE address SET name = ?, post_code = ?, address = ?, telephone_number = ? WHERE address_id = ?');
    $address_update->execute([$name, $post_code, $address, $telephone_number, $address_id]);

    header('Location: user_edit_complete.php');
    exit;
}

?>