CSS.registerProperty({
	name: '--pos', 
	syntax: '<length-percentage>', 
	initialValue: '0%', 
	inherits: true
});

$(function () {

var limit_time_secs = $('.limit-time-sec').text();
var sec = (limit_time_secs*2)+"s,"+(limit_time_secs)+"s"; 
$clock = $('.clockclock');
$clock.css("animation-duration", sec);
})

