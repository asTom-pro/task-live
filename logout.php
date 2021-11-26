<?php
require_once('function.php');
debugLogStart();


debug('ログアウトします。');

$_SESSION =  array();
if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time() - 42000, '/');
}
session_destroy();


debug('部屋一覧ページに遷移します。');
header('Location:index.php?logout=true');
