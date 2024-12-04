<?php
session_start();

try {
    // データベース接続設定
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557125-sakagura;charset=utf8',
        'LAA1557125',
        'Pass2301386',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // エラーを例外として処理
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // デフォルトのフェッチモード
        ]
    );

    // POSTデータの取得
    $shohin_id = $_POST['shohin_id'];
    $customer_id = $_SESSION['customer']['customer_id']; // セッションから取得する場合も考慮
    $nickname = trim($_POST['nickname']); // ニックネームをトリム
    $rating = (int)$_POST['rating']; // 数値型にキャスト
    $comment = trim($_POST['comment']); // コメントをトリム
    $review_date = date('Y-m-d'); // 現在の日付を取得

    // 入力値のバリデーション
    if (empty($nickname) || $rating < 1 || $rating > 5 || empty($comment)) {
        throw new Exception('入力内容に不備があります。すべての項目を正しく入力してください。');
    }

    // SQLの準備と実行
    $sql = $pdo->prepare(
        'INSERT INTO review (shohin_id, customer_id, nickname, comment, review_date, rating)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $sql->execute([$shohin_id, $customer_id, $nickname, $comment, $review_date, $rating]);

    // レビュー投稿完了ページへリダイレクト
    header('Location: review_submit.php');
    exit(); // 追加: リダイレクト後、処理を終了

} catch (PDOException $e) {
    // データベースエラーが発生した場合
    echo 'データベースエラー: ' . $e->getMessage();
    exit();
} catch (Exception $e) {
    // その他のエラー
    echo 'エラー: ' . $e->getMessage();
    exit();
}
?>
