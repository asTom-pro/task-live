<?php
require('function.php');
debugLogStart();
if (!empty($_GET['logout'])) {
  $_SESSION['success_msg'] =  'ログアウトしました!';
  unset($_GET['logout']);
}
if (!empty($_SESSION['user_id'])) {
  $u_id = $_SESSION['user_id'];
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
}


$dbTags = getTags();
if (!empty($u_id) && $u_id != 1) {
  $dbMyOpenRoom = getMyOpenRoom($u_id);
}
$dbRoomsInfo = getRoomsInfo();

$tag = '';
$room_id_related_tag = array();
if (!empty($_GET)) {
  // タグ検索
  if(!empty($_GET['tag'])){
    $tag = $_GET['tag'];
    $tag_id = getTagId($tag);
  
    if (empty($tag_id)) {
      debug('不正なパラメータを取得したのでトップページに遷移します。');
      header('Location:index.php');
    }
    $room_id_related_tag[] = getRoomId($tag_id);
    $dbRoomsInfo = getRoomsInfoRelatedTag($room_id_related_tag);  
  }
  // 部屋名検索
  if(!empty($_GET['search'])){
    debug('部屋名検索します');
    $s = $_GET['search'];
    $dbRoomsInfo = getRoomsInfo('',$s);  
  }
}

if (empty($dbRoomsInfo[0])) {
  debug('不正なパラメータを取得したのでトップページに遷移します。');
  header('Location:index.php');
  exit();
}

$no_room_flg = false;
if (count($dbRoomsInfo) == 0) {
  $no_room_flg = true;
}
$title = '部屋一覧';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require('head.php'); ?>
  <link href="css/index.css" rel="stylesheet">
  <link href="css/clock.css" rel="stylesheet">
  <!-- リロード時のtransition対策 -->
  <script>
    console.log("");
  </script>
  <script src="js/roomUserCount.js"></script>
</head>

