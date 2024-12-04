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

if(isset($_POST['customer_update'])) {
    $customer_id = $_SESSION['customer']['customer_id'];
    $customer_name = $_POST['customer_name'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nickname = $_POST['nickname'];
    $address_id = $_POST['address_id'];
    $telephone_number = $_POST['telephone_number'];

    $update_sql = $pdo->prepare('UPDATE customer SET customer_name = ?, birthdate = ?, email = ?, password = ?, nickname = ? WHERE customer_id = ?');
    $update_sql -> execute([$customer_name, $birthdate, $email, $password, $nickname, $customer_id]);

    $address_sql = $pdo -> prepare('UPDATE address SET telephone_number = ? WHERE address_id = ?');
    $address_sql -> execute([$telephone_number, $address_id]);

    header('Location: user_edit_complete.php');
    exit;
}
?>