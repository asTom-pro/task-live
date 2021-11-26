<?php
require_once('function.php');
debug('ヘッダーページです。');

$userInfo = (!empty($_SESSION['user_id'])) ? getUser($_SESSION['user_id']) : 1;
$userImg = (!empty($userInfo['profile_img'])) ? $userInfo['profile_img'] : 'img/user-sample.svg';

if(!empty($_GET)){
  if(!empty($_GET['search'])){
    $s = $_GET['search'];
  }
}

?>



<header class="header">

  <div class="container-header">

    <div class="header-left">
      <a href="index.php">
        <img src="img/logo.svg" alt="" class="header-logo">
      </a>
    </div>
    <div class="header-right">
      <ul class="nav">
        <li class="nav-list nav-list-search">
          <div class="js-toggle-search-container search-container">
            <form action="index.php" method="GET" class="form-search">
              <div class="divided">
                <span class="search-title d-lg-none">検索</span>
                <label for="">
                  <input type="text" name="search" class="input search-input" value="<?php if(!empty($s)){echo $s;} ?>">
                </label>
              </div>
              <input type="submit" class="btn btn-submit d-lg-none" value="検索">
            </form>
            <!-- 「学校で探す」機能を実装する -->
            <!-- <div class="divided d-lg-none">
              <a href="search.php">
                <div class="search-title">学校で探す<span class="icon-right-direction">&gt;</span>
              </div>
            </div> -->
            </a>
          </div>

          <span class="js-.toggle-search-form"><i class="fas fa-search btn-search"></i>
          </span>
        </li>
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
            <li class="nav-list nav-list-menu">
              <span class="js-toggle-header-link">
                <img src="<?php echo sanitize($userImg); ?>" alt="ユーザー画像" class="user-img">
              </span>
              <div class="js-show-header-link header-link-container header-link-container-pc d-sm-none">
                <div class="header-links">
                  <ul class="header-links">
                    <li class="header-link">
                      <a href="userpage.php">
                        マイページ
                      </a>
                    </li>
                    <li class="header-link">
                      <a href="logout.php">
                        ログアウト
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </li>
            <li class="nav-list">
              <a href="makeRoom.php" class="nav-active btn-make-room ph-btn-make-room"><i class="fas fa-pen-square fa-2x"></i></a>
            </li>
          <?php } ?>
        <?php } else { ?>
          <li class="nav-list">
            <span class="js-toggle-login-link toggle-login-link-ph">
              <img src="img/user-sample.svg" alt="ユーザー画像" class="user-img">
            </span>
          </li>
          <li class="nav-list d-sm-none">
            <a href="login.php">ログイン</a>
          </li>
          <li class="nav-list d-sm-none">
            <a href="signup.php" class="btn btn-signup">新規登録</a>
          </li>
          <li class="nav-list d-lg-none">
            <a href="makeRoom.php" class="nav-active btn-make-room ph-btn-make-room"><i class="fas fa-pen-square fa-2x"></i>
            </a>
          </li>
        <?php } ?>
      </ul>

    </div>
    <div class="js-show-login-link login-list-container">
      <ul class="header-links">
        <li class="nav-list">
          <a href="login.php">ログイン</a>
        </li>
        <li class="nav-list">
          <a href="signup.php" class="btn btn-signup">新規登録</a>
        </li>
      </ul>
    </div>
    <div class="js-show-header-link header-link-container header-link-container-ph">
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
    <!-- <div class="js-toggle-search-container search-container">
      <form action="index.php" method="GET" class="form-search form-search-ph d-lg-none">
        <div class="divided">
          <span class="search-title">検索</span>
          <label for="">
            <input type="text" name="search" class="search-input">
          </label>
        </div>
        <input type="submit" class="btn btn-submit" value="検索">
      </form>
      <div class="divided d-lg-none">
        <a href="search.php">
          <div class="search-title">学校で探す<span class="icon-right-direction">&gt;</span></div>
      </div>
      </a>
    </div> -->
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