<!-- 新規会員登録確認 -->
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/registration.css">
    <title>新規会員登録確認</title>
</head>
<body>
    <div class="registration">
        <form action="registration_subTodb.php" method="post">
            <h1>登録内容をご確認ください</h1>
            <p id="correction">訂正する場合は、この画面で書き直すことができます</p>
            <p id="correction2">間違えがなければ、下の登録ボタンを押下してください</p>
            <p><label for="name" >お名前</label></p>
            <p><input type="text" name="name" class="f1" value=<?= htmlspecialchars($_POST['name']) ?>></p>
            <p><label for="birthdate">生年月日</label></p>
            <p><input type="date" name="ymd" value=<?= $_POST['ymd'] ?> required></p>
            <p><label for="birthdate">郵便番号</label></p> 
            <p><input type="text" name="post_code" id="f4" placeholder="ハイフンなしで入力してください" value=<?= htmlspecialchars($_POST['post_code']) ?>></p>
            <p><label for="address">住所</label></p> 
            <p><input type="text" name="address" class="f1" value=<?= htmlspecialchars($_POST['address']) ?>></p>
            <p><label for="mail">メールアドレス</label></p> 
            <p><input type="text" name="email" class="f1" value=<?= htmlspecialchars($_POST['email']) ?>></p>
            <p><label for="password">パスワード</label></p> 
            <p><input type="password" name="password"  class="f1" value=<?= htmlspecialchars($_POST['password']) ?>></p>
            <p><label for="birthdate">電話番号</label></p> 
            <p><input type="tel" name="telephone_number" placeholder="09012345678 ハイフンなしで入力"  class="f1" value=<?= htmlspecialchars($_POST['telephone_number']) ?>></p>
            <p><label for="nickname">ニックネーム</label></p> 
            <p><input type="text" name="nickname" class="f1" value=<?=htmlspecialchars($_POST['nickname'])?>></p>
            <p class="button"><input type="submit" value="登録" id="submit"></p>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>