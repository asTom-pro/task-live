<?php

require('function.php');
debugLogStart();
require('auth.php');
if (!empty($_SESSION)) {
  $u_id = $_SESSION['user_id'];
  $dbUserInfo = getUser($u_id);
}
if (!empty($_POST)) {
  debug('POST送信がありました。');
  $user_name = $_POST['name'];
  $prof_text = $_POST['introduce'];
  $file = (!empty($_FILES['prof-img']['name']) ? updateImg($_FILES['prof-img'], 'file') : $dbUserInfo['profile_img']);

  if (empty($err_msg)) {
    try {
      debug('プロフィール編集・トライ');
      $dbh = dbConnect();
      $sql = 'UPDATE user SET user_name = :user_name ,profile_img = :prof_img ,profile_text = :prof_text WHERE id = :u_id';
      $data = array(':user_name' => $user_name, ':prof_img' => $file, 'prof_text' => $prof_text, ':u_id' => $u_id);
      $rst = queryPost($dbh, $sql, $data);
      if ($rst) {
        debug('プロフィール編集・成功');
        $_SESSION['success_msg'] =  '更新しました!';
        debug('チェック' . print_r($_SESSION['success_msg'], true));
        header("Location:" . $_SERVER['PHP_SELF']);
        exit();
      } else {
        debug('プロフィール編集・失敗');
      }
    } catch (Exception $e) {
      debug($e->getMessage());
    }
  }
}
$title = 'プロフィール';
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require('head.php'); ?>
  <link href="css/form.css" rel="stylesheet">
  <link href="css/profile.css" rel="stylesheet">
  <!-- リロード時のtransition対策 -->
  <script>
    console.log("");
  </script>
  <script>
    $(function() {
      $('#upfile').change(function() {
        var reader = new FileReader();
        var file = this.files[0];
        reader.readAsDataURL(file);
        reader.onload = function() {
          $('.user-img').attr('src', reader.result);
          $('.profile-grayscale-img').css('opacity', '0');
        }
      });
    })
  </script>
</head>

<body class="body">
  <?php if (!empty($_SESSION['success_msg'])) : ?>
    <div id="msg-container">
      <div id="success-msg">
        <?php echo '<i class="far fa-check-circle"></i>' . sanitize(getSessionOnce('success_msg')) ?>
        <div class="msg-bg-color"></div>
      </div>
    </div>
  <?php endif;  ?>
  <?php require('header.php'); ?>
  <main class="main">
    <div class="container">
      <form action="" class="form make-room-form" method="POST" enctype="multipart/form-data">
        <h1 class="form-title"><?php echo sanitize($title) ?></h1>
        <div class="profile-grayscale">
          <img src="img/profile-grayscal2.png" alt="" class="profile-grayscale-img">
          <img src="<?php if (!empty($dbUserInfo['profile_img'])) {
                      echo sanitize($dbUserInfo['profile_img']);
                    } else {
                      echo 'img/user-sample.svg';
                    } ?>" alt="" class="user-img">
          <input type="file" name="prof-img" class="prof-img" id="upfile">
        </div>
        <label for="">
          お名前
          <input type="text" name="name" class="input" value="<?php if (!empty($dbUserInfo['user_name'])) {
                                                                echo sanitize($dbUserInfo['user_name']);
                                                              } ?>">
        </label>
        <label for="">
          メールアドレス<?php if (!empty($err_msg['email'])) {
                    echo sanitize($err_msg['email']);
                  } ?>
          <p style="margin-top: 0;"><?php if (!empty($dbUserInfo['email'])) {
                                      echo sanitize($dbUserInfo['email']);
                                    } ?></p>
        </label>
        <label for="">
          プロフィール文
          <textarea name="introduce" id="js-count" cols="30" rows="10" class="input"><?php if (!empty($dbUserInfo['profile_text'])) {
                                                                                        echo sanitize($dbUserInfo['profile_text']);
                                                                                      } ?></textarea>
          <span class="input-num">
            <sapn class="show-count"></sapn>/140文字
          </span>
        </label>
        <input type="submit" value="更新する" class="btn btn-submit">
      </form>
    </div>
  </main>
  <?php require('footer.php'); ?>
  <script src="js/textCount.js"></script>
</body>

</html>