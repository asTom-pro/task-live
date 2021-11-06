<?php

require_once('function.php');

if (!empty($_GET)) {
  $from_path = $_GET['from_path'];
}
?>

<div class="modal">
  <div class="modal-bg js-modal-close"></div>
  <div class="modal-content">
    <p class="title text-center"><span class="modal-title"></span>には、ログインが必要です。</p>
    <div class="btn-container">
      <a href="login.php?redirect=<?php echo sanitize($from_path); ?>" class="btn btn-login">ログインして<span class="modal-title"></span>する</a>
      <a href="signup.php?redirect=<?php echo sanitize($from_path); ?>" class="btn btn-signup">新規登録して<span class="modal-title"></span>する</a>
    </div>
  </div>
</div>

<script>
  $(".js-modal-close").click(function() {
    console.log('ログインモーダルフェードアウトするつもり');
    $('.modal').css('display', 'none');
    return false;
  });
</script>