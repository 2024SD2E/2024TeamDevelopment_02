<?php 
ob_start();
include 'header.php'; 

// ユーザーがログインしているか確認
if (empty($_SESSION['customer']['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer']['customer_id'];

try {
    $pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
            dbname=LAA1557125-sakagura;charset=utf8',
            'LAA1557125',
            'Pass2301386');

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 顧客の住所情報を取得
    $addresses_stmt = $pdo->prepare("
        SELECT address_id, name, post_code, address, telephone_number 
        FROM address 
        WHERE customer_id = :customer_id
    ");
    $addresses_stmt->execute(['customer_id' => $customer_id]);
    $addresses = $addresses_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 電話番号の修正処理
    foreach ($addresses as $key => $address) {
        $telephone = $address['telephone_number'] ?? '';
        if (!empty($telephone) && $telephone[0] != '0') {
            $addresses[$key]['telephone_number'] = '0' . $telephone;
        }
    }

    // お届け時間帯を取得
    $delivery_stmt = $pdo->prepare("SELECT delivery_time_id, time_range FROM delivery_times");
    $delivery_stmt->execute();
    $delivery_times = $delivery_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 支払方法を取得
    $payment_stmt = $pdo->prepare("SELECT payment_id, payment_type FROM payment");
    $payment_stmt->execute();
    $payment_methods = $payment_stmt->fetchAll(PDO::FETCH_ASSOC);

    // カート情報を取得
    $cart_stmt = $pdo->prepare("
        SELECT 
            c.shohin_id, 
            SUM(c.shohin_sum) AS total_quantity, 
            s.shohin_name, 
            s.price, 
            s.shohin_description
        FROM cart c
        JOIN shohin s ON c.shohin_id = s.shohin_id
        WHERE c.customer_id = :customer_id
        GROUP BY c.shohin_id
    ");
    $cart_stmt->execute(['customer_id' => $customer_id]);
    $cart_items = $cart_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('データベース接続エラー: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/order.css">
    <title>注文</title>
</head>
<body>
    <div class="order-container">
        <h2 class="centered">注文内容確認</h2>

        <form action="process_order.php" method="post">
            <!-- ご注文商品 -->
            <div class="order-section">
                <hr>
                ご注文商品
                <hr>
                <div class="order-items">
                    <?php
                    if (count($cart_items) > 0) {
                        foreach ($cart_items as $item) {
                            $total_price = $item['price'] * $item['total_quantity'];
                            echo '
                            <div class="order-item">
                                <div class="item-details">
                                    <p class="item-name">' . htmlspecialchars($item['shohin_name']) . '</p>
                                    <p class="item-price">¥' . number_format($item['price']) . '</p>
                                </div>
                                <div class="item-quantity">
                                    <label>数量: ' . htmlspecialchars($item['total_quantity']) . '</label>
                                </div>
                                <div class="item-total">
                                    <p>合計: ¥' . number_format($total_price) . '</p>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<p>カートに商品がありません。</p>';
                    }
                    ?>
                </div>
            </div>

            <!-- お届け先情報 -->
            <div class="order-section">
                <h3>お届け先情報</h3>
                <div>
                    <input type="radio" name="address_option" id="existing_address" value="existing" required>
                    <label for="existing_address">既存の住所を使用</label>
                    <div id="existing-address-fields" style="margin-left: 20px;">
                        <?php if (!empty($addresses)): ?>
                            <?php foreach ($addresses as $address): ?>
                                <div>
                                    <input type="radio" name="address_id" value="<?= htmlspecialchars($address['address_id']); ?>">
                                    <label>
                                        <p>受取人名: <?= htmlspecialchars($address['name']); ?></p>
                                        <p>郵便番号: <?= htmlspecialchars($address['post_code']); ?></p>
                                        <p>住所: <?= htmlspecialchars($address['address']); ?></p>
                                        <p>電話番号: <?= htmlspecialchars($address['telephone_number'] ?: '登録なし'); ?></p>
                                    </label>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>登録された住所がありません。</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <input type="radio" name="address_option" id="new_address" value="new">
                    <label for="new_address">新しい住所を入力する</label>
                    <div id="new-address-fields" style="display: none; margin-left: 20px;">
                        <p><input type="text" name="alt_name" placeholder="受取人名"></p>
                        <p><input type="text" name="alt_post_code" placeholder="郵便番号"></p>
                        <p><textarea name="alt_address" placeholder="住所" rows="3" cols="50"></textarea></p>
                        <p><input type="text" name="alt_telephone_number" placeholder="電話番号 (任意)"></p>
                    </div>
                </div>
            </div>

            <!-- お届け時間指定 -->
            <div class="order-section">
                <hr>
                お届け時間指定
                <hr>
                <select name="delivery_time_id" required>
                    <?php
                    foreach ($delivery_times as $time) {
                        echo '<option value="' . htmlspecialchars($time['delivery_time_id']) . '">' . htmlspecialchars($time['time_range']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- お支払方法 -->
            <div class="order-section">
                <hr>
                お支払方法
                <hr>
                <div class="payment-method">
                    <?php
                    foreach ($payment_methods as $method) {
                        echo '<label><input type="radio" name="payment_id" value="' . htmlspecialchars($method['payment_id']) . '" required> ' . htmlspecialchars($method['payment_type']) . '</label>';
                    }
                    ?>
                </div>
            </div>

            <!-- 合計金額 -->
            <div class="order-section">
                <hr>
                合計金額
                <hr>
                <div class="order-total">
                    <p>合計: ¥<?php 
                        $total = 0;
                        foreach ($cart_items as $item) {
                            $total += $item['price'] * $item['total_quantity'];
                        }
                        echo number_format($total);
                    ?></p>
                </div>
            </div>

            <div class="order-buttons">
                <button class="return-cart"><a href="cart.php" style="text-decoration: none; color: black;">カートに戻る</a></button>
                <button class="confirm-order" type="submit">注文を確定する</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const existingAddressRadio = document.getElementById('existing_address');
            const newAddressRadio = document.getElementById('new_address');
            const existingAddressFields = document.getElementById('existing-address-fields');
            const newAddressFields = document.getElementById('new-address-fields');

            // ラジオボタンの状態に応じてフィールドを切り替える
            const toggleFields = () => {
                if (existingAddressRadio.checked) {
                    existingAddressFields.style.display = 'block';
                    newAddressFields.style.display = 'none';
                } else if (newAddressRadio.checked) {
                    existingAddressFields.style.display = 'none';
                    newAddressFields.style.display = 'block';
                }
            };

            // イベントリスナーを追加
            existingAddressRadio.addEventListener('change', toggleFields);
            newAddressRadio.addEventListener('change', toggleFields);

            // 初期状態を設定
            toggleFields();
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
