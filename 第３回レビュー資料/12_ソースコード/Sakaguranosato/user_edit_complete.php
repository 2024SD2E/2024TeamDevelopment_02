<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/user_edit_submit.css"> <!-- 修正したCSSを読み込む -->
    <title>ユーザー情報更新完了</title>
</head>
<body>
    <div class="user-edit-container">
        <h1 align="center">更新完了</h1>
        <hr>
        <h2 align="center">情報が正常に更新されました。</h2>
        <div class="user-edit-button-container">
            <a href="mypage.php"><button>マイページに戻る</button></a>
        </div>
    </div>
    <?php include 'footer.php'; ?> <!-- フッターを挿入 -->    
</body>
</html>