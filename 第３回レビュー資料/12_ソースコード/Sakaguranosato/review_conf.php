<?php
include 'header.php';

// セッションから商品情報を取得
$shohin_id = $_SESSION['shohin_id'] ?? null;
$shohin_name = $_SESSION['shohin_name'] ?? null;

$customer_id = $_SESSION['customer']['customer_id'];
// POSTデータの取得
$nickname = $_POST['nickname'] ?? null;
$rating = $_POST['rating'] ?? null;
$comment = $_POST['comment'] ?? null;

// 入力チェック
if (empty($shohin_id) || empty($shohin_name) || empty($nickname) || empty($rating) || empty($comment)) {
    die("入力に不備があります。もう一度やり直してください。");
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/review.css">
    <title>レビュー確認</title>
</head>
<body>

<div class="review-container">
    <h1>レビュー確認</h1>
    <p>以下の内容で登録します。</p>
    <hr>
    <p>商品名: <strong><?= htmlspecialchars($shohin_name) ?></strong></p>
    <p>ニックネーム: <?= htmlspecialchars($nickname) ?></p>
    <p>満足度: <?= htmlspecialchars($rating) ?>/5</p>
    <p>レビュー内容:</p>
    <p><?= nl2br(htmlspecialchars($comment)) ?></p>
    <form action="review_subTodb.php" method="post">
        <input type="hidden" name="shohin_id" value="<?= htmlspecialchars($shohin_id) ?>">
        <input type="hidden" name="nickname" value="<?= htmlspecialchars($nickname) ?>">
        <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer_id) ?>">
        <input type="hidden" name="rating" value="<?= htmlspecialchars($rating) ?>">
        <input type="hidden" name="comment" value="<?= htmlspecialchars($comment) ?>">
        <input type="submit" value="登録">
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
