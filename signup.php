<?php
require('function.php');
debugLogStart();

if (!empty($_GET)) {
  $redirect_path = $_GET['redirect'];
  debug('$redirect_path' . $redirect_path);
}


if (!empty($_POST)) {
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $re_pass = $_POST['re_pass'];

  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($re_pass, 're_pass');
  if (empty($err_msg)) {
    validEmail($email, 'email');
    validLenMin($pass, 'pass', 6);
  }
  if (empty($err_msg)) {
    validEmail($email, 'email');
    validMatched($pass, $re_pass, 'pass');
  }
  if (empty($err_msg)) {
    validEmailDup($email, 'email');
    validLenMin($pass, 'pass', 6);
  }
  if (empty($err_msg)) {
    debug('ユーザー登録・トライします');
    try {

      $dbh = dbConnect();
      $sql = 'INSERT INTO user(email,`password`) VALUES(:email,:pass)';
      $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT));
      $stmt =  queryPost($dbh, $sql, $data);
      if ($stmt) {
        debug('ユーザー登録・成功');
        $ses_time = 60 * 60 * 24;
        $_SESSION['login_limit'] = time() + $ses_time;
        $_SESSION['user_id'] = $dbh->lastInsertid();
        if (!empty($redirect_path)) {
          debug('指定されたパスに遷移します');
          header('Location:' . $redirect_path);
          exit();
        } else {
          debug('部屋一覧ページに遷移します');
          header('Location:index.php');
          exit();
        }
      } else {
        debug('ユーザー登録・失敗');
      }
    } catch (Exception $e) {
      debug('データベースエラー：' . $e->getMessage());
    }
  }
}




$title = '部屋一覧';
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <?php require('head.php'); ?>
  <link href="css/form.css" rel="stylesheet">
  <!-- リロード時のtransition対策 -->
  <script>
    console.log("");
  </script>
</head>

<body class="body">
  <?php require('header.php'); ?>
  <main class="main">
    <div class="container">
      <form action="" class="form signup-form" method="POST">
        <h1 class="form-title">ユーザー登録</h1>
        <label for="">
          メールアドレス<span class="required">※必須</span>
          <?php if (!empty($err_msg['email'])) {
            echo '<span class="err">' . sanitize($err_msg['email']) . '</span>';
          } ?>
          <input type="text" name="email" class="input" value="<?php if (!empty($_POST['email'])) {
                                                                  echo sanitize($_POST['email']);
                                                                } ?>">
        </label>
        <label for="">
          パスワード<span style="font-size:14px">(6文字以上)</span><span class="required">※必須</span>
          <?php if (!empty($err_msg['pass'])) {
            echo '<span class="err">' . sanitize($err_msg['pass']) . '</span>';
          } ?>
          <input type="password" name="pass" class="input" value="<?php if (!empty($_POST['pass'])) {
                                                                    echo sanitize($_POST['pass']);
                                                                  } ?>">
        </label>
        <label for="">
          パスワード（再入力）<span class="required">※必須</span>
          <?php if (!empty($err_msg['re_pass'])) {
            echo '<span class="err">' . sanitize($err_msg['re_pass']) . '</span>';
          } ?>
          <input type="password" name="re_pass" class="input" value="<?php if (!empty($_POST['re_pass'])) {
                                                                        echo sanitize($_POST['re_pass']);
                                                                      } ?>">
        </label>
        <input type="submit" value="登録" class="btn btn-submit">
      </form>
    </div>
  </main>
  <?php require('footer.php'); ?>
</body>

</html>