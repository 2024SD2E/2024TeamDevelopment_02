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

$user_delete = $_POST['delete'];

$i = 1;

$user_sql ='select customer.customer_id, customer.customer_name, review.comment, review.review_id
    from customer inner join review on
    review.customer_id = customer.customer_id where';
    
foreach($user_delete as $ul){
    if(count($user_delete) === $i){
        $user_sql = $user_sql. ' review.review_id = ' .$ul;

    }else{
        $user_sql = $user_sql. ' review.review_id = ' .$ul . ' or';
        $i = $i + 1;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/shohin_upanddel.css">
    <title>削除確認画面</title>
</head>
<body>
<h2>酒蔵の里 削除確認</h2>
    <header>
        <p><a href="admini_user.php">戻る</a></p>
        <div class="header-right">
            <p>ログイン者: <?php echo htmlspecialchars($_SESSION['administrator']['name']); ?> さん</p>
            <a href="administrator_logout.php">ログアウト</a>
        </div>
    </header>
    <hr>
    <h3>選択したユーザーを削除しますか？</h3>
    <form action="admini_userTodb.php" method="post">
    <table>
        <thead>
            <tr>
                <th>顧客ID</th>
                <th>顧客名</th>
                <th>レビュー内容</th>
            </tr>
        </thead>
        <?php $user = $pdo ->query($user_sql); ?>
        <tbody>
            
                <?php foreach ($user as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['customer_id']) ?></td>
                        <td><?= htmlspecialchars($u['customer_name']) ?></td>
                        <td><?= nl2br(htmlspecialchars($u['comment'])) ?></td>
                    </tr>
                    <input type="hidden" name="review_id[]" value="<?= $u['review_id']; ?>">
                    <input type="hidden" name="customer_id[]" value="<?= $u['customer_id']; ?>">
                <?php endforeach; ?>
        </tbody>
    </table>
    <input type="submit" value="ユーザーを削除する">
    </form>
</body>
</html>