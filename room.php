<?php
require('function.php');

$isSentTask = false;
$followingUserId = array();
$u_id = null;
$from_path = $_SERVER['REQUEST_URI'];

$uri = $_SERVER["REQUEST_URI"];
$ipaddress = $_SERVER["REMOTE_ADDR"];
if (!empty($_SESSION) && !empty($_SESSION['user_id'])) {
  $u_id = $_SESSION['user_id'];
  $followedUser = getFollowerList($u_id);
  $followedUserId = array();
  foreach ($followedUser as $key => $val) {
    $followedUserId[] = $val['user_id'];
  }
  $followingUser = getFollowingList($u_id);
  foreach ($followingUser as $key => $val) {
    $followingUserId[] = $val['follow_id'];
  }
}

if (!empty($_POST)) {
  debug('POSTパラメータがあります。');
  $endedTask = $_POST['ended-task'];
  validLenMax($endedTask, 'ended_task', 400);
  if (empty($err_msg)) {
    if (!empty($endedTask)) {
      try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO ended_task(user_id, ended_task) VALUES(:u_id,:task)';
        $data = array(':u_id' => $u_id, ':task' => $endedTask);
        $stmt = queryPost($dbh, $sql, $data);
        if ($stmt) {
          debug('完了タスク保存・成功');
          $isSentTask = true;
          $success_msg =  '保存しました!';
        } else {
          debug('完了タスク保存・失敗');
          $isSentTask = false;
        }
      } catch (Exception $e) {
        debug('データーベースエラー：' . $e->getMessage());
      }
    }
  }
  $_POST = array();
}

if (!empty($_GET['r_id'])) {
  debug('GETパラメータがあります。');
  debug('GETパラメータ' . print_r($_GET, true));
  $r_id =  $_GET['r_id'];
  $roomData =  getRoomOne($r_id);
  $roomCreatedUserData = getUser($roomData['user_id']);
  $roomMaxTime =  $roomData['room_time_limit'];
  // 文字列日付を数字日付に変換
  $formed_date = strtotime($roomData['create_date']);
  $limit_time_sec = (idate('U', $formed_date) + $roomMaxTime) - time();
  $limit_hours = floor($limit_time_sec / 3600);
  $limit_minutes = floor(($limit_time_sec / 60) % 60);
  $limit_seconds = floor($limit_time_sec % 60);
  $limit_time = sprintf("%02d:%02d:%02d", $limit_hours, $limit_minutes, $limit_seconds);

  $room_end_flg = false; //部屋稼働中
  $isJoinMember = false;
  if ($limit_time_sec < 0) {
    $room_end_flg = true; //部屋終了
    $limit_time = '00:00:00';
    $limit_time_sec = 0;
    $isJoinMember =  isJoinMember($r_id, $u_id);
  }

  if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] != 1) {
    $join_user_id = $_SESSION['user_id'];
  } else {
    $join_user_id = 'guest.' . uniqid('', true);
  }
  if (!$room_end_flg) {
    debug('ゲストログインします');
    // ゲストログイン
    // 参加するゲストユーザーをこの部屋に登録（最大人数を計上する）
    try {
      $dbh =  dbConnect();
      $sql = 'INSERT INTO join_room_user(room_id,join_user_id) VALUES(:room_id,:join_user_id)';
      $data = array(':room_id' => $r_id, ':join_user_id' => $join_user_id);
      $stmt = queryPost($dbh, $sql, $data);
      if ($stmt) {
        debug('ゲストユーザーを部屋登録・成功');
      } else {
        debug('ゲストユーザーを部屋登録・失敗');
      }
    } catch (Exception $e) {
      debug('データーベースエラー：' . $e->getMessage());
    }
  }
  $boardInfo = getBoard($r_id);
  $_GET = array();
} else {
  $_GET = array();
  debug('不正なパラメーターです。');
  debug('部屋一覧ページに遷移します。');
  header('Location:index.php');
}


$title = '部屋';


?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require('head.php'); ?>
  <link href="css/room.css" rel="stylesheet">
  <link href="css/clock.min.css" rel="stylesheet">
  <link href="css/end.css" rel="stylesheet">
  <?php if ($room_end_flg) : ?>
    <link href="css/ended.css" rel="stylesheet">
  <?php else : ?>
    <script>
      $(function() {
        function countAll() {
          setCount();
          getCount();
        }
        /* アクセスユーザーのログを記録 */
        function setCount() {
          $(function() {
            $.ajax({
              type: 'POST',
              url: 'setCount.php',
              data: {
                uri: '<?php echo $uri; ?>',
                ipaddress: '<?php echo $ipaddress; ?>'
              },
              success: function(data) {},
              error: function(data) {}
            });
          });
        }
        /* アクセスユーザーのログを取得 */
        function getCount() {
          $(function() {
            $.ajax({
              type: 'POST',
              url: 'getCount.php',
              data: {
                uri: '<?php echo $uri; ?>'
              },
              success: function(data) {
                $('#show-count').html(data);
              },
              error: function(data) {}
            });
          });
        }
        setInterval(function() {
          countAll()
        }, 10000);
      })
    </script>
  <?php endif; ?>
  <!-- リロード時のtransition対策 -->
  <script>
    console.log("");
  </script>
