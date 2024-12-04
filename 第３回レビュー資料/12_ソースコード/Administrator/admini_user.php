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

$user_sql = $pdo->prepare('select customer.customer_id, customer.customer_name, review.comment, review.review_id
from customer join review on 
review.customer_id = customer.customer_id where review.report=1');

$user_sql->execute();

$user = $user_sql->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/shohin_upanddel.css">
    <title>ユーザー管理</title>
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
    <form action="admini_user_conf.php" method="post">
    <table>
        <thead>
            <tr>
                <th>顧客ID</th>
                <th>顧客名</th>
                <th>レビュー内容</th>
                <th>削除選択</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['customer_id']) ?></td>
                    <td><?= htmlspecialchars($u['customer_name']) ?></td>
                    <td><?= nl2br(htmlspecialchars($u['comment'])) ?></td>
                    <td><input type="checkbox" name="delete[]" value="<?= $u['review_id'] ?>"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <input type="submit" value="削除確認画面へ">
</form>

</body>

</html>