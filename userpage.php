<?php
require('function.php');

debugLogStart();
if (!empty($_SESSION) && !empty($_SESSION['user_id'])) {
  $u_id = $_SESSION['user_id'];
} else {
  // ゲストユーザーに設定
  $u_id = 1;
}
if (!empty($_GET['u'])) {
  $u_id = $_GET['u'];
}
// ゲストユーザーの場合トップページに遷移
if ($u_id == 1 || empty($u_id)) {
  header('Location:index.php');
  exit();
}
$isMyPageFlg = false;
if (!empty($_SESSION['user_id']) && $u_id == $_SESSION['user_id']) {
  $isMyPageFlg = true;
}

if ($isMyPageFlg) {
  debug('ログインユーザーのユーザーページです');
  $dbUserEndTaskInfo = getEndTask($u_id);
}

$dbUserInfo = getUser($u_id);
$dbUserCreatedRoomInfo = getRoomsInfo($u_id);
$formedDbUserCreatedRoomId = array();
if (!empty($dbUserCreatedRoomInfo)) {
  foreach ($dbUserCreatedRoomInfo as $key => $val) {
    $formedDbUserCreatedRoomId[] = $val['room_id'];
  }
}
$dbUserCreatedRoomNum = getRoomsNum($u_id);
$joinedRoomId = getJoinedRoomId($u_id);
if (!empty($joinedRoomId)) {
  foreach ($joinedRoomId as $key => $val) {
    $formedJoinedRoomId[] = $val['room_id'];
  }
  $UniqueJoinedRoomId = array_diff($formedJoinedRoomId, $formedDbUserCreatedRoomId);
  $UniqueJoinedRoomNum =  count($UniqueJoinedRoomId);
} else {
  $UniqueJoinedRoomId = array();
  $UniqueJoinedRoomNum =  0;
}

$total_room_time = 0;
foreach ($UniqueJoinedRoomId as $key => $val) {
  $joinedRoomInfo = getRoomOne($val);
  if (!empty($joinedRoomInfo)) {
    $total_room_time += (round($joinedRoomInfo['room_time_limit'] / 3600, 2));
  }
}

$isMyPageFlg = false;
if (!empty($_SESSION['user_id']) && $u_id == $_SESSION['user_id']) {
  $isMyPageFlg = true;
}

