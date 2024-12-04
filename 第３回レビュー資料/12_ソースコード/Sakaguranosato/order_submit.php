<?php
include 'header.php';  // ヘッダーをインクルード
// エラーや成功メッセージを取得
$status = $_GET['status'] ?? null;
$message = $_GET['message'] ?? null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文完了</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/order_submit.css"> <!-- 上記CSSファイルを読み込み -->
</head>
<body>
    <div class="order-submit-container">
        <div class="centered">
            <?php if ($status === 'success'): ?>
                <h3>注文が完了しました！</h3>
                <p>ご注文ありがとうございました。またのご利用をお待ちしております。</p>
                <div class="order-buttons">
                    <a href="top.php">トップページに戻る</a>
                </div>
            <?php elseif ($status === 'error'): ?>
                <h3>注文処理中にエラーが発生しました。</h3>
                <p>エラーの詳細: <?= htmlspecialchars(urldecode($message)) ?></p>
                <div class="order-buttons">
                    <a href="cart.php">カートに戻る</a>
                </div>
            <?php else: ?>
                <h3>不正なアクセスです。</h3>
                <div class="order-buttons">
                    <a href="top.php">トップページに戻る</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- フッターをインクルード -->
</body>
</html>
