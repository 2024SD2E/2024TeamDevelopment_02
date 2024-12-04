<?php
session_start();
ob_start();

try {
    // データベース接続情報
    $pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
    dbname=LAA1557125-sakagura;charset=utf8',
    'LAA1557125',
    'Pass2301386');
} catch (PDOException $e) {
    die('データベース接続失敗: ' . htmlspecialchars($e->getMessage()));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $shohin_name = $_POST['shohin_name'];
        $price = $_POST['price'];
        $shohin_description = $_POST['shohin_description'];

        if (!$shohin_name || !$price || !$shohin_description) {
            throw new Exception('入力内容に不備があります。');
        }

        // 商品テーブルに情報を挿入
        $shohin_insert = $pdo->prepare(
            'INSERT INTO shohin (shohin_name, price, shohin_description) VALUES (?, ?, ?)'
        );
        $shohin_insert->execute([$shohin_name, $price, $shohin_description]);

        // 挿入したshohin_idを取得
        $shohin_id = $pdo->lastInsertId();

        // 画像名の保存処理
        if (!empty($_POST['images'])) {
            $stmt = $pdo->prepare(
                'INSERT INTO shohin_images (shohin_id, image_name) VALUES (?, ?)'
            );

            foreach ($_POST['images'] as $image_name) {
                $stmt->execute([$shohin_id, $image_name]);
            }
        } else {
            throw new Exception('画像がありません。');
        }

    } catch (Exception $e) {
        throw new Exception('エラー: ' . htmlspecialchars($e->getMessage()));
    }

    header('Location: shohin_insertSubmit.php');
    exit;
}
?>
