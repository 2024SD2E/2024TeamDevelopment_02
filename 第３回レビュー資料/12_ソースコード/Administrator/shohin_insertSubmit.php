<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録完了</title>
    <link rel="stylesheet" href="./css/shohin_insertSubmit.css">
</head>

<body>
    <!-- タイトル部分 -->
    <h2>酒蔵の里 新規商品登録完了</h2>

    <!-- ヘッダー部分 -->
    <header>
        <p>ログイン者: <?php echo htmlspecialchars($_SESSION['administrator']['name']); ?> さん</p>
        <div class="header-links">
            <a href="administrator_logout.php">ログアウト</a>
        </div>
    </header>
    <hr>
    <div class="submit">
        <h1>商品の登録が完了しました</h1>
        <a href="admini_top.php"><button type="submit">トップページへ戻る</button></a>        
    </div>

</body>

</html>
