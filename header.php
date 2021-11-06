<?php
require_once('function.php');

$userInfo = (!empty($_SESSION['user_id'])) ? getUser($_SESSION['user_id']) : 1;
$userImg = (!empty($userInfo['profile_img'])) ? $userInfo['profile_img'] : '';

?>



<header class="header">

  <div class="header-left">
    <a href="index.php">
      <img src="img/logo.svg" alt="" class="header-logo">
    </a>
  </div>
  <div class="header-right">
    <ul class="nav">
      <li class="nav-list">
        <a href="makeRoom.php" class="nav-active btn-make-room">部屋を作る</a>
      </li>
      <?php if (!empty($_SESSION['login_limit'])) { ?>
        <?php if (!isLogin()) { ?>
          <li class="nav-list">
            <a href="login.php">ログイン</a>
          </li>
          <li class="nav-list">
            <a href="signup.php" class="btn btn-signup">新規登録</a>
          </li>
          <li class="nav-list">
            <a href="makeRoom.php" class="nav-active btn-make-room ph-btn-make-room">
              <i class="fas fa-pen-square fa-2x"></i>
            </a>
          </li>
        <?php } else { ?>
          <li class="nav-list">
            <span class="js-toggle-search-form"><i class="fas fa-search fa-2x btn-search"></i>
            </span>
          </li>
          <li class="nav-list">
            <span class="js-toggle-header-link">
              <img src="<?php echo sanitize($userImg); ?>" alt="ユーザー画像" class="user-img">
            </span>
          </li>
          <li class="nav-list">
            <a href="makeRoom.php" class="nav-active btn-make-room ph-btn-make-room"><i class="fas fa-pen-square fa-2x"></i></a>
          </li>
        <?php } ?>
      <?php } else { ?>
        <li class="nav-list">
          <a href="login.php">ログイン</a>
        </li>
        <li class="nav-list">
          <a href="signup.php" class="btn btn-signup">新規登録</a>
        </li>
        <li class="nav-list">
          <a href="makeRoom.php" class="nav-active btn-make-room ph-btn-make-room"><i class="fas fa-pen-square fa-2x"></i>
          </a>
        </li>
      <?php } ?>
    </ul>

  </div>
  <div class="js-show-header-link header-link-container">
    <ul class="header-links">
      <li class="nav-list">
        <a href="userpage.php">
          マイページ
        </a>
      </li>
      <li class="nav-list">
        <a href="logout.php">
          ログアウト
        </a>
      </li>
    </ul>
  </div>
  <div class="js-toggle-search-container search-container">
    <form action="GET" class="form-search">
      <div class="divided">
        <span class="search-title">検索</span>
        <label for="">
          <input type="text" class="search-input">
        </label>
      </div>
      <input type="submit" class="btn btn-submit" value="検索">
    </form>
    <div class="divided">
      <a href="search.php">
        <div class="search-title">学校で探す<span class="icon-right-direction">&gt;</span></div>
      </div>
      </a>

  </div>
</header>
<?php if (!empty($_SESSION['success_msg'])) { ?>
    <div id="msg-container">
      <div id="success-msg"><i class="far fa-check-circle"></i><?php echo sanitize(getSessionOnce('success_msg')); ?><div class="msg-bg-color"></div>
      </div>
    </div>
<?php } ?>
<div id="js-msg-container">
  <div id="success-msg">
    <i class="far fa-check-circle"></i>
    <span class="msg"></span>
    <div class="msg-bg-color"></div>
  </div>
</div>
