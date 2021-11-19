<?php
require('function.php');
debugLogStart();

if (empty($_GET)) {
  debug('不正な値が入力されたので、トップページに遷移します。');
  header('Location:index.php');
  exit();
}else{
  if(!empty($_GET['token'])){
    $auth_token = $_GET['token'];
  }else{
    debug('不正な値が入力されたので、トップページに遷移します。');
    header('Location:index.php');  
    exit();
  }

    debug('ユーザー登録・トライします');
    try {

      $dbh = dbConnect();
      $sql = 'SELECT * FROM verify_email WHERE auth_token = :token';
      $data = array(':token' => $auth_token);
      $stmt = queryPost($dbh, $sql, $data);
      if(!$stmt){
        // 入力されたトークンが存在しない場合
        debug('不正な値が入力されたので、トップページに遷移します。');
        header('Location:index.php');  
        exit();    
      }else{
        $rst = $stmt->fetch(PDO::FETCH_ASSOC);

        $email = $rst['email'];
        $pass = $rst['password'];
        $token_created_date = $rst['created_date'];
        if ((strtotime($token_created_date) + (60 * 60 * 24)) < strtotime(date("Y/m/d H:i:s"))) {
          // 認証トークン発行から２４時間経過した場合
          debug('認証トークン発行から２４時間経過したので、トップページに遷移します。');
          header('Location:index.php');    
        }else{
          // 認証トークン発行から２４時間経過してない場合
          try{
            debug('ユーザー登録・トライ');
            
            $dbh = dbConnect();
            $sql = 'INSERT INTO user(email,`password`) VALUES(:email,:pass)';
            $data = array(':email' => $email, ':pass' => $pass);
            $stmt =  queryPost($dbh, $sql, $data);
            if ($stmt) {
              debug('クエリ成功。');
              $ses_time = 60 * 60 * 24;
              $_SESSION['login_limit'] = time() + $ses_time;
              $_SESSION['user_id'] = $dbh->lastInsertid();
              if (!empty($redirect_path)) {
                debug('指定されたパスに遷移します');
                header('Location:' . $redirect_path);
                exit();
              } else {
                debug('部屋一覧ページに遷移します');
                header('Location:index.php');
                exit();
              }
            } else {
              debug('クエリ失敗。');
            }

          }catch(Exception $e){
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = ERR_QUERUY;      
          }
        }

      }



    } catch (Exception $e) {
      debug('データベースエラー：' . $e->getMessage());
  }
}
