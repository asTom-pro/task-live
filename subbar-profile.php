<?php
$isMypage = false;
if (!empty($_SESSION['user_id'])) {
  $u_id = $_SESSION['user_id'];
}
if (!empty($_GET['u'])) {
  $u_id = $_GET['u'];
}
if (!empty($_SESSION['user_id']) && $u_id == $_SESSION['user_id']) {
  $isMypage = true;
}
$dbUserInfo = getUser($u_id);

$followedUser = getFollowerList($u_id);
$followingUser = getFollowingList($u_id);
$followedUserNum = count($followedUser);
$followingUserNum = count($followingUser);
$followedUserId = array();
foreach ($followedUser as $key => $val) {
  $followedUserId[] = $val['user_id'];
}
debug('followedUserId' . print_r($followedUserId, true));
$followingUserId = array();
foreach ($followingUser as $key => $val) {
  $followingUserId[] = $val['follow_id'];
}
debug('followingUserId' . print_r($followingUserId, true));


?>
<div class="sub-bar-list sub-bar-profile">
  <img src="<?php if (!empty($dbUserInfo['profile_img'])) {
              echo sanitize($dbUserInfo['profile_img']);
            } else {
              echo 'img/user-sample.svg';
            } ?>" alt="" class="user-img">
  <p class="user-name"><?php if (!empty($dbUserInfo['user_name'])) {
                          echo sanitize($dbUserInfo['user_name']);
                        } ?></p>
  <div class="follow">
    <a href="follow.php<?php if (empty($_SESSION['user_id']) || $_SESSION['user_id'] != $u_id) {
                          echo '?u=' . sanitize($u_id);
                        } ?>">フォロー<span class="follow-num"><?php echo sanitize($followingUserNum); ?></span></a>
    <a href="follower.php<?php if (!$isMypage) {
                            echo '?u=' . sanitize($u_id);
                          } ?>">フォロワー<span class="follower-num"><?php echo sanitize($followedUserNum); ?></span></a>
  </div>
  <div class="user-description">
    <p class="description"><?php if (!empty($dbUserInfo['profile_text'])) {
                              echo sanitize($dbUserInfo['profile_text']);
                            } ?></p>
  </div>
  <div class="btn-container">
    <?php if ($isMypage) {
      echo '
      <a class="btn btn-profile" href="profile.php">プロフィール編集</a>
      <ul class="sub-bar-links">
      <li class="sub-bar-link">
        <a href="withdrow-confirm.php">退会</a>
      </li>
    </ul>  
      ';
    } else {
      echo '
      <div class="follow-members">
      <button class="btn btn-follow ';
      if (!empty($_SESSION['user_id']) && in_array($_SESSION['user_id'], $followedUserId)) {
        echo 'btn-active';
      }
      echo '" data-userid="';
      if (!empty($dbUserInfo['id'])) {
        echo sanitize($dbUserInfo['id']);
      }
      echo '">フォロー</button>
      </div>
      ';
    }
    ?>
  </div>
</div>