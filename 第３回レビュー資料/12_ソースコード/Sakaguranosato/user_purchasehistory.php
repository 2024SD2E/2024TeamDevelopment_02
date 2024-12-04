<?php
// header.php の読み込み
include 'header.php';
// データベース接続ファイル
$pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;
dbname=LAA1557125-sakagura;charset=utf8',
'LAA1557125',
'Pass2301386');

// ログインユーザーID（セッションから取得）
$customer_id = $_SESSION['customer']['customer_id'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入履歴</title>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/user_purchasehistory.css">
</head>
<body>
    <div class="history-container">
        <div class="title">購入履歴</div>

        <?php
        try {
            // 購入履歴と最初の画像を取得するSQL
            $sql = "SELECT
                        s.shohin_id,
                        s.shohin_name,
                        o.order_date,
                        MIN(si.image_name) AS image_name
                    FROM
                        orders o
                    INNER JOIN
                        order_detail od ON o.order_id = od.order_id
                    INNER JOIN
                        shohin s ON od.shohin_id = s.shohin_id
                    LEFT JOIN
                        shohin_images si ON s.shohin_id = si.shohin_id
                    WHERE
                        o.customer_id = :customer_id
                    GROUP BY
                        s.shohin_id, s.shohin_name, o.order_date
                    ORDER BY
                        o.order_date DESC";

            // クエリの準備と実行
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
            $stmt->execute();

            // 購入履歴を出力
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $shohin_id = htmlspecialchars($row['shohin_id']);
                $shohin_name = htmlspecialchars($row['shohin_name']);
                $order_date = htmlspecialchars($row['order_date']);
                $image_name = htmlspecialchars($row['image_name']);

                // 画像パスの生成
                if (!empty($image_name)) {
                    $image_path = "./upload/" . $image_name;
                } else {
                    $image_path = "./images/no_image_available.jpg"; // 代替画像
                }

                // 出力内容
                echo "
                <div class='item'>
                    <img src='{$image_path}' alt='{$shohin_name}' onerror=\"this.src='./images/no_image_available.jpg';\">
                    <div class='item-info'>
                        <h3>
                            <a href='productdetails.php?shohin_id={$shohin_id}' class='product-link'>
                                {$shohin_name}
                            </a>
                        </h3>
                        <p>注文日: {$order_date}</p>
                    </div>
                    <a href='review.php?shohin_id={$shohin_id}&shohin_name=" . urlencode($shohin_name) . "' class='review-button'>レビューを投稿</a>
                </div>";
            }
        } catch (PDOException $e) {
            echo "<p>エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
