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
} catch (PDOException $e) {
    die('データベース接続エラー: '.$e->getMessage());
}

// 数量更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $shohin_id = $_POST['shohin_id'];
    $new_quantity = $_POST['quantity'];

    if ($new_quantity > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE cart SET shohin_sum = :quantity WHERE customer_id = :customer_id AND shohin_id = :shohin_id");
            $stmt->execute([
                'quantity' => $new_quantity,
                'customer_id' => $customer_id,
                'shohin_id' => $shohin_id
            ]);
            header("Location: cart.php"); // 更新後にリロード
            exit();
        } catch (Exception $e) {
            echo '<p>エラーが発生しました: '.$e->getMessage().'</p>';
        }
    }
}

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $shohin_id = $_POST['shohin_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE customer_id = :customer_id AND shohin_id = :shohin_id");
        $stmt->execute([
            'customer_id' => $customer_id,
            'shohin_id' => $shohin_id
        ]);
        header("Location: cart.php"); // 削除後にリロード
        exit();
    } catch (Exception $e) {
        echo '<p>エラーが発生しました: '.$e->getMessage().'</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/cart.css">
    <title>カート</title>
</head>
<body>
    <div class="cart-container">
        <h2 class="centered">カートの中身</h2>
        
        <!-- 商品リスト -->
        <div class="cart-items">
            <?php
            try {
                // カートと商品情報を取得するSQL
                $stmt = $pdo->prepare("
                    SELECT c.shohin_id, c.shohin_sum, s.shohin_name, s.price, s.shohin_description 
                    FROM cart c
                    JOIN shohin s ON c.shohin_id = s.shohin_id
                    WHERE c.customer_id = :customer_id
                ");
                $stmt->execute(['customer_id' => $customer_id]);
                $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // カートが空でない場合
                if ($cart_items) {
                    foreach ($cart_items as $item) {
                        echo '
                        <div class="cart-item">
                            <form action="cart.php" method="post">
                                <div class="item-details">
                                    <p class="item-name">'.htmlspecialchars($item['shohin_name']).'</p>
                                    <p class="item-price">¥'.number_format($item['price']).'</p>
                                </div>
                                <div class="item-quantity">
                                    <label for="quantity'.$item['shohin_id'].'">数量:</label>
                                    <input type="number" id="quantity'.$item['shohin_id'].'" name="quantity" value="'.htmlspecialchars($item['shohin_sum']).'" min="1" max="10">
                                </div>
                                <div class="item-total">
                                    <p>合計: ¥'.number_format($item['price'] * $item['shohin_sum']).'</p>
                                </div>
                                <input type="hidden" name="shohin_id" value="'.htmlspecialchars($item['shohin_id']).'">
                                <button type="submit" name="update_quantity">数量を更新</button>
                                <button type="submit" name="delete_item" id="del_button">削除</button>
                            </form>
                        </div>';
                    }
                } else {
                    echo '<p>カートに商品がありません。</p>';
                }
            } catch (Exception $e) {
                echo '<p>エラーが発生しました: '.$e->getMessage().'</p>';
            }
            ?>
        </div>
        
        <hr>

        <!-- 合計金額 -->
        <div class="cart-summary">
            <p>合計金額: <?php 
                $total = 0;
                if ($cart_items) {
                    foreach ($cart_items as $item) {
                        $total += $item['price'] * $item['shohin_sum'];
                    }
                }
                echo number_format($total) . '円';

                // セッションに合計金額を保存
                $_SESSION['cart_total'] = $total;
            ?></p>
        </div>

        <div class="cart-buttons">
            <!-- 商品一覧ページに戻るボタン -->
            <button><a href="./list.php" style="text-decoration: none; color: white;">商品一覧に戻る</a></button>
            
            <!-- 注文に進むボタン -->
            <button><a href="./order.php" style="text-decoration: none; color: white;">注文に進む</a></button>
        </div>

    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
