<?php
include 'header.php';
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

// 検索キーワードの取得
$searchKeyword = '';
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchKeyword = trim($_POST['search']);
}

// SQLクエリで検索
$sql = "
    SELECT 
        s.shohin_id, 
        s.shohin_name, 
        s.price, 
        si.image_name
    FROM shohin s
    LEFT OUTER JOIN shohin_images si ON s.shohin_id = si.shohin_id
    WHERE si.image_name LIKE '%-01.jpg'
      AND s.shohin_name LIKE :searchKeyword
    GROUP BY s.shohin_id
    ORDER BY s.price ASC
";

$stmt = $pdo->prepare($sql);
$searchKeywordParam = '%' . $searchKeyword . '%'; // 部分一致検索
$stmt->bindParam(':searchKeyword', $searchKeywordParam, PDO::PARAM_STR);
$stmt->execute();
$shohinList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索結果</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/list.css">
</head>
<body>
<div class="main-container">
    <div class="container">
        <h1>検索結果</h1>

        <?php if (!empty($searchKeyword)): ?>
            <p class="search-result">「<?= htmlspecialchars($searchKeyword) ?>」を含むもの</p>
        <?php endif; ?>

        <div class="product-grid">
            <?php if (count($shohinList) > 0): ?>
                <?php foreach ($shohinList as $shohin): ?>
                    <?php $detailUrl = 'productdetails.php?shohin_id=' . urlencode($shohin['shohin_id']); ?>
                    <div class="product-card">
                        <a href="<?= $detailUrl ?>">
                            <img src="upload/<?= htmlspecialchars(basename($shohin['image_name'])) ?>" alt="<?= htmlspecialchars($shohin['shohin_name']) ?>">
                            <div class="product-name"><?= htmlspecialchars($shohin['shohin_name']) ?></div>
                            <div class="product-price">¥<?= number_format($shohin['price']) ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results">該当する商品が見つかりませんでした。</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
