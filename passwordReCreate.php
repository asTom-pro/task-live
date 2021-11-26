<?php
require('function.php');
debugLogStart();


if (!empty($_POST)) {
  $pass = $_POST['pass'];
  $re_pass = $_POST['re_pass'];

  validRequired($pass, 'pass');
  validRequired($re_pass, 're_pass');
  if (empty($err_msg)) {
    validLenMin($pass, 'pass', 6);
  }
  if (empty($err_msg)) {
    validMatched($pass, $re_pass, 'pass');
  }
  if (empty($err_msg)) {
    validLenMin($pass, 'pass', 6);
  }
  if (empty($err_msg)) {
    global $err_msg;
    if(!empty($_SESSION['auth_token'])){
      // 認証コードからユーザーを指定する。
      $auth_key = $_SESSION['auth_token'];
      try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM verify_email WHERE auth_token = :token';
        $data = array(':token' => $auth_key);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt->rowCount()){
          $rst = $stmt->fetch(PDO::FETCH_ASSOC);
          $email = $rst['email'];
        }else{
          // 入力されたトークンが存在しない場合
          debug('不正な値が入力されたので、トップページに遷移します。');
          $err_msg['token'] = '認証コード発行から２４時間経過したので、お手数ですが、再度初めから行ってください。';
        }
      }catch(Exception $e){
        debug($e->getMessage());
      }
    }else{
      $err_msg['token'] = 'セッションエラーです。一貫して必ず同じデバイスでパスワードリセットを行ってください。';
    }
  }
  if(empty($err_msg)){
    debug('パスワードリセットします');
    try{
      debug('パスワードリセット・トライ');
      $dbh = dbConnect();
      $sql = 'UPDATE user SET password = :pass WHERE email = :email';
      $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT));
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        debug('パスワードリセット・成功');

        $sql = 'SELECT * FROM user WHERE email = :email';
        $data = array(':email' => $email);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt->rowCount()){
          unset($_SESSION['auth_token']);
          $rst =  $stmt->fetch(PDO::FETCH_ASSOC);
          $user_id =  $rst['id'];
          
          // ログイン処理はじめ
          $_SESSION['user_id'] = $user_id;
          $ses_time = 60 * 60 * 24;
          $_SESSION['login_limit'] = time() + $ses_time;
          // ログイン処理おわり

          $_SESSION['success_msg'] = 'パスワード変更しました!';
          header('Location:index.php');
          exit();
        }
      }else{
        debug('パスワードリセット・失敗');
        global $err_msg;
        $err_msg['pass'] = 'サーバーエラーが発生しました。お手数ですが、時間をあけて再度行ってください。';
      }
    }catch(Exception $e){
      debug($e->getMessage());
    }
  }
}



$title = '新しいパスワードを作成';
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
        <p></p>
        <?php if (!empty($err_msg['pass'])) {
            echo '<span class="err" style="float:none" >' . sanitize($err_msg['pass']) . '</span>'; 
        } ?>
        <label for="">
          新しいパスワードを入力<span class="required"></span>
          <?php if (!empty($err_msg['pass'])) {
            echo '<span class="err">' . sanitize($err_msg['pass']) . '</span>';
          } ?>
          <input type="password" name="pass" class="input" value="<?php if (!empty($_POST['pass'])) { echo sanitize($_POST['pass']);} ?>">                                                   
        </label>
        <label for="">
          パスワードを再度入力してください<span class="required"></span>
          <?php if (!empty($err_msg['re_pass'])) {
            echo '<span class="err">' . sanitize($err_msg['re_pass']) . '</span>';
          } ?>
          <input type="password" name="re_pass" class="input" value="<?php if (!empty($_POST['re_pass'])) { echo sanitize($_POST['re_pass']);} ?>">                                                   
        </label>
        <input type="submit" value="送信" class="btn btn-submit">
      </form>
    </div>
  </main>
  <?php require('footer.php'); ?>
</body>

</html>