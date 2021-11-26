<?php
require('function.php');
debugLogStart();

if (!empty($_POST)) {
  $email = $_POST['email'];
  validRequired($email,'email');
  global $err_msg;
  if(empty($err_msg)){
    validEmail($email,'email');
  }
  if(empty($err_msg)){
    validLenMax($email,'email',255);
  }
  if(empty($err_msg)){
    debug('バリデーションOK');
    try{
      $dbh = dbConnect();
      $sql = 'SELECT * FROM user WHERE email = :email';
      $data = array(':email' => $email);
      $stmt = queryPost($dbh, $sql, $data);
      $rst = $stmt->rowCount();
      if($rst){
        debug('登録されているメールアドレスです。');
        $_SESSION['success_msg'] = 'メールを送信しました!'; 

        // 認証トークン生成
        $auth_key= uniqid('',true);
        $_SESSION['auth_token'] = $auth_key;

        $dbh = dbConnect();
        $sql = 'INSERT INTO verify_email(email, password, auth_token) VALUES(:email, :pass, :token)';
        $data =  array(':email' => $email,':pass' => '', ':token' => $auth_key);
        $rst = queryPost($dbh, $sql, $data);
        if($rst){
          debug('クエリ成功。');
          debug('認証メール送信します');
        $from ='verify@task-live.com';
        $to = $email;
        $subject = 'TASKLIVEパスワード再発行のご案内';
        $comment = <<<EOT
本メールアドレス宛にパスワード再発行のリクエストを承りました。
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。
        
パスワード再発行認証キー入力ページ：https://task-live.com/passRemindRecieve.php

認証キー：{$auth_key}

※認証キーの有効期限は1時間となります。

認証キーを再発行されたい場合は下記ページより再度再発行をお願いいたします。
https://task-live.com/passwordRemind.php

このメールはご入力いただいたメールアドレス宛に自動で送信されています。
送信専用メールアドレスから送っておりますので、
直接ご返信いただいてもお返事ができません。あらかじめご了承ください。
万が一、このメールにお心当たりの無い場合は、
どなたかがメールアドレスを間違って入力してしまった可能性があります。
恐れ入りますが、以下の問合せ先までお知らせをお願いいたします。

------------------------------
問い合わせ窓口
Email： contact@task-live.com
------------------------------
        
EOT;

sendMail($from, $to, $subject, $comment);
$_SESSION['success_msg'] = '本登録確認メールを送信しました';

header("Location:passRemindReceive.php"); //認証コード入力ページへ

      }else{
        debug('クエリ失敗。');
        $err_msg['common'] = ERR_QUERUY;
      }
      }else{
        debug('登録されてないメールアドレスです。');
        $err_msg['email'] = 'データベースエラーが発生しました。お手数ですが時間をあけて、再度行ってください。';
      }
    }catch(Exception $e){
      debug('データベースエラー：' . $e->getMessage());
    }
  }


}



$title = 'パスワードリマインド';
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
        <p class="form-lead">TASKLIVEに登録してあるメールアドレスを入力してください。</p>
        <?php if (!empty($err_msg['email'])) {
            echo '<span class="err" style="float:none" >' . sanitize($err_msg['email']) . '</span>'; 
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
        <input type="submit" value="送信" class="btn btn-submit">
      </form>
    </div>
  </main>
  <?php require('footer.php'); ?>
</body>

</html>