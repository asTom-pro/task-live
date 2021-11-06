<?php

require('function.php');
debugLogStart();

$title = '学校科目検索';

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
          学校名
          <?php if (!empty($err_msg['room_name'])) {
            echo '<span class="err">' . sanitize($err_msg['room_name']) . '</span>';
          } ?>
          <input type="text" name="room-name" class="input js-room-name-input-count" value="<?php if (!empty($_GET['room-name'])) {
                                                                                              echo sanitize($_GET['room-name']);
                                                                                            } ?>">
        </label>
        <label for="">
          学部名
          <?php if (!empty($err_msg['room_name'])) {
            echo '<span class="err">' . sanitize($err_msg['room_name']) . '</span>';
          } ?>
          <input type="text" name="room-name" class="input js-room-name-input-count" value="<?php if (!empty($_GET['room-name'])) {
                                                                                              echo sanitize($_GET['room-name']);
                                                                                            } ?>">
        </label>
        <input type="submit" value="検索" class="btn btn-submit">
      </form>
    </div>
  </main>
  <?php require('footer.php'); ?>
  <script src="js/makeRoom.js"></script>
</body>

</html>