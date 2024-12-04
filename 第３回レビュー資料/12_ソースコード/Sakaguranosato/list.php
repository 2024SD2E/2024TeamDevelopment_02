<?php
// データベース接続設定
$dsn = 'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557125-sakagura;charset=utf8mb4';
$username = 'LAA1557125';
$password = 'Pass2301386';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}

// フィルタリング条件
$minPrice = 0;
$maxPrice = PHP_INT_MAX;

if (isset($_GET['price'])) {
    switch ($_GET['price']) {
        case '~3000':
            $maxPrice = 3000;
            break;
        case '~5000':
            $maxPrice = 5000;
            break;
        case '~10000':
            $maxPrice = 10000;
            break;
        case '10000~':
            $minPrice = 10000;
            break;
    }
}

if (isset($_GET['volume']) && $_GET['volume'] !== 'all') {
    $volumeFilter = $_GET['volume'];
}

// SQLクエリで商品情報と画像を取得
$sql = "
    SELECT 
        s.shohin_id, 
        s.shohin_name, 
        s.price, 
        si.image_name
    FROM shohin s
    LEFT OUTER JOIN shohin_images si ON s.shohin_id = si.shohin_id
    WHERE si.image_name LIKE '%-01.jpg'
      AND s.price BETWEEN :minPrice AND :maxPrice
      " . ($volumeFilter ? "AND s.shohin_name REGEXP CONCAT('[[:space:]]', :volume, '$')" : "") . "
    GROUP BY s.shohin_id
    ORDER BY s.price ASC
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
$stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);

if ($volumeFilter) {
    $stmt->bindParam(':volume', $volumeFilter, PDO::PARAM_STR); // 内容量のみ渡す
}


$stmt->execute();
$shohinList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/list.css">
</head>
<body>
<div class="slideshow-container">
    <div class="slide"><img src="./img/ochoco.jpg" alt="スライド1"></div>
    <div class="slide"><img src="./img/dassai.png" alt="スライド2"></div>
    <div class="slide"><img src="./img/haisou.png" alt="スライド3"></div>
</div>
<div class="main-container">

    <div class="container">
        <h1>ITMES</h1>
        <p class="subtitle">商品一覧</p>

        <div class="filter-bar">
            <form method="GET" action="">
                <label for="price-filter">金額で絞る:</label>
                <select name="price" id="price-filter" onchange="this.form.submit()">
                    <option value="all" <?= !isset($_GET['price']) || $_GET['price'] == 'all' ? 'selected' : '' ?>>すべて</option>
                    <option value="~3000" <?= isset($_GET['price']) && $_GET['price'] == '~3000' ? 'selected' : '' ?>>~3000円</option>
                    <option value="~5000" <?= isset($_GET['price']) && $_GET['price'] == '~5000' ? 'selected' : '' ?>>~5000円</option>
                    <option value="~10000" <?= isset($_GET['price']) && $_GET['price'] == '~10000' ? 'selected' : '' ?>>~10000円</option>
                    <option value="10000~" <?= isset($_GET['price']) && $_GET['price'] == '10000~' ? 'selected' : '' ?>>10000円~</option>
                </select>

                <label for="volume-filter">内容量で絞る:</label>
                <select name="volume" id="volume-filter" onchange="this.form.submit()">
                    <option value="all" <?= !isset($_GET['volume']) || $_GET['volume'] == 'all' ? 'selected' : '' ?>>すべて</option>
                    <option value="300ml" <?= isset($_GET['volume']) && $_GET['volume'] == '300ml' ? 'selected' : '' ?>>300ml</option>
                    <option value="720ml" <?= isset($_GET['volume']) && $_GET['volume'] == '720ml' ? 'selected' : '' ?>>720ml</option>
                    <option value="1800ml" <?= isset($_GET['volume']) && $_GET['volume'] == '1800ml' ? 'selected' : '' ?>>1800ml</option>
                </select>
            </form>
        </div>

        <div class="product-grid">
            <?php
                foreach ($shohinList as $shohin) {
                    // 商品詳細ページのURLに shohin_id を渡す
                    $detailUrl = 'productdetails.php?shohin_id=' . urlencode($shohin['shohin_id']); // 修正点: id を shohin_id に変更
                    echo '<div class="product-card">';
                    echo '<a href="' . $detailUrl . '">'; // リンク開始
                    echo '<img src="upload/' . basename($shohin['image_name']) . '" alt="' . htmlspecialchars($shohin['shohin_name']) . '">';
                    echo '<div class="product-name">' . htmlspecialchars($shohin['shohin_name']) . '</div>';
                    echo '<div class="product-price">¥' . number_format($shohin['price']) . '</div>';
                    echo '</a>'; // リンク終了
                    echo '</div>';
                }
            ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

