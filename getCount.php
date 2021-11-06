<?php
require_once('function.php'); 
debug('getCountですよ');
if(!empty($_POST)){
  debug('POST送信がありました。');
  debug('POSTパラメータ'.print_r($_POST,true));
  echo getLogs($_POST['uri']); 
}