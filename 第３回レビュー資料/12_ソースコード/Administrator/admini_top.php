<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者トップページ</title>
    <link rel="stylesheet" href="./css/top.css">
</head>
<body>
    <h2>酒蔵の里 管理者トップページ</h2>
    <!-- ヘッダー部分 -->
    <header>
        <label>ログイン者: <?php echo $_SESSION['administrator']['name']; ?>さん</label>
        <a href="administrator_logout.php">ログアウト</a>
    </header>
    <hr>

    <!-- 商品登録 -->
    <p>
        <a href="shohin_insert.php"><button type="submit">商品登録</button></a>
    </p>

    <!-- 商品更新・削除 -->
    <p>
        <a href="shohin_upanddel.php"><button type="submit">商品更新・削除</button></a>
    </p>

    <!-- ユーザー削除 -->
    <p>
        <a href="admini_user.php"><button type="submit">ユーザー削除</button></a>
    </p>
    
</body>
</html>
