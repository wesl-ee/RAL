function flashmessages(elem, messages, delay) {
	var i, j;
	function writer() {
		do { j = Math.floor(Math.random()*messages.length);
		} while (j == i);
		i = j;
		txt = messages[i];
		elem.innerText = txt;
		elem.setAttribute('data-text', txt);
	}
	return setInterval(writer, delay);
}

window.addEventListener('load', function() {
	var xxx = [
		'WIRED',
		'Connection',
		'Existence',
		'GOD',
		'The End',
		'Trance',
	];
	var nodes = document.getElementsByClassName('xxx-welcome');
	for (var i = 0; i < nodes.length; i++) {
		nodes[i].setAttribute('data-text', nodes[i].innerText);
		nodes[i].classList.add('glitch');
		flashmessages(nodes[i], xxx, 300);
	}
	var nodes = document.getElementsByClassName('xxx-success');
	for (var i = 0; i < nodes.length; i++) {
		nodes[i].setAttribute('data-text', nodes[i].innerText);
		nodes[i].classList.add('glitch');
	}
	var nodes = document.getElementsByClassName('xxx-failure');
	for (var i = 0; i < nodes.length; i++) {
		nodes[i].setAttribute('data-text', nodes[i].innerText);
		nodes[i].classList.add('glitch');
	}
});
