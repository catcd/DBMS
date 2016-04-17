	// Cache some selectors

	var clock = $('#clock'),
		alarm = clock.find('.alarm'),
		ampm = clock.find('.ampm');

	// Map digits to their names (this will be an array)
	var digit_to_name = 'zero one two three four five six seven eight nine'.split(' ');

	// This object will hold the digit elements
	var digits = {};

	// Positions for the hours, minutes, and seconds
	var positions = [
		'h1', 'h2', ':', 'm1', 'm2', ':', 's1', 's2'
	];

	// Generate the digits with the needed markup,
	// and add them to the clock

	var digit_holder = clock.find('.digits');

	$.each(positions, function(){

		if(this == ':'){
			digit_holder.append('<div class="dots">');
		}
		else{

			var pos = $('<div>');

			for(var i=1; i<8; i++){
				pos.append('<span class="d' + i + '">');
			}

			// Set the digits as key:value pairs in the digits object
			digits[this] = pos;

			// Add the digit elements to the page
			digit_holder.append(pos);
		}

	});
	var timer = null;
	var interval = 1000,time,value = 0,sec = 0, min =0, hour =0;

  
  
function timeToString(sec_num) {   
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    time    = hours + minutes + seconds;
    return time;
}
function setDigits(_value) {
	var result = timeToString(value);
	digits.h1.attr('class', digit_to_name[result[0]]);
	digits.h2.attr('class', digit_to_name[result[1]]);
	digits.m1.attr('class', digit_to_name[result[2]]);
	digits.m2.attr('class', digit_to_name[result[3]]);
	digits.s1.attr('class', digit_to_name[result[4]]);
	digits.s2.attr('class', digit_to_name[result[5]]);
	
}
 setDigits(0);
function start() {
  if (timer !== null) return;
  timer = setInterval(function() {
    value = value + 1;
	setDigits(value);	
	//console.log(value);   
  }, interval);
  
};
function lapse1() {	
	return value;	
}

function stop() {	
  clearInterval(timer);
  timer = null
}
function lapse2() {
	var endtime = value;	
	stop();
	return endtime;
}
