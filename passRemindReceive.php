<?php
require('function.php');
debugLogStart();

if (!empty($_SESSION)) {
  if (!empty($_SESSION['auth_token'])) {
    $auth_key = $_SESSION['auth_token'];
    try{
      $dbh = dbConnect();
      $sql = 'SELECT * FROM verify_email WHERE auth_token = :token';
      $data = array(':token' => $auth_token);
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt->rowCount()){
        $rst = $stmt->fetch(PDO::FETCH_ASSOC);
        $email = $rst['email'];
      }
    }catch(Exception $e){
      debug($e->getMessage());
    }
  }
}

if (!empty($_POST)) {
  $auth_token = $_POST['auth_key'];

  global $err_msg;
  validRequired($auth_key, 'auth_key');
  if (empty($err_msg)) {
    try {
      $dbh = dbConnect();
      $sql = 'SELECT * FROM verify_email WHERE auth_token = :token';
      $data = array(':token' => $auth_token);
      $stmt = queryPost($dbh, $sql, $data);
      $rst_num = $stmt->rowCount();
      if ($rst_num) {
        $rst = $stmt->fetch(PDO::FETCH_ASSOC);
        $token_created_date = $rst['created_date'];
        if ((strtotime($token_created_date) + (60 * 60)) < strtotime(date("Y/m/d H:i:s"))) {
          // 認証トークン発行から１時間経過した場合
          debug('認証トークン発行から１時間経過したので、お手数ですが、再度初めから行ってください。');
          $err_msg['token'] = '認証トークン発行から１時間経過したので、お手数ですが、再度初めから行ってください。';
        } else {
          debug('トークンが認証されました。パスワード変更画面に遷移します。');
          $_SESSION['auth_token'] = $auth_token;
          header('Location:passwordReCreate.php');
          exit();
        }
      }
    } catch (Exception $e) {
      debug($e->getMessage());
    }
  }
}



$title = '認証コードを確認';
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
      <form action="" class="form" method="POST">
        <h1 class="form-title"><?php echo sanitize($title) ?></h1>
        <p class="form-lead"><?php if(!empty($email)){ echo $email.'宛に'; } ?>メールを送信いたしましたので、メールに記載されている認証コードを入力してください。</p>
        <label for="">
          認証コードを入力<span class="required"></span>
          <?php if (!empty($err_msg['auth_key'])) {
            echo '<span class="err">' . sanitize($err_msg['auth_key']) . '</span>';
          } ?>
          <input type="text" name="auth_key" class="input" value="<?php if (!empty($_POST['auth_key'])) {
                                                                    echo sanitize($_POST['auth_key']);
                                                                  } ?>">
        </label>
        <input type="submit" value="送信" class="btn btn-submit">
      </form>
    </div>
  </main>
  <?php require('footer.php'); ?>
</body>

</html>