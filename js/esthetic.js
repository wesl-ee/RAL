function flashmessages(e) {
	var xxx = [
		'The future',
		'Virtual Reality',
		'20XX',
		'New society',
		'Now',
		'Forever',
		'Tomorrow',
		'Yesterday',
		'The Void',
		'Nothing',
		'Arcadia',
		'Everything',
		'Digital Heaven',
	]
	var speed = 400;
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
	window.textinterval = setInterval(writer, speed);
}
