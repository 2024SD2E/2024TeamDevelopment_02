<!-- 新規会員登録 -->
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/registration.css">
    <title>新規会員登録</title>
</head>
<body>
    <div class="registration">
        <form action="registration_conf.php" method="post">
            <h1>新規会員登録</h1>
            <p><label for="name" >お名前 <span class="mark">必須</span></label></p>
            <p><input type="text" name="name" placeholder="例) 田中太郎" class="f1" required></p>
            <p><label for="birthdate">生年月日 <span class="mark">必須</span></label></p>
            <p><input type="date" name="ymd" value="2024-01-01" required></p>
            <p><label for="birthdate">郵便番号 <span class="mark">必須</span></label></p>
            <p><input type="text" name="post_code" id="f4" placeholder="ハイフンなしで入力してください" required></p>
            <p><label for="address">住所 <span class="mark">必須</span></label></p>
            <p><input type="text" name="address" class="f1" required></p>
            <p><label for="mail">メールアドレス <span class="mark">必須</span></label></p>
            <p><input type="text" name="email" id="email" class="f1" required></p>
            <span id="emailError" style="color: red; display: none;">無効なメールアドレスです。</span>
            <p><label for="password">パスワード <span class="mark">必須</span></label></p> 
            <p><input type="password" name="password"  class="f1" required></p>
            <p><label for="birthdate">電話番号 <span class="mark">必須</span></label></p> 
            <p><input type="tel" name="telephone_number" placeholder="09012345678 ハイフンなしで入力"  class="f1" required></p>
            <p><label for="nickname">ニックネーム <span class="mark2"></span></label></p> 
            <p><input type="text" name="nickname" class="f1"></p>
            <p id="nickname_ex">※ニックネームはレビューを書くときに使用します</p>
            <p class="button"><input type="submit" value="確認" id="submit"></p>
        </form>
    </div>
    <?php include 'footer.php'; ?>
    <script>
    document.getElementById('email').addEventListener('input', function () {
      const emailInput = this;
      const emailError = document.getElementById('emailError');

      // メールアドレスの正規表現
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      // 入力値が正しいかどうかをチェック
      if (emailRegex.test(emailInput.value)) {
        emailInput.classList.remove('invalid');
        emailInput.classList.add('valid');
        emailError.style.display = 'none';
      } else {
        emailInput.classList.remove('valid');
        emailInput.classList.add('invalid');
        emailError.style.display = 'inline';
      }
    });

    // フォーム送信時のチェック
    document.getElementById('emailForm').addEventListener('submit', function (e) {
      const emailInput = document.getElementById('email');
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      if (!emailRegex.test(emailInput.value)) {
        e.preventDefault(); // 送信をキャンセル
        alert('正しいメールアドレスを入力してください。');
      }
    });
  </script>
</body>
</html>