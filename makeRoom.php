<?php

require('function.php');
debugLogStart();

if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] != 1) {
  $dbMyOpenRoom = getMyOpenRoom($_SESSION['user_id']);
} else {
  // ゲストユーザーログイン
  debug('ゲストユーザーログインします。');
  $_SESSION['user_id'] = 1;
}



if (!empty($_GET)) {
  $room_name = $_GET['room-name'];
  $set_time_hour = $_GET['set-time-hour'];
  $set_time_minute = $_GET['set-time-minute'];
  $room_tag = $_GET['tag'];
  if (!empty($_GET['tag'])) {
    $room_tag = $_GET['tag'];
    debug('$room_tag１個目' . print_r($room_tag, true));
    validLenMax($room_tag, 'room_tag', 20);
    if (empty($err_msg['room_tag'])) {
      $room_tag = str_replace('　', ' ', $room_tag);
      $room_tag = trim($room_tag);
      $room_tag = str_replace('  ', ' ', $room_tag);
      $room_tag = explode(' ', $room_tag);
      debug('$room_tag2個目' . print_r($room_tag, true));
      $new_room_tag = array_unique(dedupTag($room_tag));
      $old_room_tag = array_unique(array_diff($room_tag, $new_room_tag));
    }
  }
  // 設定時間を秒数変換
  $set_time = ($set_time_hour * 60 * 60) + ($set_time_minute * 60);

  validRequired($room_name, 'room_name');
  validRequired($set_time, 'set_time');
  if (empty($err_msg)) {
    validLenMax($room_name, 'room_name', 20);
    if (empty($err_msg)) {
      try {
        debug('部屋作成・トライ');
        $dbh =  dbConnect();
        // 部屋を作る
        $sql = 'INSERT INTO room(room_name,room_time_limit,user_id) VALUES(:room_name,:room_time_limit,:user_id)';
        $data = array(':room_name' => $room_name, ':room_time_limit' => $set_time, ':user_id' => $_SESSION['user_id']);
        debug($data);
        $rst_1 = queryPost($dbh, $sql, $data);
        $r_id = $dbh->lastInsertId();
        // タグを登録・設定する
        $sql = 'INSERT INTO room_tag(room_tag_name) VALUES(:room_tag_name)';
        $sql_id = 'INSERT INTO rooms_room_tags(room_id,room_tag_id) VALUES(:room_id,:room_tag_id)';
        $dbh->beginTransaction();
        if (!empty($new_room_tag)) {
          foreach ($new_room_tag as $key => $val) {
            // 新規タグ登録
            $data =  array(':room_tag_name' => $val);
            queryPost($dbh, $sql, $data);
            // 部屋と新規タグを結ぶ
            $t_id = $dbh->lastInsertId();
            $data = array(':room_id' => $r_id, ':room_tag_id' => $t_id);
            queryPost($dbh, $sql_id, $data);
          }
        }
        // 新規以外のタグを部屋と結ぶ
        if (!empty($old_room_tag)) {
          foreach ($old_room_tag as $key => $val) {
            $t_id = getTagId($val);
            $data = array(':room_id' => $r_id, ':room_tag_id' => $t_id);
            queryPost($dbh, $sql_id, $data);
          }
        }
        $dbh->commit();
        // その部屋に参加する
        if ($_SESSION['user_id'] != 1) {
          $sql = 'INSERT INTO join_room_user(room_id,join_user_id) VALUES(:r_id,:join_user_id)';
          $data = array(':r_id' => $r_id, 'join_user_id' => $_SESSION['user_id']);
          $rst_2 = queryPost($dbh, $sql, $data);
        } else {
          $rst_2 = true;
        }
        $dbh = null;
        if ($rst_1 && $rst_2) {
          debug('部屋作成・成功');
          debug('部屋ページに遷移します。');
          header('Location:room.php?r_id=' . $r_id);
        } else {
          debug('部屋作成・失敗');
        }
      } catch (Exception $e) {
        debug('データベースエラー：' . $e->getMessage());
      }
    }
  }
}
$title = '部屋作成';
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require('head.php'); ?>
  <link href="css/form.css" rel="stylesheet">
  <link href="css/makeRoom.css" rel="stylesheet">
  <!-- リロード時のtransition対策 -->
  <script>
    console.log("");
  </script>
</head>

<body class="body">
  <?php require('header.php'); ?>
  <main class="main">
    <div class="container">
      <?php
      if (!empty($dbMyOpenRoom)) {
        echo '
          <div class="open-myroom">
          <p>作成した部屋がまだ開いています</p>
        <a href="room.php?r_id=' . sanitize($dbMyOpenRoom['room_id']) . '" class="info-txt">部屋に戻る</a>
        </div>
        ';
      }
      ?>
      <form action="" class="form make-room-form" method="GET">
        <h1 class="form-title"><?php echo sanitize($title) ?></h1>
        <label for="">
          部屋名<span style="font-size:12px">(20文字まで)</span><span class="required">※必須</span>
          <div class="room-name-title  input-text-num"><span class="js-show-room-name-count">0</span>/20</div>
          <?php if (!empty($err_msg['room_name'])) {
            echo '<span class="err">' . sanitize($err_msg['room_name']) . '</span>';
          } ?>
          <input type="text" name="room-name" class="input js-room-name-input-count" value="<?php if (!empty($_GET['room-name'])) {
                                                                      echo sanitize($_GET['room-name']);
                                                                    } ?>">
        </label>
        <label for="">タグ<span style="font-size:12px">（スペースで区切る・計20文字まで）</span>
        <div class="room-tag-title input-text-num"><span class="js-show-room-tag-count">0</span>/20</div>
<?php if (!empty($err_msg['room_tag'])) { ?> <span class="err"><?php echo  sanitize($err_msg['room_tag']); ?></span><?php } ?>
          <input type="text" name="tag" class="input js-room-tag-input-count" value="<?php if (!empty($_GET['tag'])) {
                                                                echo sanitize($_GET['tag']);
                                                              } ?>">
        </label>
        <label for="">
          制限時間<span class="required">※必須</span>
          <?php if (!empty($err_msg['set_time'])) {
            echo '<span class="err">' . sanitize($err_msg['set_time']) . '</span>';
          } ?>
          <div class="select-container">
            <select name="set-time-hour" id="" class="select select-time set-time-hour">
              <?php for ($i = 0; $i <= 23; $i++) {
                echo '<option value="' . sanitize($i) . '"';
                if (!empty($set_time_hour)) {
                  if ($set_time_hour == $i) {
                    echo 'selected';
                  }
                }
                echo '>' . sanitize($i) . '</option>';
              } ?>
            </select><span class="select-name">時間</span>
            <select name="set-time-minute" id="" class="select select-time set-time-minute">
              <?php for ($i = 0; $i <= 59; $i++) {
                echo '<option value="' . sanitize($i) . '"';
                if (!empty($set_time_minute)) {
                  if ($set_time_minute == $i) {
                    echo 'selected';
                  }
                }
                echo '>' . sanitize($i) . '</option>';
              } ?>
            </select><span class="select-name">分</span>
            <button class="btn btn-recommend " id="js-btn-recommend" type="button">おすすめ時間</button>
          </div>
        </label>
        <input type="submit" value="作成する" class="btn btn-submit">
      </form>
    </div>
  </main>
  <?php require('footer.php'); ?>
  <script src="js/makeRoom.js"></script>
</body>

</html>