<?php
session_start();

// データベース接続設定
try {
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;
        dbname=LAA1557125-sakagura;charset=utf8',
        'LAA1557125',
        'Pass2301386'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}

if (isset($_GET['shohin_id']) && !empty($_GET['shohin_id'])) {
    $shohin_id = intval($_GET['shohin_id']);

    $update_sql = $pdo->prepare('SELECT * FROM shohin WHERE shohin_id = ?');
    $update_sql->execute([$shohin_id]);
    $update_shohin = $update_sql->fetch(PDO::FETCH_ASSOC);

    $update_images = $pdo->prepare('SELECT * FROM shohin_images WHERE shohin_id = ?');
    $update_images->execute([$shohin_id]);
    $images = $update_images->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品更新入力</title>
    <link rel="stylesheet" href="./css/insert.css">
</head>
<body>
    <!-- タイトル部分 -->
    <h2>酒蔵の里 商品更新入力</h2>

    <!-- ヘッダー部分 -->
    <header>
        <p><a href="shohin_upanddel.php">戻る</a></p>
        <p>ログイン者: <?php echo htmlspecialchars($_SESSION['administrator']['name']); ?> さん</p>
        <div class="header-links">
            <a href="administrator_logout.php">ログアウト</a>
        </div>
    </header>
    
    <hr>
    
    <!-- フォーム部分 -->
    <form action="shohin_update_confirm.php" method="post" enctype="multipart/form-data">
        <div>
            <p>商品ID<?= $update_shohin['shohin_id']?></p>
        </div>    
        <div>
            <p>商品名 <input type="text" name="shohin_name" value="<?= $update_shohin['shohin_name']?>" required></p>
        </div>
        <div>
            <p>価格 <input type="number" name="price" value="<?= $update_shohin['price']?>" required><br>
            <span>※税込みで入力してください</span></p>
        </div>
        <div>
            <label>商品画像</label>
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $image): ?>
                    <div>
                        <img src="../Sakaguranosato/upload/<?php echo htmlspecialchars($image['image_name'], ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像" style="max-width: 300px; max-height: 300px;">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>画像がアップロードされていません。</p>
            <?php endif; ?>
            <input type="file" name="shohin_images[]" id="images" accept="image/*" multiple></p>

        </div>
        <div>
            <p>商品説明 <textarea name="shohin_description"  required><?= $update_shohin['shohin_description']?></textarea></p>
        </div>
        <input type="hidden" name="shohin_id" value="<?= $shohin_id; ?>">
        <button type="submit">商品更新確認画面へ</button>
    </form>
</body>
</html>

</html>