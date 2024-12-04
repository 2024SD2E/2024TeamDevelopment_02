<?php session_start();
ob_start();
?>
<div class="navbar">
  <!-- ロゴ -->
  <div class="logo">
    <a href="top.php"><img src="./img/top.png" alt="酒蔵の里"></a>
  </div>

  <!-- ハンバーガーメニュー用のチェックボックス -->
  <input type="checkbox" id="menu-toggle">
  <label for="menu-toggle" class="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </label>

  <!-- ナビゲーションメニュー -->
  <ul class="nav">
    <!-- 検索バー -->
    <li class="nav-item search-bar">
      <form action="search.php" method="post" class="headerform">
        <input type="text" name="search" class="search-input" placeholder="検索">
        <button type="submit" class="search-button"><img src="./img/search_16.png" height="16px" alt="検索"></button>
      </form>
    </li>

    <!-- ユーザーメニュー -->
    <li class="nav-item user-menu">
      <a href="#"><img src="./img/user.png" height="12px" alt="ユーザ"></a>
      <div class="dropdown-content">
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
    </li>

    <!-- カート -->
    <li class="nav-item cart">
      <a href="cart.php"><img src="./img/cart.png" height="12px" alt="カート"></a>
    </li>
  </ul>

  <!-- ハンバーガー用のメニュー -->
  <div class="hamburger-menu">
    <ul>
      <li>
        <form action="search.php" method="post" class="headerform">
          <input type="text" name="search" class="search-input" placeholder="検索">
          <button type="submit" class="search-button"><img src="./img/search_16.png" height="16px" alt="検索"></button>
        </form>
      </li>
      <li id="mypage">マイページ</li>
      <li>
        <ul>
          <?php if (isset($_SESSION['customer'])): ?>
            <li><a href="mypage.php">マイページ</a></li>
            <li><a href="logout.php">ログアウト</a></li>
          <?php else: ?>
            <li><a href="registration.php">新規会員登録</a></li>
            <li><a href="login.php">ログイン</a></li>
          <?php endif; ?>
        </ul>
      </li>
      <li id="cart">カート情報</li>
      <li id="cartlink"><a href="cart.php">カート</a></li>
    </ul>
  </div>
</div>