<body class="body">
  <?php require('header.php'); ?>
  <main class="main">
    <div class="container">
      <div class="main-bar">
        <div class="room-container">
          <?php if (!empty($dbMyOpenRoom)) {
            // start マイルーム
            $myOpenRoomUserId = $dbMyOpenRoom['user_id'];
            $dbRoomUserInfo =  getUser($myOpenRoomUserId);
            $roomUserId = $dbRoomUserInfo['id'];
            $roomUserName = $dbRoomUserInfo['user_name'];
            $roomUserName = $dbRoomUserInfo['profile_img'];
            $myOpenRoomId = $dbMyOpenRoom['room_id'];
            $myOpenRoomName = $dbMyOpenRoom['room_name'];
            $roomMaxTime = floor($dbMyOpenRoom['room_time_limit'] / 60);
            $formed_date = strtotime($dbMyOpenRoom['create_date']);
            $limit_time_sec = (idate('U', $formed_date) + $dbMyOpenRoom['room_time_limit']) - time();
            $limit_time_min = floor($limit_time_sec / 60);
            $isRoomTimeOut = false;
            if ($limit_time_sec < 0) {
              $isRoomTimeOut = true;
              $limit_time_sec =  0;
            }
            $endRoomUserNum = getRoomUserNum($dbMyOpenRoom['room_id']);
            $uri = dirname($_SERVER["SCRIPT_NAME"]).'/room.php?r_id='.$myOpenRoomId;
            $nowRoomUserNum = getLogs($uri);
            $roomUsernum = ($isRoomTimeOut) ? $endRoomUserNum : $nowRoomUserNum;
            ?>
            <div class="open-myroom">
              <p class="room-select-lead info-txt">作成した部屋がまだ開いています</p>
              <a href="room.php?r_id=<?php echo sanitize($myOpenRoomId); ?>">
              <div class="room">
                <div class="room-info">
                  <h2 class="room-title"><?php echo sanitize($myOpenRoomName); ?></h2>
                  <div class="room-member room-info-list">
                    <div class="member-container">
                      現在<span class="member-num"><?php echo sanitize($roomUsernum); ?></span>人
                    </div>
                  </div>
                  <div class="room-tag room-info-list">
                  <?php
                  $tags = getRoomTag($myOpenRoomId);
                  foreach ($tags as $key => $val) { 
                    if (!empty($tags)) { ?>
                      <object><a class="btn tag" href ="index.php?tag=<?php echo sanitize($val); ?>"><?php echo sanitize($val); ?></a></object>
                      <?php
                      }
                    } 
                    ?>
                    </div>
                    <div class="room-user-info room-info-list">
                      <img src="" alt="" class="favorite no-favorite">
                      <object>
                        <a href="userpage.php?u=<?php if (!empty($roomUserId) && $roomUserId != 1) { echo sanitize($roomUserId);}?>">
                        <img src="
                        <?php
                        if (!empty($dbRoomUserInfo['profile_img'])) {
                          echo sanitize($dbRoomUserInfo['profile_img']);
                        } else {
                          echo 'img/user-sample.svg';
                        }
                        ?>" alt="" class="user-img">
                        <span class="user-name">
                          <?php if (!empty($dbRoomUserInfo['user_name'])) {
                            echo sanitize($dbRoomUserInfo['user_name']);
                          } else {
                            echo '';
                          }
                          ?>
                        </span>
                      </a>
                    </object>
                  </div>
                </div>
                <div class="room-time">
                  <div class="clock">
                    <div class="clock-hand" style="<?php if (!empty($limit_time_sec)) {
                      $limit_time_per = (90+360*(1-($limit_time_sec/($roomMaxTime*60)))).'deg';
                    }
                    ?>
                    transform: rotate(<?php echo sanitize($limit_time_per);?>);
                    animation:rotation-s <?php echo sanitize($limit_time_sec); ?>s linear 1 forwards;"></div>
                              </div>
                              <div class="remain-time">
                                <span class="remain-time-num-m"><?php if (!empty($limit_time_min)) { echo sanitize($limit_time_min); } else {echo '0';} ?></span>/
                                <?php if (!empty($roomMaxTime)) { echo sanitize($roomMaxTime); }?>分
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                      <!-- end マイルーム -->
          <?php } ?>
          <?php if(!empty($s)){
            echo '<p class="room-select-lead text-muted">検索：'.$s.'</p>';
          } ?>
          <p class="room-select-lead">参加する部屋を選択</p>
          <?php if ($no_room_flg) { ?>
            <div class="no-room">
            <p>現在開いている部屋がありません。部屋を作成しましょう！</p>
            <a href="makeRoom.php" class="btn btn-make-room">作成する</a>
          </div>
          <?php } ?>
          <?php
          foreach ($dbRoomsInfo as $key => $val) {
            if (!empty($val['room_id'])) {
              $r_id = $val['room_id'];
              $tags = getRoomTag($r_id);
              $roomMaxTime = floor($val['room_time_limit'] / 60);
              $formed_date = strtotime($val['create_date']);
              $limit_time_sec = (idate('U', $formed_date) + $val['room_time_limit']) - time();
              $limit_time_min = floor($limit_time_sec / 60);
              $isRoomTimeOut = false;
              if ($limit_time_sec < 0) {
                $isRoomTimeOut = true;
                $limit_time_sec =  0;
              }
              if ($isRoomTimeOut) {
                $limit_time_min = '0';
                debug('isRoomTimeOutがtrue');
              } else {
                $uri = dirname($_SERVER["SCRIPT_NAME"]) . '/room.php?r_id=' . $val['room_id'];
                $nowRoomUserNum = getLogs($uri);
                debug('isRoomTimeOutがfalse');
              }
              $roomUser =  getUser($val['user_id']);
              $endRoomUserNum = getRoomUserNum($val['room_id']);
              ?>
              <a href="room.php?r_id=<?php if (!empty($val['room_id'])) {echo sanitize($val['room_id']);}?>" class="room-link">
              <div class="room">
                <div class="room-info">
                  <h2 class="room-title"><?php echo sanitize($val['room_name']);?></h2>
                  <div class="room-member room-info-list">
                    <div class="member-container">
                      <?php
                      if ($isRoomTimeOut) {echo '終了';} else { echo '現在';}?>
                  <span class="member-num">
                    <?php
                    if ($isRoomTimeOut) {
                      if (!empty($endRoomUserNum)) {echo sanitize($endRoomUserNum);}} else {echo sanitize($nowRoomUserNum);}
                      ?>
                      </span>人
                    </div>
                  </div>
                  <div class="room-tag room-info-list">
                    <?php foreach ($tags as $key => $val_tag) {
                      if (!empty($tags)) {
                    ?>
                  <object>
                    <a class="btn tag" href ="index.php?tag=<?php echo sanitize($val_tag);?>"><?php echo sanitize($val_tag) ?></a>
                  </object>
                  <?php
                }
              }
              ?>
              </div>
              <div class="room-user-info room-info-list">
                <?php
              if (!empty($followingUserId)) {
                if (in_array($val['user_id'], $followingUserId)) {
                  ?>
                  <img src="img/favorite.png" alt="" class="favorite">
                  <?php } else { ?>
                  <img src="" alt="" class="favorite no-favorite">
                  <?php
                }
              }
              ?>
                <object>
                  <a href="userpage.php?u=<?php if (!empty($roomUser['id']) && $roomUser['id'] != 1) { echo sanitize($roomUser['id']);}?>">
                  <img src="<?php if (!empty($roomUser['profile_img'])) { echo sanitize($roomUser['profile_img']); } else { echo 'img/user-sample.svg';} ?>" alt="" class="user-img">
                  <span class="user-name">
                    <?php
                    if (!empty($roomUser['user_name'])) {
                      echo sanitize($roomUser['user_name']);
                    } else {
                      echo '名称未設定';
                    }
                    ?>
                    </span>
                  </a>
                </object>
              </div>
            </div>
            <div class="room-time">
              <div class="clock">
                <div class="clock-hand" style="<?php
                if (!empty($limit_time_sec)) {
                  $limit_time_per =   (90 + 360 * (1 - ($limit_time_sec / ($roomMaxTime * 60)))) . 'deg';
                  echo 'transform:rotate('.sanitize($limit_time_per).');';
                  echo 'animation:rotation-s '.sanitize($limit_time_sec).'s linear 1 forwards;';
                }
                ?>'"></div>
              </div>
              <div class="remain-time"><span class="remain-time-num-m">
                <?php
                if (!empty($limit_time_min)) {
                  echo sanitize($limit_time_min);
                } else {
                  echo '0';
                }
                ?>
              </span>/
              <?php
              if (!empty($roomMaxTime)) {
                echo sanitize($roomMaxTime);
              }
              ?>
              分</div>
            </div>
          </div>
          </a>
          <?php
            }
          } ?>
        </div>
      </div>
      <div class="sub-bar">
        <div class="sub-bar-list">
          <form action="" method="GET">
            <label for="">
              タグ
              <select name="tag" class="select">
                <option value="">選択してください。</option>
                <?php foreach ($dbTags as $key => $val) { ?>
                  <option value="<?php echo sanitize($val['room_tag_name']); ?>"
                  <?php
                  if (!empty($tag)) {
                    if ($tag == $val['room_tag_name']) {
                      echo 'selected';
                    }
                  }
                  ?>
                  ><?php echo sanitize($val['room_tag_name']);?>
                  </option>
                  <?php } ?>
              </select>
            </label>
            <input type="submit" value="検索" class="btn btn-submit btn-right">
          </form>
        </div>
      </div>
    </div>
  </main>
  <?php require('footer.php'); ?>
  <script src="js/clock.js"></script>

</body>

</html>