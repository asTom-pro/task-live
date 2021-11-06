<?php

if($_SESSION['login_limit'] < time()){
  debug('ログイン期間をオーバーしています。');
  debug('ログイン画面に遷移します。');
  header('Location:login.php');
  exit();
}
