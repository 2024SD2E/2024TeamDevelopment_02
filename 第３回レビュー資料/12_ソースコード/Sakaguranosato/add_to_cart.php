<?php
// セッションでログインユーザーを確認
ob_start();
session_start();
if (!isset($_SESSION['customer']['customer_id'])) {
    header("Location: login.php");
    exit();
}

// データベース接続
$pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
    dbname=LAA1557125-sakagura;charset=utf8',
    'LAA1557125',
    'Pass2301386');

$customer_id = $_SESSION['customer']['customer_id'];

// POSTデータを取得
$shohin_id = intval($_POST['shohin_id']);
$shohin_sum = intval($_POST['quantity']);

// カート内の同じ商品を確認
$stmt = $pdo->prepare("SELECT * FROM cart WHERE customer_id = :customer_id AND shohin_id = :shohin_id");
$stmt->execute(['customer_id' => $customer_id, 'shohin_id' => $shohin_id]);
$cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cart_item) {
    // 数量を更新
    $new_sum = $cart_item['shohin_sum'] + $shohin_sum;
    $stmt = $pdo->prepare("UPDATE cart SET shohin_sum = :shohin_sum, `update` = NOW() WHERE cart_id = :cart_id");
    $stmt->execute(['shohin_sum' => $new_sum, 'cart_id' => $cart_item['cart_id']]);
} else {
    // 新しい商品を追加
    $stmt = $pdo->prepare("INSERT INTO cart (customer_id, shohin_id, shohin_sum, adddate) 
                           VALUES (:customer_id, :shohin_id, :shohin_sum, NOW())");
    $stmt->execute(['customer_id' => $customer_id, 'shohin_id' => $shohin_id, 'shohin_sum' => $shohin_sum]);
}
header("Location: cart.php");
exit();
?>