$title = 'ユーザーページ';
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require('head.php'); ?>
  <link href="css/index.css" rel="stylesheet">
  <link href="css/mypage.css" rel="stylesheet">
  <link href="css/subbar-profile.css" rel="stylesheet">
  <link href="css/clock.css" rel="stylesheet">

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
      <?php if ($isMyPageFlg) : ?>
        <div class="task-container">
          <table class="task-table">
            <caption class="table-title js-open-table">メモ
              <i class="fas fa-chevron-down is-active icon-arrow js-closing-table"></i>
              <i class="fas fa-chevron-up icon-arrow  js-opening-table"></i>
            </caption>
            <tbody>
                <?php if ($isMyPageFlg && !empty($dbUserEndTaskInfo[0])) : ?>
                <?php
                foreach ($dbUserEndTaskInfo as $key => $val) {
                  $endTask = $val['ended_task'];
                  $taskEndTime = strtotime($val['create_date']);
                  echo '
              <tr>
                <td class="end-task-date">' . sanitize(date("Y年m月d日", $taskEndTime)) . '</td>
                <td class="end-task-detail">' . sanitize($endTask) . '</td>
              </tr>
                ';
                }
                ?>
                <?php else: ?>
                  <tr>
                    <td>現在メモはありません。</td>
                  </tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        <p class="total-study-time">総学習時間<br>
        <span class="total-time"><?php
                                                                  foreach ($dbUserCreatedRoomInfo as $key => $val) {
                                                                    $total_room_time += (round($val['room_time_limit'] / 3600, 2));
                                                                    debug(print_r($total_room_time, true));
                                                                  }
                                                                  echo sanitize($total_room_time); ?></span><span class="total-time-unit">時間</span>
        </p>
        <div class="created-room-container room-container">
          <p class="total-room">今まで作成した部屋<span class="total-room-num"><?php if (!empty($dbUserCreatedRoomNum)) {
                                                                                                                                                                                                                    echo sanitize($dbUserCreatedRoomNum);
                                                                                                                                                                                                                  } else {
                                                                                                                                                                                                                    echo '0';
                                                                                                                                                                                                                  } ?>部屋</span></p>
          <div class="created-rooms">
            <?php foreach ($dbUserCreatedRoomInfo as $key => $val) {
              $createdRoomInfo = getRoomOne($val['room_id']);
              $roomName = $val['room_name'];
              $roomUserName = $dbUserInfo['user_name'];
              $roomUserImg =  (!empty($dbUserInfo['profile_img'])) ? $dbUserInfo['profile_img'] : 'img/user-sample.svg';
              $joinedUserNum = getRoomUserNum($val['room_id']);
              $formed_date = strtotime($createdRoomInfo['create_date']);
              $limit_time_sec = (idate('U', $formed_date) + $createdRoomInfo['room_time_limit']) - time();
              if ($limit_time_sec < 0) {
                $limit_time_sec = 0;
              }
              $limit_time_min = floor($limit_time_sec / 60);
              $roomMaxTime = floor($createdRoomInfo['room_time_limit'] / 60);
              $limit_time_per =   (90 + 360 * (1 - ($limit_time_sec / ($roomMaxTime * 60)))) . 'deg';

              echo '
              <div class="room">
              <div class="room-info">
                <h2 class="room-title">' . sanitize($roomName) . '</h2>
                <div class="room-member room-info-list">
                  <div class="member-container">
                    最大<span class="member-num">' . sanitize($joinedUserNum) . '</span>人
                  </div>
                </div>
                <div class="room-user-info room-info-list">
                  <img src="img/favorite.png" alt="" class="no-favorite favorite">
                  <img src="' . sanitize($roomUserImg) . '" alt="" class="user-img">
                  <span class="user-name">' . sanitize($roomUserName) . '</span>
                </div>
              </div>
              <div class="room-time">
                <div class="clock">
                  <div class="clock-hand" style="transform:rotate(' . sanitize($limit_time_per) . '); animation:rotation-s ' . sanitize($limit_time_sec) . 's linear 1 forwards;"></div>
                </div>
                <div class="remain-time"><span class="remain-time-num-m">' . sanitize($limit_time_min) . '</span>/' . (sanitize($val['room_time_limit']) / 60) . '分</div>
              </div>
            </div>
              ';
            } ?>
          </div>
        </div>
        <div class="joined-room-container room-container">
          <p class="total-room" style="font-size: 20px;">今まで参加した部屋<span class="total-room-num" style="font-size: 20px; border:1px solid black;padding:5px 10px; float:right; overflow:hidden;"><?php echo sanitize($UniqueJoinedRoomNum); ?>部屋</span></p>
          <div class="joined-rooms">
            <?php
            foreach ($UniqueJoinedRoomId as $key => $val) {
              $joinedRoomInfo = getRoomOne($val);
              if (!empty($joinedRoomInfo)) {
                $joinedRoomTimeLimitSec = $joinedRoomInfo['room_time_limit'];
                $joinedRoomCreatedUserInfo = getUser($joinedRoomInfo['user_id']);
                $joinedRoomUserName = $joinedRoomCreatedUserInfo['user_name'];
                $joinedRoomUserImg = (!empty($joinedRoomCreatedUserInfo['profile_img'])) ? $joinedRoomCreatedUserInfo['profile_img'] : 'img/user-sample.svg';
                $joinedRoomName = $joinedRoomInfo['room_name'];
                $joinedRoomUserNum = getRoomUserNum($val);
                $formed_date = strtotime($joinedRoomInfo['create_date']);
                $limit_time_sec = (idate('U', $formed_date) + $joinedRoomInfo['room_time_limit']) - time();
                if ($limit_time_sec < 0) {
                  $limit_time_sec = 0;
                }
                $limit_time_min = floor($limit_time_sec / 60);
                $room_time_limit_per =  $limit_time_sec / $joinedRoomInfo['room_time_limit'];
                $roomMaxTime = floor($joinedRoomTimeLimitSec / 60);
                $limit_time_per =   (90 + 360 * (1 - ($limit_time_sec / ($roomMaxTime * 60)))) . 'deg';
                echo '
                <div class="room">
                <div class="room-info">
                  <h2 class="room-title">' . sanitize($joinedRoomName) . '</h2>
                  <div class="room-member room-info-list">
                    <div class="member-container">
                      最大<span class="member-num">' . sanitize($joinedRoomUserNum) . '</span>人
                    </div>
                  </div>
                  <div class="room-user-info room-info-list">
                    <img src="img/favorite.png" alt="" class="no-favorite favorite">
                    <img src="' . sanitize($joinedRoomUserImg) . '" alt="" class="user-img">
                    <span class="user-name">' . sanitize($joinedRoomUserName) . '</span>
                  </div>
                </div>
                <div class="room-time">
                  <div class="clock">
                  <div class="clock-hand" style="transform:rotate(' . sanitize($limit_time_per) . '); animation:rotation-s ' . sanitize($limit_time_sec) . 's linear 1 forwards;"></div>
                  </div>
                  <div class="remain-time"><span class="remain-time-num-m">' . sanitize($limit_time_min) . '</span>/' . (sanitize($joinedRoomTimeLimitSec) / 60) . '分</div>
                </div>
              </div>
                ';
              }
            }
            ?>
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