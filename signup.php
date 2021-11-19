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
    debug('ユーザー登録/認証メール送信します');
    try{
      debug('ユーザー仮登録・トライ');

      // 認証トークン生成
      $auth_key= uniqid('',true);


      $dbh = dbConnect();
      $sql = 'INSERT INTO verify_email(email, password, auth_token) VALUES(:email, :pass, :token)';
      $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT), ':token' => $auth_key);
      $rst = queryPost($dbh, $sql, $data);
      if($rst){
        debug('クエリ成功。');
        debug('認証メール送信します');

        $from ='verify@task-live.com';
        $to = $email;
        $subject = 'TASKLIVE本登録のご案内';
        $comment = <<<EOT
ご利用ありがとうございます。
本登録のご案内です。

以下のリンクをクリックして本登録のお手続きをお願いいたします。

https://task-live.com/identity-email-activations.php?token={$auth_key}

※恐れ入りますが24時間以内に本登録をお願いいたします。
24時間を超えると上記URLをクリックをしても本登録をすることができませんので、ご了承ください。

なお、何かご不明な点や、お困りのことがございましたら、以下問い合わせ先よりお気軽にご連絡ください。

このメールはご入力いただいたメールアドレス宛に自動で送信されています。
送信専用メールアドレスから送っておりますので、
直接ご返信いただいてもお返事ができません。あらかじめご了承ください。

このメールは送信先のメールアドレス宛てに自動で送信されています。
送信専用メールアドレスから送っております。
直接ご返信いただいてもお返事することができませんので、ご了承ください。

万が一、このメールにお心当たりの無い場合は、
どなたかがメールアドレスを間違って入力してしまった可能性があります。
恐れ入りますが、以下の問合せ先までお知らせをお願いいたします。

------------------------------
問い合わせ窓口
Email： contact@task-live.com
------------------------------
EOT;
      sendMail($from, $to, $subject, $comment);
      $_SESSION['msg_success'] = '本登録確認メールを送信しました';

      header("Location:info-confirmation.php"); //登録確認ページへ

      }else{
      debug('クエリに失敗しました。');
      $err_msg['common'] = ERR_QUERUY;
      }
    }catch(Exception $e){
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = ERR_MAILCONFIRM;
    }
  }
}




$title = 'ユーザー登録';
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