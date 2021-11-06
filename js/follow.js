$(function () {

$('.follow-member .btn-active').text('フォロー中');
$('.sub-bar-profile .btn-active').text('フォロー中');

var $btn_follow ;
    $btn_follow = $('.follow-members .btn-follow') || $('.btn-follow');
  $btn_follow.click(function() {
    $this = $(this);
    follow_user_id = $this.data('userid');
    $.ajax({
      type: "POST",
      url: "ajaxFollow.php",
      data: {
        followUserId: follow_user_id
      }
    }).done(function(data) {
        if(data != 'false'){
          $this.toggleClass('btn-active');
          if($this.hasClass('btn-active')){
            $this.text('フォロー中');
          }else{
            $this.text('フォロー');
          }
        }else{
          login_modal('フォロー');
        }
      }).fail(function(msg) {
      })
    })
})