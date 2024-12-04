<!-- 共通部品フッター -->
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h3>CONTENTS</h3>
            <ul>
                <li><a href="list.php">商品一覧</a></li>
                <li><a href="top.php">トップページ</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>ACCOUNT</h3>
            <ul>
                <?php if (isset($_SESSION['customer'])): ?>
                    <li><a href="mypage.php">マイページ</a></li>
                    <li><a href="logout.php">ログアウト</a></li>
                <?php else: ?>
                    <li><a href="registration.php">新規会員登録</a></li>
                    <li><a href="login.php">ログイン</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="footer-section">
            <h3>SUPPORT</h3>
            <ul>
                <li><a href="contact.php">お問い合わせ</a></li>
                <li><a href="faq.php">よくある質問</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 酒蔵の里 All Rights Reserved.</p>
    </div>
</footer>
