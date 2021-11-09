$(function () {

  function choose_at_random(arrayData) {
    var arrayIndex = Math.floor(Math.random() * arrayData.length);
    return arrayData[arrayIndex];
}

  var  recommendTimeMin =[25,30,60,90];
  $('#js-btn-recommend').click(function () {
    $('.set-time-minute option:selected').removeAttr('selected');
    $('.set-time-hour option:selected').removeAttr('selected');
    var time_h = 0;
    var time_m = 0;
    time_m = choose_at_random(recommendTimeMin);
    if(time_m >= 60){
      time_h = Math.floor(time_m / 60);
      time_m = time_m % 60;
    }
    $selectedOptionM =  $(`.set-time-minute option[value="${time_m}"]`);
    $selectedOptionM.prop('selected', true);
    $selectedOptionH =  $(`.set-time-hour option[value="${time_h}"]`);
    $selectedOptionH.prop('selected', true);
  })

  $('.js-room-name-input-count').on('keyup',function(){
    var char_num = $(this).val().length;
    console.log(char_num);
    $('.js-show-room-name-count').text(char_num);

    if( char_num > 20){
      $('.room-name-title').addClass('is-over-count');
      $('.room-name-title').removeClass('is-in-count');
    }else{
      $('.room-name-title').addClass('is-in-count');
      $('.room-name-title').removeClass('is-over-count');
    }
  })


  $('.js-room-tag-input-count').on('keyup',function(){
    var $this = $(this);
    var str = $this.val();
    if($this.prop("name") === "tag" ){
      str = str.replace(/\s+/g, "");
    }
    var char_num = str.length;

    console.log(char_num);
    $('.js-show-room-tag-count').text(char_num);
    if( char_num > 20){
      $('.room-tag-title').addClass('is-over-count');
      $('.room-tag-title').removeClass('is-in-count');
    }else{
      $('.room-tag-title').addClass('is-in-count');
      $('.room-tag-title').removeClass('is-over-count');
    }
  })


})