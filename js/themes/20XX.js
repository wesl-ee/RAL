function flashmessages(elem, messages, delay) {
	var i, j;
	function writer() {
		do { j = Math.floor(Math.random()*messages.length);
		} while (j == i);
		i = j;
		txt = messages[i];
		elem.innerText = txt;
	}
	return setInterval(writer, delay);
}

window.addEventListener('load', function() {
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
		'Worlds',
		'Digital Heaven'
	];
	var nodes = document.getElementsByClassName('xxx-welcome');
	for (var i = 0; i < nodes.length; i++) {
		flashmessages(nodes[i], xxx, 300);
	}
	xxx = [
		'Sent',
		'Uploaded',
		'Exported',
		'Posted',
		'Exchanged',
		'Transmitted',
		'Uplinked',
		'Carried',
		'Relayed',
		'Delivered',
		'Accepted',
		'Entered',
		'Comunicated',
	]
	var nodes = document.getElementsByClassName('xxx-success');
	for (var i = 0; i < nodes.length; i++) {
		flashmessages(nodes[i], xxx, 100);
	}
});
