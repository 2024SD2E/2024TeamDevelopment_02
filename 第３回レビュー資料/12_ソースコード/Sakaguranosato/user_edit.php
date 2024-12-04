<?php
include 'header.php';

try {
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;
        dbname=LAA1557125-sakagura;charset=utf8',
        'LAA1557125',
        'Pass2301386'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'データベース接続エラー: ' . $e->getMessage();
    exit;
}

// セッションから顧客IDを取得
if (!isset($_SESSION['customer']['customer_id'])) {
    echo 'ログインしてください。';
    exit;
}
$customer_id = $_SESSION['customer']['customer_id'];

// 電話番号を補正する関数
function formatTelephoneNumber($telephone)
{
    if (!empty($telephone) && $telephone[0] != '0') {
        return '0' . $telephone;
    }
    return $telephone;
}

// 本人情報を取得
$customer_stmt = $pdo->prepare(
    'SELECT * 
     FROM customer c 
     JOIN address a ON c.customer_id = a.customer_id 
     WHERE c.customer_id = ? 
     LIMIT 1'
);
$customer_stmt->execute([$customer_id]);
$customer = $customer_stmt->fetch(PDO::FETCH_ASSOC);

if ($customer) {
    $customer['telephone_number'] = formatTelephoneNumber($customer['telephone_number'] ?? '');
}

// 顧客の住所情報を取得
$addresses_stmt = $pdo->prepare(
    'SELECT address_id, name, post_code, address, telephone_number 
     FROM address 
     WHERE customer_id = :customer_id'
);
$addresses_stmt->execute(['customer_id' => $customer_id]);
$addresses = $addresses_stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($addresses as $key => $address) {
    $addresses[$key]['telephone_number'] = formatTelephoneNumber($address['telephone_number'] ?? '');
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/user_edit.css"> <!-- 修正したCSSを読み込む -->
    <title>ユーザー情報</title>
</head>

<body>
    <div class="main-content user_edit">
        <h1 align="center">ユーザー情報</h1>
        <hr>
        <h2 align="center">プロフィールの詳細</h2>
        <h3>本人情報を更新する</h3>
        <div>
            <form action="user_updateTodb.php" method="post">
                <?php if ($customer): ?>
                    <input type="hidden" name="address_id" value="<?= htmlspecialchars($customer['address_id']) ?>;">
                    <div>
                        <p>名前：<input type="text" name="customer_name" value="<?= htmlspecialchars($customer['customer_name']); ?>"></p>
                        <p>生年月日：<input type="date" name="birthdate" value="<?= htmlspecialchars($customer['birthdate']); ?>"></p>
                        <p>メールアドレス：<input type="email" name="email" value="<?= htmlspecialchars($customer['email']); ?>"></p>
                        <p>パスワード：<input type="password" name="password" value="<?= htmlspecialchars($customer['password']) ?>"></p>
                        <p>電話番号：<input type="text" name="telephone_number" value="<?= htmlspecialchars($customer['telephone_number']); ?>"></p>
                        <p>ニックネーム：<input type="text" name="nickname" value="<?= htmlspecialchars($customer['nickname']); ?>"></p>
                    </div>
                <?php else: ?>
                    <p>本人情報が見つかりません。</p>
                <?php endif; ?>
                <div class="button-container">
                    <input type="submit" name="customer_update" value="本人情報を更新する">
                </div>
            </form>
        </div>


        <h3>現在登録されている住所情報を更新する</h3>
        <div>

            <?php
            $i = 1;
             if ($addresses): ?>
                <?php foreach ($addresses as $address): ?>
                    <form action="address_updateTodb.php" method="post">
                        <div class="address-info">
                            <input type="hidden" name="address_id" value="<?= htmlspecialchars($address['address_id']); ?>">
                            <p>
                                受取人名: <input type="text" name="name[<?= $address['address_id']; ?>]" value="<?= htmlspecialchars($address['name']); ?>">
                                郵便番号: <input type="text" name="post_code[<?= $address['address_id']; ?>]" value="<?= htmlspecialchars($address['post_code']); ?>">
                                住所: <input type="text" name="address[<?= $address['address_id']; ?>]" value="<?= htmlspecialchars($address['address']); ?>">
                                電話番号: <input type="text" name="telephone_number[<?= $address['address_id']; ?>]" value="<?= htmlspecialchars($address['telephone_number']); ?>">
                            </p>
                        </div>
                        <div class="button-container">
                            <input type="submit" name="address_update" value="住所情報を更新する">
                        </div>
                    </form>
                    <?php if($i !== 1): ?>
                    <form action="user_editdel.php" method="post">
                        <div class="button-container">
                            <input type="hidden" name="address_id" value="<?= htmlspecialchars($address['address_id']) ?>;">
                            <input type="submit" name="customer_del" value="住所情報を削除する">                            
                        </div>
                    </form>
                    <?php endif;
                    $i++;
                     ?>
        
        <hr>
    <?php endforeach; ?>

<?php else: ?>
    <p>登録された住所がありません。</p>
<?php endif; ?>
    </div>
    <?php include 'footer.php'; ?> <!-- フッターを挿入 -->
</body>

</html>