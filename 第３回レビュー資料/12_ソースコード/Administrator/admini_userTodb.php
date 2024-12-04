<?php
session_start();
ob_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
    dbname=LAA1557125-sakagura;charset=utf8',
    'LAA1557125',
    'Pass2301386');

    // POST データの検証
    if (empty($_POST['review_id']) || empty($_POST['customer_id'])) {
        throw new Exception('削除対象が選択されていません。');
    }

    $review_ids = $_POST['review_id'];
    $customer_ids = $_POST['customer_id'];

    if (!is_array($review_ids) || !is_array($customer_ids)) {
        throw new Exception('データ形式が不正です。');
    }

    // トランザクション開始
    $pdo->beginTransaction();

    // レビュー削除
    $review_placeholders = implode(',', array_fill(0, count($review_ids), '?'));
    $review_sql = "DELETE FROM review WHERE review_id IN ($review_placeholders)";
    $review_stmt = $pdo->prepare($review_sql);


    $review_stmt->execute($review_ids);

    // 顧客削除
    $customer_placeholders = implode(',', array_fill(0, count($customer_ids), '?'));
    $customer_sql = "DELETE FROM customer WHERE customer_id IN ($customer_placeholders)";
    $customer_stmt = $pdo->prepare($customer_sql);


    $customer_stmt->execute($customer_ids);

    // コミット
    $pdo->commit();

    // リダイレクト
    header('Location: admini_userSubmit.php');
    exit;

} catch (Exception $e) {
    // ロールバック
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die('エラーが発生しました: ' . htmlspecialchars($e->getMessage()));
}
?>