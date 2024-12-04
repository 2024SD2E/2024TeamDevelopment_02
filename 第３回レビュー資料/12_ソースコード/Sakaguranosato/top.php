<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/toppage.css">
</head>
<body>
    <?php
    // データベース接続
    $dsn = 'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557125-sakagura;charset=utf8mb4';
    $username = 'LAA1557125';
    $password = 'Pass2301386';

    try {
        $pdo = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo 'データベース接続エラー: ' . $e->getMessage();
        exit;
    }

    // 売れ筋ランキングを取得（売り上げ順に上位3商品を取得）
    $salesRankingQuery = "
        SELECT 
            sh.shohin_id, 
            sh.shohin_name, 
            sh.price, 
            img.image_name 
        FROM shohin sh
        JOIN shohin_images img ON sh.shohin_id = img.shohin_id
        JOIN order_detail od ON sh.shohin_id = od.shohin_id
        WHERE img.image_name LIKE '%-01%'
        GROUP BY sh.shohin_id
        ORDER BY SUM(od.quantity) DESC
        LIMIT 3
    ";
    $salesRanking = $pdo->query($salesRankingQuery)->fetchAll(PDO::FETCH_ASSOC);

    // 商品一覧を取得（最新6商品を取得）
    $productListQuery = "
        SELECT 
            sh.shohin_id, 
            sh.shohin_name, 
            sh.price, 
            img.image_name 
        FROM shohin sh
        JOIN shohin_images img ON sh.shohin_id = img.shohin_id
        WHERE img.image_name LIKE '%-01%'
        LIMIT 6
    ";
    $productList = $pdo->query($productListQuery)->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- スライドショー -->
    <div class="slideshow-container">
        <div class="slide">
            <img src="./img/ochoco.jpg" alt="スライド1">
        </div>
        <div class="slide">
            <img src="./img/dassai.png" alt="スライド2">
        </div>
        <div class="slide">
            <img src="./img/haisou.png" alt="スライド3">
        </div>
    </div>

<!-- 売れ筋ランキング -->
    <div class="section">
        <div class="section-title">RANKING</div>
        <div class="section-subtitle">売れ筋商品</div>
        <div class="ranking product-grid">
            <?php $i = 1;
            foreach ($salesRanking as $product): ?>
                <a href="productdetails.php?shohin_id=<?= urlencode($product['shohin_id']) ?>" class="product-link">
                    <div class="product-card">
                        <div class="product-rank"><?= $i . '位' ?></div>
                        <img src="./upload/<?= basename($product['image_name']) ?>" alt="商品画像">
                        <div class="product-title"><?= htmlspecialchars($product['shohin_name'], ENT_QUOTES) ?></div>
                        <div class="product-price">¥<?= number_format($product['price']) ?></div>
                    </div>
                </a>
                <?php $i++; ?>
            <?php endforeach; ?>
        </div>
    </div>

<!-- 商品一覧 -->
    <div class="section">
        <div class="section-title">ITEMS</div>
        <div class="section-subtitle">商品一覧</div>
        <div class="items product-grid">
            <?php foreach ($productList as $product): ?>
                <a href="productdetails.php?shohin_id=<?= urlencode($product['shohin_id']) ?>" class="product-link">
                    <div class="product-card">
                        <img src="./upload/<?= basename($product['image_name']) ?>" alt="商品画像">
                        <div class="product-title"><?= htmlspecialchars($product['shohin_name'], ENT_QUOTES) ?></div>
                        <div class="product-price">¥<?= number_format($product['price']) ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 商品一覧をみるボタン -->
    <div class="view-more-container">
        <a href="list.php">
            <button class="view-more-button">商品一覧をみる</button>
        </a>
    </div>
</body>
</html>

</body>
<?php include 'footer.php'; ?>
</html>
