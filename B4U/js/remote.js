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
		movelatbar(latency);
	} }
	xhr.open('GET', '/');
	xhr.send();
}
function movelatbar(latency)
{
	var lat = document.getElementById('latency');
	var excellent_interval = 50;
	var good_interval = 200;
	var bad_interval = 500;

	var text = lat.getElementsByClassName('text')[0];
	var bar = lat.getElementsByClassName('bar')[0];

	if (!latency) {
		bar.className = 'bar disconnected';
	}
	else if (latency < excellent_interval) {
		text.innerText = latency + ' ms';
		bar.className = 'bar excellent';
	}
	else if (latency < good_interval) {
		text.innerText = latency + ' ms';
		bar.className = 'bar good';
	}
	else if (latency < bad_interval) {
		text.innerText = latency + ' ms';
		bar.className = 'bar bad';
	}
}
function oos()
{
	document.getElementById('latency').className = 'error';
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

		// For sanity
		console.log(this.responseText);

		// For Vorkuta
		if (msg.type == 'POST') {
			var posthtml = msg.body.content;
			var post = appendToReader(reader, posthtml);
			console.log(post);
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
	var mostpost = reader.getAttribute('data-mostpost');

	xhr.timeout = 15000;
	xhr.ontimeout = function() {
		movelatbar(false); return false;
	}

	var t1;
	// Updating latency
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	if (this.readyState == 2) {
		var t2 = performance.now();
		movelatbar(Math.round(t2 - t1));
	}
	if (this.readyState == 4)
	if (this.status == 200)
	if (this.responseText) {
		var posts = JSON.parse(this.responseText);
		if (!verifyreader(reader, posts)) {
			movelatbar(false); return false;
		}
	} }
	var uri = '?verify';
	if (continuity)
		uri += '&continuity=' + continuity;
	if (topic)
		uri += "&topic=" + topic;
	// Prevent caching or throttling
	uri += '&' + Math.random().toString(36);
	if (mostpost)
		uri += '&mostpost=' + mostpost;
	xhr.open('GET', '/api.php' + uri);

	// Synchronous: we care about the result
	xhr.send(false);
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
		movelatbar(Math.round(t2 - t1));
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
