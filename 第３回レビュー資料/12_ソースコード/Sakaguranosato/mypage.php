<?php include 'header.php'; ?>
<!--マイページ-->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/mypage.css">
    <title>マイページ</title>
</head>
<body>
    <div id="mypage">
        <h1>マイページ</h1>
        <hr>
        <ul>
            <h2><a href="user_edit.php">ユーザー情報</a></h2>
            <h2><a href="user_purchasehistory.php">購入履歴</a></h2>
            <h2><a href="faq.php">よくある質問</a></h2>
            <h2><a href="contact.php">お問い合わせ</a></h2>
            <h2><a href="review_list.php">レビュー履歴</a></h2>
        </ul>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>