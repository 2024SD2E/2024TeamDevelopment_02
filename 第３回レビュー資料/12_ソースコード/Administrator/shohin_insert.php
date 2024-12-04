<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規商品登録</title>
    <link rel="stylesheet" href="./css/insert.css">
</head>
<body>
    <!-- タイトル部分 -->
    <h2>酒蔵の里 新規商品登録</h2>

    <!-- ヘッダー部分 -->
    <header>
        <p><a href="admini_top.php">戻る</a></p>
        <p>ログイン者: <?php echo htmlspecialchars($_SESSION['administrator']['name']); ?> さん</p>
        <div class="header-links">
            <a href="administrator_logout.php">ログアウト</a>
        </div>
    </header>
    
    <hr>
    
    <!-- フォーム部分 -->
    <form action="shohin_insert_confirm.php" method="post" enctype="multipart/form-data">
        <div>
            <p>商品名 <input type="text" name="name" required></p>
        </div>
        <div>
            <p>価格 <input type="number" name="price" required><br>
            <span>※税込みで入力してください</span></p>
        </div>
        <div>
            <p>商品画像 <input type="file" name="images[]" id="images" accept="image/*" multiple required></p>
        </div>
        <div>
            <p>商品説明 <textarea name="explain" required></textarea></p>
        </div>
        <button type="submit">商品登録確認画面へ</button>
    </form>
</body>
</html>
