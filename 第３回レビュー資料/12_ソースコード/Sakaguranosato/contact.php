<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/contact.css" />
    <title>お問い合わせ</title>
</head>
<body>
  <div class="contact">
    <form action="contact_submit.php" method="post">
      <h1>お問い合わせ</h1>

          <p><label for="name">お名前</label>
          <input type="text" name="name" size="40" required></p>

          <p><label for="tel">電話番号</label>
          <input type="text" name="tel" size="40" required></p>

          <p><label for="email">メールアドレス</label>
          <input type="text" name="email" size="40" required></p>

          <p><label for="contact">お問い合わせ内容</label>
          <textarea name="contact"cols="70" rows="20" required></textarea></p>

          <p class="button"><input type="submit" value="登録" id="submit"></p>
    </form>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>
