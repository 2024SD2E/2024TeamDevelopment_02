<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/shohin_upanddel.css">
    <title>酒蔵の里 商品更新・削除一覧</title>
</head>

<body>
    <h2>酒蔵の里 商品更新・削除一覧</h2>
    <header>
        <p><a href="admini_top.php">戻る</a></p>
        <div class="header-right">
            <p>ログイン者: <?php echo htmlspecialchars($_SESSION['administrator']['name']); ?> さん</p>
            <a href="administrator_logout.php">ログアウト</a>
        </div>
    </header>


    <hr>

    <?php
    try {
        $pdo = new PDO(
            'mysql:host=mysql311.phy.lolipop.lan;
            dbname=LAA1557125-sakagura;charset=utf8',
            'LAA1557125',
            'Pass2301386'
        );
        $sql = "SELECT * FROM shohin";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("データベース接続に失敗しました: " . $e->getMessage());
    }
    ?>
    <table>
        <thead>
            <tr>
                <th>商品ID</th>
                <th>商品名</th>
                <th>価格</th>
                <th>更新</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['shohin_id']) ?></td>
                    <td><?= htmlspecialchars($product['shohin_name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?> 円</td>
                    <td><a href="shohin_update.php?shohin_id=<?= htmlspecialchars($product['shohin_id']) ?>"><button class="update-btn">更新</button></a></td>
                    <td><a href="shohin_delete.php?shohin_id=<?= htmlspecialchars($product['shohin_id']) ?>"><button class="delete-btn">削除</button></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>