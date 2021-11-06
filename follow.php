<?php
require('function.php');

if (!empty($_SESSION['user_id'])) {
  $u_id = $_SESSION['user_id'];
}
if (!empty($_GET['u'])) {
  $u_id = $_GET['u'];
}

$followingUser = getFollowingList($u_id);

$isMyPage = false;
if (!empty($_SESSION['user_id']) && $u_id == $_SESSION['user_id']) {
  $isMyPage = true;
}

$title = 'フォロー';
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require('head.php'); ?>
  <link href="css/index.css" rel="stylesheet">
  <link href="css/mypage.css" rel="stylesheet">
  <link href="css/follow.css" rel="stylesheet">
  <link href="css/subbar-profile.css" rel="stylesheet">

  <!-- リロード時のtransition対策 -->
  <script>
    console.log("");
  </script>
</head>

<body class="body">
  <?php require('header.php'); ?>
  <div id="modal"></div>
  <main class="main">
    <div class="container">
      <div class="main-bar">
        <div class="follow-container">
          <div class="follow-switch">
            <a class="btn btn-follow btn-active" href="follow.php<?php if (!$isMyPage) {
                                                                    echo '?u=' . sanitize($u_id);
                                                                  } ?>">フォロー</a>
            <a class="btn btn-follow" href="follower.php<?php if (!$isMyPage) {
                                                          echo '?u=' . sanitize($u_id);
                                                        } ?>">フォロワー</a>
          </div>
          <div class="follow-members">
            <?php foreach ($followingUser as $key => $val) {
              $followUserInfo = getUser($val['follow_id']);
              $followUserId = (!empty($followUserInfo['id'])) ? $followUserInfo['id'] : '';
              $followUserName = (!empty($followUserInfo['user_name'])) ? $followUserInfo['user_name'] : '';
              $followUserImg = (!empty($followUserInfo['profile_img'])) ? $followUserInfo['profile_img'] : 'img/user-sample.svg';
              $followUserText = (!empty($followUserInfo['profile_text'])) ? $followUserInfo['profile_text'] : '';

              $isMyInfo = false;
              if (!empty($_SESSION['user_id']) && $followUserId == $_SESSION['user_id']) {
                $isMyInfo = true;
              }
              echo '
              <div class="follow-member">
              <a href ="userpage.php?u=' . sanitize($followUserId) . '">
              <div class="member-info">
                <div class="info-left">
                  <img src="' . sanitize($followUserImg) . '" alt="" class="user-img">
                </div>
                <div class="info-right">
                  <p class="user-name">' . sanitize($followUserName) . '</p>
                  <p class="user-description">' . sanitize($followUserText) . '</p>
                </div>
              </div>
              </a>';
              if (!$isMyInfo) {
                echo '
                <button type="button" class="btn btn-follow ';
                if (!isGuest()) {
                  echo 'btn-active';
                };
                echo '" data-userid="';
                if (!empty($followUserId)) {
                  echo sanitize($followUserId);
                }
                echo '">フォロー</button>';
              }
              echo '
            </div>
              ';
            } ?>
          </div>
        </div>
      </div>
      <div class="sub-bar">
        <?php require('subbar-profile.php'); ?>
      </div>
    </div>
  </main>
  <?php require('footer.php'); ?>
  <script src="js/follow.js"></script>
</body>

</html>