</head>

<body class="body">
  <?php require('header.php'); ?>
  <main class="main">
    <div class="container">
      <div class="main-bar">
        <div class="room-info">
        <i class="fas fa-share-alt icon-invite d-lg-none js-modal-invite-open"></i>
          <div class="info-top">
            <div class="room-title-container">
              <div class="room-title">
                <span class="room-select-title"><?php if (!empty($roomData)) {
                                                  echo sanitize($roomData['room_name']);
                                                } ?></span>
                の部屋
              </div>
            </div>
          </div>
          <div class="info-bottom">
            <div class="info-left">
              <div class="info-left-top limit-time"><span class="remain-time-num"><?php echo sanitize($limit_time); ?></span>
              </div>
              <div class="info-left-bottom">
                <div class="now-joined-num">
                  <?php if ($room_end_flg) {
                    echo '終了';
                  } else {
                    echo '現在';
                  } ?>
                  <span class="member-num">
                    <sapn id="show-count">1</sapn>
                  </span>人
                </div>
                <a href="userpage.php?u=<?php if (!empty($roomData['user_id']) && $roomData['user_id'] != 1) {
                                          echo sanitize($roomData['user_id']);
                                        } ?>">
                  <div class="room-user-info">
                    <?php if (in_array($roomData['user_id'], $followingUserId)) {
                      echo '
                    <img src="img/favorite.png" alt="" class="favorite">
                    ';
                    } else {
                      echo '
                    <img src="" alt="" class="no-favorite favorite">
                    ';
                    } ?>
                    <img src="<?php if (!empty($roomCreatedUserData['profile_img'])) {
                                echo sanitize($roomCreatedUserData['profile_img']);
                              } else {
                                echo 'img/user-sample.svg';
                              } ?>" alt="" class="user-img">
                    <span class="user-name"><?php if (!empty($roomCreatedUserData['user_name'])) {
                                              echo sanitize($roomCreatedUserData['user_name']);
                                            } else {
                                              echo '名称未設定';
                                            } ?></span>
                  </div>
                </a>
              </div>
            </div>
            <div class="info-right">
              <div class="room-time">
                <div class="clock">
                  <div class="clock-hand"></div>
                  <div class="clock-param"></div>
                </div>
                <div class="clock-bottom">
                  <button class="btn btn-invite js-btn-invite-open d-sm-none">部屋に招く</button>
                  <div class="d-none invite-container">
                    <p class="link-text">
                      <span class="m-auto">招待リンク</span>
                      <?php echo sanitize($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>
                    </p>
                    <span class="link-invite js-btn-invite">リンクをコピー</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="room-board">
          <p class="room-board-lead">この部屋でやることを宣言しましょう!</p>
          <div class="room-board-user">
            <?php
            if (!empty($boardInfo)) {
              foreach ($boardInfo as $key => $val) {
                $boardUserInfo =  getUser($val['user_id']);
                $boardUserId = $boardUserInfo['id'];
                $boardUserName = (!empty($boardUserInfo['user_name'])) ? $boardUserInfo['user_name'] : '名称未設定';
                $boardUserImg = (!empty($boardUserInfo['profile_img'])) ? $boardUserInfo['profile_img'] : 'img/user-sample.svg';
                if ($boardUserInfo['id'] == $u_id) {
                  echo '
                  <div class="right-user board-user">
                  <div class="user-info">
                    <img src="' . sanitize($boardUserImg) . '" alt="" class="user-img">
                    <span class="user-name">' . sanitize($boardUserName) . '</span>
                  </div>
                  <div class="comment-container">
                    <p class="comment"><span class="user-ballon"></span>' . sanitize($val['comment']) . '</p>
                  </div>
                </div>   
                  ';
                } else {
                  echo '
                  <div class="left-user board-user">
                  <div class="user-info">
                    <img src="' . sanitize($boardUserImg) . '" alt="" class="user-img">
                    <span class="user-name">' . sanitize($boardUserName) . '</span>
                  </div>
                  <div class="comment-container">
                    <p class="comment"><span class="user-ballon"></span>' . sanitize($val['comment']) . '</p>
                  </div>
                </div>   
                  ';
                }
              }
            }
            ?>
          </div>
          <?php if (!$room_end_flg) : ?>
            <div class="comment-form">
              <textarea name="comment" id="" cols="30" rows="10" class="textarea"></textarea>
              <button type="button" class="btn btn-submit btn-comment">コメントする</button>
            </div>
          <?php endif; ?>
        </div>


        <?php
        // 部屋が終わっている。かつ、ログインしている。
        if ($room_end_flg && isLogin()) {
          // メモを既に保存している
          if ($isSentTask) {
            echo '
            <div class="end-room ended-room">
            </div>
            <div class="end-room-description ended-room-description">    
            <p class="description">完了タスクを保存しました。<br>
            <a href="userpage.php" class="info-txt text-decoration-underline">マイページ</a>で確認できます。
            </p>
            <div class="btn-container">
            <a href="index.php" class="btn join-similar-room">部屋を探す</a>
            <a href="makeRoom.php" class="btn make-my-room">部屋を作る</a>
            </div>';
          } else {
            // メモをまだ保存していない
            if ($isJoinMember) {
              // その部屋の参加した人
              echo '
              <div class="end-room ended-room">
              </div>
              <div class="end-room-description ended-room-description">      
              <p class="description">お疲れ様でした<br>
              完了タスクをメモしますか？<br>
              <span class="info-txt">メモは<a href="userpage.php" class="text-decoration-underline">マイページ</a>に保存されます。</span>
              </p>
            <form class="end-room-form" method="POST">
              <textarea name="ended-task" id="" cols="30" rows="10" class="end-task-textarea"></textarea>
              <input type="submit" value="送信" class="btn btn-submit">
            </form>
            ';
            } else {
              // 部屋に参加してない人
              echo '
              <div class="end-room ended-room">
              </div>
              <div class="end-room-description ended-room-description">      
              <p class="description">この部屋は終了しました </p>
              <div class="btn-container">
              <a href="index.php" class="btn join-similar-room">部屋を探す</a>
              <a href="makeRoom.php" class="btn make-my-room">部屋を作る</a>
              </div>';
            }
          }
          echo '
        </div>
        ';
        } elseif (!isLogin() && $room_end_flg) {
          // ログインしてない、かつ部屋が終わってる時
          echo '
          <div class="end-room ended-room">
          </div>
          <div class="end-room-description ended-room-description">
          <p class="description">この部屋は終了しました </p>
          <div class="btn-container">
          <a href="index.php" class="btn join-similar-room">部屋を探す</a>
          <a href="makeRoom.php" class="btn make-my-room">部屋を作る</a>
          </div>';
        }
        ?>
        <?php if(!$room_end_flg){ ?>
        <?php if (isLogin()) { ?>
          <!-- 時間終了時にJSで表示するタスク入力モーダル -->
          <div class="js-show-end-room end-room d-none">
          </div>
          <div class="js-show-end-room-description end-room-description d-none">
            <p class="description">
              お疲れ様でした<br>
              完了タスクをメモしますか？<br>
              <span class="info-txt">メモは<a href="userpage.php" class="text-decoration-underline">マイページ</a>に保存されます。</span>
            <form class="end-room-form" method="POST">
              <textarea name="ended-task" id="" cols="30" rows="10" class="end-task-textarea"></textarea>
              <input type="submit" value="送信" class="btn btn-submit">
            </form>
            </p>
          </div>
        <?php } else { ?>
          <!-- 時間終了時にをJSで表示するタスク入力モーダル -->
          <div class="js-show-end-room end-room d-none">
          </div>
          <div class="js-show-end-room-description end-room-description end-room-no-login d-none">
            <p class="description">
              お疲れ様でした<br>
              完了タスクをメモしますか？<br>
            <p>メモするにはログインをしてください。</p>
            </p>
            <div class="btn-container">
              <a href="index.php" class="join-similar-room link">部屋を探す</a>
              <a href="login.php?redirect=<?php echo $from_path; ?>" class="btn-login link">ログイン</a>
            </div>
          </div>
        <?php }; ?>
        <?php }; ?>
      </div>
    </div>
    </div>
  </main>
  <?php require('footer.php'); ?>
  <script src="js/room.js"></script>
  <script src="js/clock.js"></script>

  <div class="d-none limit-time-sec"><?php echo sanitize($limit_time_sec) ?></div>
  <div class="d-none limit-time-max"><?php echo sanitize($roomMaxTime) ?></div>
</body>

</html>