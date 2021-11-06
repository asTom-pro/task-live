$(function () {

  // setInterval(function () { countRoomUser() }, 10000);
  var loc = location.pathname,
    dir = loc.substring(0, loc.lastIndexOf('/')) + '/';
  countRoomUser();
  function countRoomUser() {
    $('.room-link').each(function (i, elem) {
      let uri,
          userNum;
      uri = dir + $(elem).attr('href');
        userNum = getCount(uri)
        $(elem).find('.member-num').text(userNum);
    })
  }
  function getCount(uri) {
    $(function () {
      $.ajax({
        type: 'POST',
        url: 'getCount.php',
        data: {
          uri: uri
        }
      }).done(function (data) {
        return data;
      }).fail(function (msg) {
      })
    });
  }
})

