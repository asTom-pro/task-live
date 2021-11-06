<?php

require('function.php');

debug('AjaxFollow');

if(!empty($_POST) && isset($_SESSION['user_id']) && isLogin() ){
  debug('POSTパラメータがあります。');
  debug('POSTパラメータ'.print_r($_POST,true));

  $u_id = $_SESSION['user_id'];
  $follow_u_id = $_POST['followUserId'];
  try{
    debug('フォロー情報を取得・トライ');
    $dbh = dbConnect();
    $sql = 'SELECT * FROM follow WHERE user_id = :u_id AND follow_id = :f_u_id';
    $data = array(':u_id' => $u_id,':f_u_id' => $follow_u_id);
    $stmt = queryPost($dbh, $sql, $data);
    $rst = $stmt->rowCount();
    if($rst){
      debug('フォロー情報を取得・成功');
      $sql = 'DELETE FROM follow WHERE user_id = :u_id AND follow_id = :f_u_id';
      $data = array(':u_id' => $u_id,':f_u_id' => $follow_u_id);
      $stmt = queryPost($dbh, $sql, $data);
    }else{
      debug('フォロー情報を取得・成功');
      $sql = 'INSERT INTO follow(user_id,follow_id) VALUES(:u_id,:f_u_id)';
      $data = array(':u_id' => $u_id,':f_u_id' => $follow_u_id);
      $stmt = queryPost($dbh, $sql, $data);
    }
  }catch(Exception $e){
    debug('データベースエラー：'.print_r($e->getMessage()));
  }
}else{
  debug('ajaxFollow・不正な値');
  echo 'false';
}