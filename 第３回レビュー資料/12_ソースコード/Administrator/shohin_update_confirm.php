<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // データの受け取り
    $shohin_name = htmlspecialchars($_POST['shohin_name'], ENT_QUOTES, 'UTF-8');
    $price = htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
    $shohin_description = htmlspecialchars($_POST['shohin_description'], ENT_QUOTES, 'UTF-8');
    $shohin_id = $_POST['shohin_id'];

    // 画像の処理
    $images = [];
    if (isset($_FILES['shohin_images']) && $_FILES['shohin_images']['error'][0] === UPLOAD_ERR_OK) {
        foreach ($_FILES['shohin_images']['tmp_name'] as $key => $tmp_name) {
            $originalName = basename($_FILES['shohin_images']['name'][$key]);
            $uploadDir = '../Sakaguranosato/upload/';
            $uploadPath = $uploadDir . $originalName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // 同名ファイルが存在する場合に名前を変更する
            $counter = 1;
            while (file_exists($uploadPath)) {
                $fileInfo = pathinfo($originalName);
                $newFileName = $fileInfo['filename'] . '-' . $counter . '.' . $fileInfo['extension'];
                $uploadPath = $uploadDir . $newFileName;
                $counter++;
            }

            // ファイルを保存
            if (move_uploaded_file($tmp_name, $uploadPath)) {
                $images[] = basename($uploadPath); // 保存後のファイル名を配列に追加
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規商品登録確認</title>
    <link rel="stylesheet" href="./css/shohin_insert_confirm.css">
</head>

<body>
    <!-- タイトル部分 -->
    <h2>酒蔵の里 新規商品登録</h2>

    <!-- ヘッダー部分 -->
    <header>
        <p><a href="admini_top.php">戻る</a></p>
        <p>ログイン者: <?php echo htmlspecialchars($_SESSION['administrator']['name']); ?> さん</p>
        <div class="header-links">
            <a href="administrator_logout.php">ログアウト</a>
        </div>
    </header>
    <hr>
    <form action="shohin_updateTodb.php" method="post">
        <div>
            <label>商品名</label>
            <span><?php echo $shohin_name; ?></span>
            <input type="hidden" name="shohin_name" value="<?php echo $name; ?>">
        </div>
        <div>
            <label>価格 (税込み)</label>
            <span><?php echo $price; ?> 円</span>
            <input type="hidden" name="price" value="<?php echo $price; ?>">
        </div>
        <div>
            <label>商品画像</label>
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $image): ?>
                    <div>
                        <img src="../Sakaguranosato/upload/<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>" alt="商品画像" style="max-width: 300px; max-height: 300px;">
                        <input type="hidden" name="images[]" value="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>画像がアップロードされていません。</p>
            <?php endif; ?>

        </div>
        <div>
            <label>商品説明</label>
            <p><?php echo nl2br($shohin_description); ?></p>
            <input type="hidden" name="shohin_description" value="<?php echo $shohin_description; ?>">
        </div>
        <button type="submit">商品を登録する</button>
    </form>
</body>

</html>