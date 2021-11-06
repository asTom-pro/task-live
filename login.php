<?php
require('function.php');
debugLogStart();

if (!empty($_GET)) {
  $redirect_path = $_GET['redirect'];
}


if (!empty($_POST)) {
  $email = $_POST['email'];
  $pass =  $_POST['pass'];
  if (!empty($_POST['login_save'])) {
    $login_save = $_POST['login_save'];
  }

  // バリデーション
  validRequired($email, 'email');
  validRequired($pass, 'pass');

  if (empty($err_msg)) {
    validEmail($email, 'email');
  }
  if (empty($err_msg)) {
    debug('ログイン・トライします');
    try {
      $dbh = dbConnect();
      $sql = 'SELECT * From user WHERE email = :email AND delete_flg = 0';
      $data = array(':email' => $email);
      $stmt = queryPost($dbh, $sql, $data);
      debug('ここは' . print_r($stmt, true));
      $rst = $stmt->fetch(PDO::FETCH_ASSOC);
      debug('取得したユーザー情報：' . print_r($rst, true));
      if (!empty($rst['id'])) {
        if (password_verify($pass, $rst['password'])) {
          debug('ログイン成功');
          $ses_time = 60 * 60 * 24;
          if (!empty($login_save)) {
            $ses_time = 60 * 60 * 24 * 30;
          }
          $_SESSION['login_limit'] = time() + $ses_time;
          $_SESSION['user_id'] = $rst['id'];
          if (!empty($redirect_path)) {
            if (strpos($redirect_path, 'room.php')) {
              $query = parse_url($redirect_path, PHP_URL_QUERY);
              parse_str($query, $parms);
              $r_id = $parms['r_id'];
              try {
                $dbh =  dbConnect();
                $sql = 'INSERT INTO join_room_user(room_id,join_user_id) VALUES(:room_id, :join_user_id)';
                $data = array(':room_id' => $r_id, ':join_user_id' => $_SESSION['user_id']);
                $stmt = queryPost($dbh, $sql, $data);
                if ($stmt) {
                  debug('ログインユーザーを部屋登録・成功');
                } else {
                  debug('ログインユーザーを部屋登録・失敗');
                }
              } catch (Exception $e) {
                debug('データーベースエラー：' . $e->getMessage());
              }
            }
            debug('指定されたパスに遷移します');
            header('Location:' . $redirect_path);
            exit();
          } else {
            debug('部屋一覧ページに遷移します');
            header('Location:index.php');
            exit();
          }
        } else {
          debug('ログイン失敗');
          $err_msg['login'] =  MSG07;
        }
      } else {
        $err_msg['login'] =  MSG07;
      }
    } catch (Exception $e) {
      debug('データベースエラー：' . $e->getMessage());
    }
  }
}



$title = 'ログイン';
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
        <h1 class="form-title"><?php echo sanitize($title) ?></h1>
        <?php if (!empty($err_msg['login'])) {
          echo '<span class="err" style="float:none" >' . sanitize($err_msg['login']) . '</span>';
        } ?>
        <label for="">
          メールアドレス<span class="required"></span>
          <?php if (!empty($err_msg['email'])) {
            echo '<span class="err">' . sanitize($err_msg['email']) . '</span>';
          } ?>
          <input type="text" name="email" class="input" value="<?php if (!empty($_POST['email'])) {
                                                                  echo sanitize($_POST['email']);
                                                                } ?>">
        </label>
        <label for="">
          パスワード<span class="required"></span>
          <?php if (!empty($err_msg['pass'])) {
            echo '<span class="err">' . sanitize($err_msg['pass']) . '</span>';
          } ?>
          <input type="password" name="pass" class="input" value="<?php if (!empty($_POST['pass'])) {
                                                                    echo sanitize($_POST['pass']);
                                                                  } ?>">
        </label>
        <label for="">
          <input type="checkbox" name="login-save">
          ログイン状態を保持する
        </label>
        <input type="submit" value="ログイン" class="btn btn-submit">
      </form>
    </div>
  </main>
  <?php require('footer.php'); ?>
</body>

</html>