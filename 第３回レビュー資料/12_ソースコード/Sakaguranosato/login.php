<?php include 'header.php'; ?>
<?php
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 前のページのURLを取得
        // $previousUrl = $_SERVER['HTTP_REFERER'] ?? 'default_page.php';
        $email = $_POST['email'];
        $pass = $_POST['password'];

        $pdo = new PDO(
            'mysql:host=mysql311.phy.lolipop.lan;
            dbname=LAA1557125-sakagura;charset=utf8',
            'LAA1557125',
            'Pass2301386'
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = $pdo->prepare('SELECT * FROM customer WHERE email = ? AND password = ?');
        $sql->execute([$email, $pass]);
        $customer = $sql->fetch();

        $message = '';
        if ($customer) {
            $_SESSION['customer'] = [
                'customer_id' => $customer['customer_id'],
                'customer_name' => $customer['customer_name'],
                'email' => $customer['email'],
                'password' => $customer['password'],
                'nickname' => $customer['nickname'],
            ];
            header("Location: top.php");
            exit();
        } else {
            $message = 'IDまたはパスワードが違います。</p>';
        }
    }
} catch (PDOException $e) {
    $message = 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/user_login.css">
    <title>ログイン</title>
</head>

<body>
    <div class="login-container">
        <h2 class="login-title">ログイン</h2>
        <form action="" method="post" class="login-form">
            <p class="user-login-label">メールアドレス</p>
            <input type="text" name="email" class="user-login-input" required>
            <p class="user-login-label">パスワード</p>
            <input type="password" name="password" class="user-login-input" required>
            <a href="registration.php" class="user-login-link">新規会員登録の方はこちら</a>
            <div class="message">
                <p><?php echo $message; ?></p>
            </div>

            <button type="submit" class="user-login-button">ログイン</button>
        </form>
    </div>
</body>
<?php include 'footer.php'; ?>

</html>