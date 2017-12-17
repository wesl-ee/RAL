function flashmessages(elem, messages) {
	var speed = 400;
	var i, j;
	function writer() {
		do { j = Math.floor(Math.random()*messages.length);
		} while (j == i);
		i = j;
		txt = messages[i];
		elem.innerText = txt;
	}
	setInterval(writer, speed);
}
