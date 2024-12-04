<?php
include 'header.php';

// 必要なパラメータを取得
$shohin_id = $_GET['shohin_id'] ?? null;
$shohin_name = $_GET['shohin_name'] ?? null;

// 商品情報が不足している場合のエラー処理
if (empty($shohin_id) || empty($shohin_name)) {
    die("商品情報が不足しています。もう一度購入履歴からやり直してください。");
}

// 商品情報をセッションに保存
$_SESSION['shohin_id'] = $shohin_id;
$_SESSION['shohin_name'] = $shohin_name;

$nickname = $_SESSION['customer']['nickname'];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/review.css">
    <title>レビュー登録</title>
</head>
<body>

<div class="review-container">
    <h1>レビュー登録</h1>
    <form action="review_conf.php" method="post" class="review-form">
        <p><strong><?= htmlspecialchars($shohin_name) ?></strong> の商品をレビューする</p>
        <hr>
        <p>ニックネーム</p>
        <p><input type="text" name="nickname" value=<?= $nickname ?> required></p>
        <hr>
        <p>この商品に対する満足度</p>
        <p>
            <input type="number" name="rating" min="1" max="5" value="5" required> 
            <span>(1: とても不満 ~ 5: 大満足)</span>
        </p>
        <hr>
        <p>レビュー内容</p>
        <p>
            <textarea name="comment" cols="100" rows="10" required placeholder="この商品に対する感想や意見を記入してください。"></textarea>
        </p>
        <p><input type="submit" value="確認"></p>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

