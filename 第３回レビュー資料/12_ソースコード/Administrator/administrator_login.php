<?php
session_start();
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = '';
    $id = $_POST['ID'];
    $pass = $_POST['password'];

    $message = '';

    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;
        dbname=LAA1557125-sakagura;charset=utf8',
        'LAA1557125',
        'Pass2301386'
    );

    $sql = $pdo->prepare('select * from administrator where employee_id = ? and password = ?');
    $sql->execute([$id, $pass]);
    $administrator = $sql->fetch();

    if ($administrator) {

        $_SESSION['administrator'] = [
            'id' => $administrator['employee_id'],
            'name' => $administrator['employee_name'],
        ];

        header('Location: admini_top.php');
        exit;
    } else {
        $message = 'idまたはpasswordが違います。';
    }
    $pdo = null;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    <title>管理者ログイン</title>
</head>

<body>
    <div class="container">
        <h2>酒蔵の里 管理者ページ</h2>
        <hr>
        <form action="" method="POST">
            <h3>ログイン</h3>
            <label for="ID">社員ID</label>
            <input type="text" id="ID" name="ID" value="1" required>
            <label for="password">社員パスワード</label>
            <input type="password" id="password" name="password" value="okaken115" required>
            <p><?php echo htmlspecialchars($message); ?></p>
            <button type="submit" name="login">ログイン</button>
        </form>
        
    </div>
</body>


</html>