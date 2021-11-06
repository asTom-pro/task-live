$(document).ready( function(){
  // 文字数カウント
  if($('#js-count').val().length){
    var count = $('#js-count').val().length;
    $('.show-count').text(count);
  }
 });
