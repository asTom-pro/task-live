<?php
require('function.php');
require('auth.php');

$delete_flg = false;
if (!empty($_SESSION)) {
  $u_id = $_SESSION['user_id'];
}
if (!empty($_POST['delete'])) {
  try {
    debug('ユーザー情報を削除・トライ');
    $dbh = dbConnect();
    $sql = 'UPDATE user SET delete_flg = 1 WHERE id = :u_id';
    $data = array(':u_id' => $u_id);
    $stmt = queryPost($dbh, $sql, $data);
    if ($stmt) {
      debug('ユーザー情報を「削除」にしました。');
      $_SESSION = array();
      session_destroy();
      if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
      }
      $delete_flg = true;
    } else {
      debug('ユーザー情報を「削除」できませんでした。');
      $err_msg['no-delete'] = '退会処理でエラーが起きました。お手数ですが、時間を開けて再度行なってください。';
    }
  } catch (Exception $e) {
    debug('データベースエラー：' . $e->getMessage());
  }
}



$title = '退会';
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require('head.php'); ?>
  <link href="css/index.css" rel="stylesheet">
  <link href="css/withdrow.css" rel="stylesheet">
  <link href="css/subbar-profile.css" rel="stylesheet">
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
        <div class="withdrow-confirm">
          <?php if (!empty($err_msg['no-delete'])) {
            echo sanitize($err_msg['no-delete']);
          } ?>
          <h2 class="form-title"><?php echo sanitize($title); ?></h2>
          <?php if ($delete_flg) {
            echo '
          <div class="delete-completed-notice">
            <p>退会処理が完了しました。</p>
            <p>ご利用ありがとうございました!</p>
            <a href="index.php" class="btn text-decoration-underline">トップページに戻る</a>
        </div>
          ';
          } else {
            echo '
          <p class="form-description">本当に退会しますか?</p>
          <div class="btn-container">
            <a href="userpage.php" class="btn btn-back">戻る</a>
            <form action="" method="POST">
            <input type="submit" name="delete" class="btn btn-withdrow" value="退会する">
            </form>
          </div>
          ';
          } ?>
        </div>
      </div>
      <?php
      if (!$delete_flg) {
        echo '
            <div class="sub-bar">
            ';
        require('subbar-profile.php');
        echo '
            </div>
            ';
      }
      ?>
    </div>
  </main>
  <?php require('footer.php'); ?>
</body>

</html>