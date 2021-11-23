<?php
require('function.php');
debugLogStart();

if (!empty($_GET)) {
  $redirect_path = $_GET['redirect'];
  debug('$redirect_path' . $redirect_path);
}

$title = '認証メールを送信しました。メールを確認してください。';
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
      <div class="main-bar main-bar-only">
        <div class="info-area">
          <svg class="main-img" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/></svg>
            <h1 class="title">認証メールを送信しました。<br>メールを確認してください。</h1>
            <p class="text-muted text-center">本登録の案内を入力いただいたメールアドレス宛に送信いたしました。メールにあるURLをクリックして、登録を完了させてください。</p>
            <div class="info-no-reply js-dropdown-info-no-reply">
              <p class="mb-0 js-toggle-accordion ">
                <span class="no-select">メールが届かない場合はこちら</span>
                <i class="fas fa-chevron-down is-active icon-arrow  js-icon-arrow js-closing-accordion"></i>
                <i class="fas fa-chevron-up icon-arrow js-icon-arrow js-opening-accordion"></i>
              </p>
            <div class="solution-not-receive-mail">
              <ul class="solution-menu">
                <li class="solution">
                  <h3 class="solution-title">迷惑メールフォルダに振り分けられている</h3>
                  <p class="solution-answer">迷惑メールフォルダの確認をお願いいたします。</p>
                </li>
                <li class="solution">
                  <h3 class="solution-title">メール送信から24時間が経過している</h3>
                  <p class="solution-answer">お手数ですが、もう一度<a href="signup.php text-decoration-underline">ユーザー登録</a>からお願いいたします。</p>
                </li>
                <li class="solution">
                  <h3 class="solution-title">その他の場合</h3>
                  <p class="solution-answer">お手数ですが、<span>contact@task-live.com</span>からお問い合わせをお願いいたします。</p>
                </li>
              </ul>
            </div>
            </div>
        </div>
      </div>
    </div>
  </main>
  <?php require('footer.php'); ?>
</body>

</html>