<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ユーザーがログインしているか確認
if (empty($_SESSION['customer']['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer']['customer_id'];

try {
    // データベース接続
    $pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
        dbname=LAA1557125-sakagura;charset=utf8',
        'LAA1557125',
        'Pass2301386'); // 必要に応じてDSNを設定してください
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POSTデータを取得
    $address_option = $_POST['address_option'] ?? null; // ラジオボタンの選択
    $payment_id = $_POST['payment_id'] ?? null; // 支払方法
    $delivery_time_id = $_POST['delivery_time_id'] ?? null; // お届け時間帯

    // 入力データのバリデーション
    if (!$address_option || !$payment_id || !$delivery_time_id) {
        throw new Exception('住所選択、支払方法、またはお届け時間帯が未選択です。');
    }

    // トランザクション開始
    $pdo->beginTransaction();

    if ($address_option === 'existing') {
        // 既存住所を使用
        $address_id = $_POST['address_id'] ?? null;
        if (!$address_id) {
            throw new Exception('既存の住所が選択されていません。');
        }
    } elseif ($address_option === 'new') {
        // 新しい住所を登録
        $alt_name = trim($_POST['alt_name'] ?? '');
        $alt_post_code = trim($_POST['alt_post_code'] ?? '');
        $alt_address = trim($_POST['alt_address'] ?? '');
        $alt_telephone_number = trim($_POST['alt_telephone_number'] ?? '');

        if (empty($alt_name) || empty($alt_post_code) || empty($alt_address)) {
            throw new Exception('新しい住所の必須項目が入力されていません。');
        }

        // 新住所を住所テーブルに保存
        $address_stmt = $pdo->prepare("
            INSERT INTO address (customer_id, name, post_code, address, telephone_number) 
            VALUES (:customer_id, :name, :post_code, :address, :telephone_number)
        ");
        $address_stmt->execute([
            'customer_id' => $customer_id,
            'name' => $alt_name,
            'post_code' => $alt_post_code,
            'address' => $alt_address,
            'telephone_number' => $alt_telephone_number
        ]);

        // 挿入された住所IDを取得
        $address_id = $pdo->lastInsertId();
    } else {
        throw new Exception('住所の選択が無効です。');
    }

    // カート情報を取得
    $cart_stmt = $pdo->prepare("
        SELECT 
            c.cart_id,
            c.shohin_id, 
            SUM(c.shohin_sum) AS total_quantity, 
            s.price
        FROM cart c
        JOIN shohin s ON c.shohin_id = s.shohin_id
        WHERE c.customer_id = :customer_id
        GROUP BY c.cart_id, c.shohin_id
    ");
    $cart_stmt->execute(['customer_id' => $customer_id]);
    $cart_items = $cart_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cart_items)) {
        throw new Exception('カートが空です。注文を確定できません。');
    }

    // ordersテーブルに注文情報を登録
    $order_stmt = $pdo->prepare("
        INSERT INTO orders (
            customer_id, 
            address_id, 
            payment_id, 
            delivery_time_id, 
            order_date
        ) VALUES (
            :customer_id, 
            :address_id, 
            :payment_id, 
            :delivery_time_id, 
            NOW()
        )
    ");
    $order_stmt->execute([
        'customer_id' => $customer_id,
        'address_id' => $address_id,
        'payment_id' => $payment_id,
        'delivery_time_id' => $delivery_time_id
    ]);

    // 注文IDを取得
    $order_id = $pdo->lastInsertId();

    // 注文明細を登録
    $order_detail_stmt = $pdo->prepare("
        INSERT INTO order_detail (
            order_id, 
            shohin_id, 
            quantity
        ) VALUES (
            :order_id, 
            :shohin_id, 
            :quantity
        )
    ");

    foreach ($cart_items as $item) {
        $order_detail_stmt->execute([
            'order_id' => $order_id,
            'shohin_id' => $item['shohin_id'],
            'quantity' => $item['total_quantity']
        ]);
    }

    // カートをクリア
    $cart_clear_stmt = $pdo->prepare("DELETE FROM cart WHERE customer_id = :customer_id");
    $cart_clear_stmt->execute(['customer_id' => $customer_id]);

    // トランザクションをコミット
    $pdo->commit();

    // 注文完了ページへリダイレクト
    header("Location: order_submit.php?status=success");
    exit();

} catch (PDOException $e) {
    // データベースエラー時にロールバック
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $error_message = urlencode("データベースエラー: " . $e->getMessage());
    header("Location: order_submit.php?status=error&message=$error_message");
    exit();

} catch (Exception $e) {
    // 汎用エラー時にロールバック
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $error_message = urlencode("エラー: " . $e->getMessage());
    header("Location: order_submit.php?status=error&message=$error_message");
    exit();
}
