<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/review_list.css">
    <title>レビュー一覧</title>
</head>
<body>
    <div id="reviews-page">
        <h1>レビュー一覧</h1>
        <div class="reviews-container">
            <?php
                // PDO接続設定
                $pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
                    dbname=LAA1557125-sakagura;charset=utf8',
                    'LAA1557125',
                    'Pass2301386');
                $customer_id = $_SESSION['customer']['customer_id'];

                // レビュー取得
                $sql = $pdo->prepare('SELECT * FROM review INNER JOIN shohin ON review.shohin_id = shohin.shohin_id WHERE review.customer_id = ?');
                $sql->execute([$customer_id]);
                $cnt = $sql->rowCount();

                if ($cnt > 0) {
                    foreach ($sql as $row) {
                        // 評価を★で表示
                        $star = '';
                        for ($i = 1; $i <= $row['rating']; $i++) {
                            $star .= '★';
                        }

                        echo '<hr>';
                        // 商品名リンク
                        echo '<p class="review-product-name"><a href="productdetails.php?shohin_id=' . htmlspecialchars($row['shohin_id']) . '">' . htmlspecialchars($row['shohin_name']) . '</a></p>';
                        echo '<hr>';
                        echo '<p class="review-rating">' . $star . '</p>';
                        echo '<hr>';
                        echo '<p class="review-comment">' . nl2br(htmlspecialchars($row['comment'])) . '</p>';

                        // 削除フォーム
                        echo '<form method="POST" action="review_delTodb.php" class="delete-review-form">';
                        echo '<input type="hidden" name="review_id" value="' . htmlspecialchars($row['review_id']) . '">';
                        echo '<button type="submit" name="delete_review" class="delete-button">削除</button>';
                        echo '</form>';
                    }
                } else {
                    echo '<p class="no-reviews">レビューがまだ投稿されていません</p>';
                }
            ?>
        </div>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>
