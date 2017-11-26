function flashmessages(e) {
	var xxx = [
		'The future',
		'Virtual Reality',
		'20XX',
		'New society',
		'Now',
		'Forever',
		'Today',
		'Tomorrow',
		'Yesterday',
		'The Void',
		'Nothing',
		'Everything',
	]
	var speed = 80;
	var i = 0;
	var txt = xxx[Math.floor(Math.random()*xxx.length)];
	function typeWriter() {
		if (i < txt.length) {
			e.innerHTML += txt.charAt(i);
			i++;
			setTimeout(typeWriter, speed);
		}
		else {
			i = 0;
			e.innerHTML = '&nbsp';
			txt = xxx[Math.floor(Math.random()*xxx.length)];
			setTimeout(typeWriter, speed);
		}
	}
	typeWriter();
}
