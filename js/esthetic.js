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
		'Sanctuary',
		'Heaven',
	]
	var speed = 1000;
	var i, j;
	function writer() {
		do {
			j = Math.floor(Math.random()*xxx.length);
		} while (j == i);
		i = j;
		txt = xxx[i];
		e.innerText = txt;
		e.setAttribute('data-text', txt);
	}
	writer();
	setInterval(writer, speed);
}
