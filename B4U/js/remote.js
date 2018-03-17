function updatelatency()
{
	var xhr = new XMLHttpRequest();

	var t1;
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	// HEADERS_RECEIVED
	if (this.readyState == 2) {
		var t2 = performance.now();
		var latency = Math.round(t2 - t1);
		updatelatbar(latency);
	} }
	xhr.open('GET', '/');
	xhr.send();
}
function updatelatbar(latency)
{
	var lat = document.getElementById('latency');

	var text = lat.getElementsByClassName('text')[0];
	var bar = lat.getElementsByClassName('bar')[0];

	if (latency === false) {
		bar.className = 'bar disconnected';
	}
	else {
		text.innerText = latency + ' ms';
		bar.className = 'bar connected';
	}
}
function outofsync()
{
	var lat = document.getElementById('latency');
	var text = lat.getElementsByClassName('text')[0];
	var bar = lat.getElementsByClassName('bar')[0];
	bar.className = 'bar error';
	text.innerText = 'Out of Sync';
}
function subscribe(reader, continuity, topic)
{
	var realtimeurl = reader.getAttribute('data-realtimeurl');
	verifyposts(reader, continuity, topic);
	var xhr = new XMLHttpRequest();

	// Long polling set-up
	xhr.timeout = 15000;
	xhr.ontimeout = function() {
		subscribe(reader, continuity, topic);
	}
	xhr.onload = function() {
		// Read the most recent message
		var msg = JSON.parse(this.responseText);

		// For Vorkuta
		if (msg.type == 'POST') {
			var posthtml = msg.body.content;
			var post = appendToReader(reader, posthtml);
			doctitlenotify();
			highlightnew(post); notifyuser(post);
		}
		subscribe(reader, continuity, topic);
	}

	xhr.open('GET', realtimeurl);
	xhr.send();
}
function verifyposts(reader, continuity, topic)
{
	var xhr = new XMLHttpRequest();

	xhr.timeout = 15000;
	xhr.ontimeout = function() {
		updatelatbar(false);
	}

	var t1;
	// Updating latency
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	if (this.readyState == 2) {
		var t2 = performance.now();
		updatelatbar(Math.round(t2 - t1));
	}
	if (this.readyState == 4)
	if (this.status == 200)
	if (this.responseText) {
		var posts = JSON.parse(this.responseText);
		if (!verifyreader(reader, posts)) {
			outofsync();
		}
	} }
	var verifyurl = reader.getAttribute('data-verifyurl');
	// Prevent caching or throttling
	xhr.open('GET', verifyurl);
	xhr.send();
}
function previewPost(text, container)
{
	var xhr = new XMLHttpRequest();
	var t1;
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	if (this.readyState == 2) {
		var t2 = performance.now();
		updatelatbar(Math.round(t2 - t1));
	}
	if (this.readyState == 4)
	if (this.status == 200) {
		var markup = this.responseText;
		container.innerHTML = markup;
	} }
	var uri = 'text=' + encodeURI(text);
	xhr.open('POST', '/api.php?preview');
	xhr.setRequestHeader("Content-type",
	"application/x-www-form-urlencoded");
	xhr.send(uri);
}
