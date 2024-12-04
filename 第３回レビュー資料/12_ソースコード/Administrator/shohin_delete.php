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

    $delete_sql = $pdo->prepare('SELECT * FROM shohin WHERE shohin_id = ?');
    $delete_sql->execute([$shohin_id]);
    $delete_shohin = $delete_sql->fetch(PDO::FETCH_ASSOC);

    $delete_images = $pdo->prepare('SELECT * FROM shohin_images WHERE shohin_id = ?');
    $delete_images->execute([$shohin_id]);
    $images = $delete_images->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/shohin_insert_confirm.css">
    <title>商品削除</title>
</head>

<body>
    <!-- タイトル部分 -->
    <h2>酒蔵の里 商品削除内容</h2>

    <!-- ヘッダー部分 -->
    <header>
        <p><a href="shohin_upanddel.php">戻る</a></p>
        <p>ログイン者: <?php echo htmlspecialchars($_SESSION['administrator']['name']); ?> さん</p>
        <div class="header-links">
            <a href="administrator_logout.php">ログアウト</a>
        </div>
    </header>
    <hr>
    <form action="shohin_deleteTodb.php" method="post">
        <input type="hidden" name="shohin_id" value=<?= $shohin_id; ?>>
        <div>
            <h3>こちらの商品を削除しますか？</h3>
        </div>
        <div>
            <label>商品名</label>
            <span><?php echo $delete_shohin['shohin_name']; ?></span>
        </div>
        <div>
            <label>価格 (税込み)</label>
            <span><?php echo $delete_shohin['price']; ?> 円</span>
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

        </div>
        <div>
            <label>商品説明</label>
            <p><?php echo nl2br($delete_shohin['shohin_description']); ?></p>
        </div>
        <button type="submit">商品を削除する</button>
    </form>

</body>

</html>