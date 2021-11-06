<?php
require('function.php');

if (!empty($_SESSION['user_id'])) {
  $u_id = $_SESSION['user_id'];
}
if (!empty($_GET['u'])) {
  $u_id = $_GET['u'];
}
$followedUser = getFollowerList($u_id);
$followedUserId = array();
foreach ($followedUser as $key => $val) {
  $followedUserId[] = $val['user_id'];
}
$followingUser = getFollowingList($u_id);
$followingUserId = array();
foreach ($followingUser as $key => $val) {
  $followingUserId[] = $val['follow_id'];
}

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
  <div id="modal"></div>
  <?php require('header.php'); ?>
  <main class="main">
    <div class="container">
      <div class="main-bar">
        <div class="follow-container">
          <div class="follow-switch">
            <a class="btn btn-follow" href="follow.php<?php if (!$isMyPage) {
                                                        echo '?u=' . sanitize($u_id);
                                                      } ?>">フォロー</a>
            <a class="btn btn-follow btn-active" href="follower.php<?php if (!$isMyPage) {
                                                                      echo '?u=' . sanitize($u_id);
                                                                    } ?>">フォロワー</a>
          </div>
          <div class="follow-members">
            <?php foreach ($followedUser as $key => $val) {
              $followUserInfo = getUser($val['user_id']);
              echo '
              <div class="follow-member">
              <a href ="userpage.php?u=' . sanitize($followUserInfo['id']) . '">
              <div class="member-info">
                <div class="info-left">
                  <img src="';
              if (!empty($followUserInfo['profile_img'])) {
                echo sanitize($followUserInfo['profile_img']);
              } else {
                echo 'img/user-sample.svg';
              }
              echo '" alt="" class="user-img">
                </div>
                <div class="info-right">
                  <p class="user-name">';
              if (!empty($followUserInfo['user_name'])) {
                echo sanitize($followUserInfo['user_name']);
              }
              echo '</p>
                  <p class="user-description">';
              if (!empty($followUserInfo['profile_text'])) {
                echo sanitize($followUserInfo['profile_text']);
              }
              echo '</p>
                </div>
              </div>
              </a>
              ';
              if ($followUserInfo['id'] != $_SESSION['user_id']) {
                echo '
                <button type="button" class="btn btn-follow ';
                if (in_array($followUserInfo['id'], $followingUserId)) {
                  echo 'btn-active';
                }
                echo '"data-userid="';
                if (!empty($followUserInfo['id'])) {
                  echo sanitize($followUserInfo['id']);
                }
                echo '"
                >フォロー</button>';
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