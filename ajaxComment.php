<?php

require('function.php');

debug('AjaxComment');

// ユーザーID 1はゲスト
$u_id =  1;
if(!empty($_SESSION)){
  $u_id = $_SESSION['user_id'];
}
if (!empty($_POST)) {
  debug('POSTパラメータがあります。');
  debug('POSTパラメータ' . print_r($_POST, true));
  $comment = $_POST['comment'];
  $r_id = $_POST['r_id'];
  $update_date =  $_POST['update_date'];
  if($update_date != 0){
    $update_date =  $_POST['update_date'];
  }else{
    $update_date = 0;
  }
  try {
    debug('コメント情報を保存・トライ');
    $dbh = dbConnect();
    $sql = 'INSERT INTO board(room_id,user_id,comment) VALUES(:r_id,:u_id,:comment)';
    $data = array(':r_id' => $r_id, ':u_id' => $u_id, ':comment' => $comment);
    $stmt = queryPost($dbh, $sql, $data);
    if ($stmt) {
      debug('コメント情報を保存・成功');
      debug('ajaxComment はじめ');
      $boardInfo = array();
      $boardInfo = getBoard($r_id,$update_date);
      function convertUidToformedUinfo($user_id)
      {
        // 配列を整える（jsに渡してokな値に変える）
        $userInfo = getUser($user_id);
        $userName = $userInfo['user_name'];
        $userImg = $userInfo['profile_img'];
        $formedUserInfo = array();
        $formedUserInfo['user_name'] = $userName;
        $formedUserInfo['user_img'] = $userImg;
        return $formedUserInfo;
      }
      foreach ($boardInfo as $key => $val) {
        $userInfo =  convertUidToformedUinfo($val['user_id']);
        $boardInfo[$key]['user_name'] = $userInfo['user_name'];
        $boardInfo[$key]['user_img'] = $userInfo['user_img'];
        if ($u_id == $val['user_id']) {
          $boardInfo[$key]['is_mine'] = true;
        } else {
          $boardInfo[$key]['is_mine'] = false;
        }
        unset($boardInfo[$key]['user_id']);
      }
      debug('ajaxComment 終わり');
      header('Content-type: application/json; charset=UTF-8');
      echo json_encode($boardInfo);
    } else {
      debug('コメント情報を保存・失敗');
    }
  } catch (Exception $e) {
    debug('データベースエラー：' . print_r($e->getMessage()));
  }
}
