$(function () {
  var arg = new Object;
  url = location.search.substring(1).split('&');
  for (i = 0; url[i]; i++) {
    var k = url[i].split('=');
    arg[k[0]] = k[1];
  }

  function makeTime(num) {
    var timeH = Math.floor(num % (24 * 60 * 60) / (60 * 60));
    var timeM = Math.floor(num % (24 * 60 * 60) % (60 * 60) / 60);
    var timeS = num % (24 * 60 * 60) % (60 * 60) % 60;
    if (String(timeH).length == 1) {
      timeH = '0' + timeH;
    }
    if (String(timeM).length == 1) {
      timeM = '0' + timeM;
    }
    if (String(timeS).length == 1) {
      timeS = '0' + timeS;
    }
    var timeDMS = timeH + ':' + timeM + ':' + timeS;
    return timeDMS;
  }

  let limit_time_sec = $('.limit-time-sec').text();
  // 制限時間毎秒表示
  $remain_time_num = $('.remain-time-num');
  const countDown = () => {
    timeDMS = makeTime(limit_time_sec);
    $remain_time_num.text(timeDMS);
    if (limit_time_sec <= 0) {
      if (!$('.end-room').hasClass('ended-room')) {
        $('.end-room').removeClass('d-none');
      }
    }
    limit_time_sec -= 1;
    const timeoutId = setTimeout(countDown, 1000);
    if (limit_time_sec < 0) {
      clearTimeout(timeoutId);
    }
  }
  countDown();
  // 制限時間の残りを円で表示
  let limit_time_max = $('.limit-time-max').text();
  let limit_time_per = (90 + 360 * (1 - (limit_time_sec / limit_time_max)));
  clockCSS = new Object();
  clockCSS.animation = `rotation-s ${limit_time_sec}s linear 1 forwards`;
  clockCSS.transform = `rotate(${limit_time_per}deg)`;
  $clock_hand = $('.clock-hand');
  $clock_hand.css(clockCSS);

  // コメント更新
  var r_id = arg.r_id;
  var comment,
    update_date = new Date();
  update_date_s = 0;
  update_date_s = update_date.getTime();
  update_date_s = Math.floor(update_date_s / 1000);
  $btn_comment = $('.btn-comment') || null;
  $btn_comment.click(function () {
    comment = $('.textarea').val();
    if (comment.length) {
      $.ajax({
        type: 'POST',
        url: 'ajaxComment.php',
        data: {
          comment: comment,
          r_id: r_id,
          update_date: update_date_s
        }
      }).done(function (data) {
        $.each(data, function (key, val) {
          if (!(val.user_img)) {
            val.user_img = 'img/user-sample.svg';
          }
          // サーバー側で自分が他人かで分ける
          if (val.is_mine = 1) {
            // 自分
            $('.room-board-user').append('<div class="right-user board-user"><div class="user-info"><img src="' + val.user_img + '" alt="" class="user-img"><sapn class="user-name">' + val.user_name + '</span></div><div class="comment-container"><p class="comment"><span class="user-ballon"></span>' + val.comment + '</p></div></div>');
          } else {
            // 他人
            $('.room-board-user').append('<div class="left-user board-user"><div class="user-info"><img src="' + val.user_img + '" alt="" class="user-img"><sapn class="user-name">' + val.user_name + '</span></div><div class="comment-container"><p class="comment"><span class="user-ballon"></span>' + val.comment + '</p></div></div>');
          }
        })
        $('.textarea').val('');
        comment = null;
        update_date = new Date();
        update_date_s = update_date.getTime();
        update_date_s = Math.floor(update_date_s / 1000);
      }).fail(function (msg) {
      })
    }
  })
})