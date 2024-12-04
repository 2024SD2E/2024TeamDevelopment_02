<?php
// ヘッダーを含む
include 'header.php';

// データベース接続設定
try {
    $pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
        dbname=LAA1557125-sakagura;charset=utf8',
        'LAA1557125',
        'Pass2301386');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}

// 商品IDを取得し、データを取得
if (isset($_GET['shohin_id']) && !empty($_GET['shohin_id'])) {
    $shohin_id = intval($_GET['shohin_id']);

    // 商品情報を取得
    $sql = "
        SELECT 
            s.shohin_name, 
            s.price, 
            s.shohin_description,
            GROUP_CONCAT(si.image_name) AS image_names
        FROM shohin s
        LEFT JOIN shohin_images si ON s.shohin_id = si.shohin_id
        WHERE s.shohin_id = :shohin_id
        GROUP BY s.shohin_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':shohin_id', $shohin_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // 商品が存在しない場合の処理
    if (!$product) {
        die('該当する商品が見つかりません。');
    }

    // レビューを取得
    $reviewSql = "
        SELECT * FROM review 
        WHERE shohin_id = :shohin_id 
        ORDER BY review_date DESC
    ";
    $reviewStmt = $pdo->prepare($reviewSql);
    $reviewStmt->bindParam(':shohin_id', $shohin_id, PDO::PARAM_INT);
    $reviewStmt->execute();
    $reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    die('商品IDが指定されていません。');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['shohin_name']) ?> - 商品詳細</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/user_productdetails.css">
</head>
<body>
    <div class="product-page">
        <!-- 画像ギャラリー -->
        <div class="image-gallery">
            <?php
            $imageDir = "upload/"; // 画像ディレクトリ
            $images = explode(',', $product['image_names']); // 複数画像をカンマで分割

            // 複数画像を表示
            foreach ($images as $image) {
                if (!empty($image)) {
                    echo '<div><img src="' . $imageDir . htmlspecialchars($image) . '" alt="商品画像"></div>';
                }
            }
            ?>
        </div>

        <!-- メイン画像 -->
        <div class="main-image">
            <img src="upload/<?= htmlspecialchars($images[0] ?? 'placeholder.png') ?>" alt="メイン商品写真">
        </div>

        <!-- 商品情報 -->
        <div class="product-details">
            <div class="product-title"><?= htmlspecialchars($product['shohin_name']) ?></div>
            <div class="price">¥<?= number_format($product['price']) ?></div>

            <!-- カート追加フォーム -->
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="shohin_id" value="<?= htmlspecialchars($shohin_id) ?>">

                <div class="quantity-selector">
                    <label for="quantity">数量</label>
                    <button type="button" onclick="decrement()">-</button>
                    <input type="number" id="quantity" name="quantity" value="1" min="1">
                    <button type="button" onclick="increment()">+</button>
                </div>

                <button type="submit" class="cart-button">カートに追加</button>
            </form>

            <div class="product-description">
                <p><?= nl2br(htmlspecialchars($product['shohin_description'])) ?></p>
            </div>
        </div>
    </div>

    <!-- レビューセクション -->
    <div class="reviews-section">
        <h2>レビュー</h2>
        <?php if (count($reviews) > 0): ?>
            <ul class="review-list">
                <?php foreach ($reviews as $review): ?>
                    <li class="review-item">
                        <?php 
                        $rating_star='';
                        for($i=1; $i <= $review['rating']; $i++){
                            $rating_star .= '★';
                        } 
                        ?>
                        <div class="review-header">
                            <span class="reviewer-name">ニックネーム：<?= htmlspecialchars($review['nickname'] . ' 様' ?: '匿名ユーザー') ?></span>
                            <span class="review-date">レビュー日：<?= htmlspecialchars($review['review_date']) ?></span>
                            <span class="review-rating">満足度：<?= $rating_star ?></span>
                        </div>
                        <div class="review-comment">
                            <p class="review-comment-item">コメント：</p>
                            <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        </div>
                        <div class="review_report">
                            <?php if($_SESSION['customer']): ?>
                                <form action="review_reportTodb.php" method="post">
                                    <input type="hidden" name="review_id" value=<?= $review['review_id']; ?>>
                                    <button type="submit">レビューを通報する</button>
                                </form>
                            <?php endif; ?>                             
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>レビューはまだ投稿されていません。</p>
        <?php endif; ?>
    </div>

    <!-- フッター -->
    <?php include 'footer.php'; ?>

    <script>
        function increment() {
            let quantity = document.getElementById("quantity");
            quantity.value = parseInt(quantity.value) + 1;
        }
        function decrement() {
            let quantity = document.getElementById("quantity");
            if (quantity.value > 1) {
                quantity.value = parseInt(quantity.value) - 1;
            }
        }
    </script>
</body>
</html>
