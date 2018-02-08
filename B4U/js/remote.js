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
		netmessage(Math.round(t2 - t1) + "ms latency");
	}
	}
	xhr.open('GET', '/');
	xhr.send();
}
function netmessage(msg)
{
	var lat = document.getElementById('latency');
	lat.innerText = msg;
}
function oos()
{
	netmessage('Out of sync');
	document.getElementById('latency').className = 'error';
}
function fetchtopics(continuity, reader)
{
	var xhr = new XMLHttpRequest();
	var t1;
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	if (this.readyState == 2) {
		var t2 = performance.now();
		netmessage(Math.round(t2 - t1) + "ms latency");
	}
	if (this.readyState == 4)
	if (this.status == 200)
	if (this.responseText) {
		var topics = JSON.parse(this.responseText);
		for (var i = 0; i - topics.length; i++) {
			var topic = topics[i];
			newtopic(reader, topic);
		}
	} }
	var uri = '?fetch&continuity=' + continuity;
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
function fetchposts(continuity, topic, reader)
{
	var xhr = new XMLHttpRequest();
	var t1;
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	if (this.readyState == 2) {
		var t2 = performance.now();
		netmessage(Math.round(t2 - t1) + "ms latency");
	}
	if (this.readyState == 4)
	if (this.status == 200)
	if (this.responseText) {
		var posts = JSON.parse(this.responseText);
		for (var i = 0; i - posts.length; i++) {
			var post = posts[i];
			newpost(reader, post)
		}
	} }
	var uri = '?fetch&continuity=' + continuity + "&topic=" + topic;
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
function subscribetopic(continuity, topic, reader)
{
	// Confirm that we have a valid collection of posts
	verifyposts(reader, continuity, topic);
	xhr = new XMLHttpRequest();

	// Long polling set-up
	xhr.timeout = 15000;
	xhr.ontimeout = function() {
		subscribetopic(continuity, topic, reader);
	}

	xhr.onload = function() {
		// Read the most recent topic
		var msg = JSON.parse(this.responseText);

		// For sanity
		console.log(this.responseText);

		// For Vorkuta
		if (msg.type == 'POST') {
			var post = msg.body;
			doctitlenotify();
			newpost(reader, post);
		}
		subscribetopic(continuity, topic, reader);
	}

	var uri = '?subscribe&continuity=' + continuity + '&topic=' + topic
	// Prevent caching or throttling
	+ '&' + Math.random().toString(36);
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
// Prob. just make one subscribe(reader) function w/ optional params
function subscribeall(reader)
{
	// Confirm that we have a valid collection of posts
	verifyposts(reader);
	xhr = new XMLHttpRequest();

	// Long polling set-up
	xhr.timeout = 15000;
	xhr.ontimeout = function() {
		subscribeall(reader);
	}

	xhr.onload = function() {
		// Read the most recent topic
		var msg = JSON.parse(this.responseText);

		// For sanity
		console.log(this.responseText);

		// For Vorkuta
		if (msg.type == 'POST') {
			var post = msg.body;
			doctitlenotify();
			newfrontpagepost(reader, post);
		}
		subscribeall(reader);
	}

	var uri = '?subscribe'
	// Prevent caching or throttling
	+ '&' + Math.random().toString(36);
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
function verifyposts(reader, continuity, topic)
{
	var xhr = new XMLHttpRequest();
	var mostpost = reader.getAttribute('data-mostpost');

	xhr.timeout = 15000;
	xhr.ontimeout = function() {
		oos(); return false;
	}

	var t1;
	// Updating latency
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	if (this.readyState == 2) {
		var t2 = performance.now();
		netmessage(Math.round(t2 - t1) + "ms latency");
	}
	if (this.readyState == 4)
	if (this.status == 200)
	if (this.responseText) {
		var posts = JSON.parse(this.responseText);
		if (!verifyreader(reader, posts)) {
			oos(); return false;
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
	xhr.open('GET', '/courier.php' + uri);

	// Synchronous: we care about the result
	xhr.send(false);
}
function subscribecontinuity(continuity, reader)
{
	xhr = new XMLHttpRequest();
	// i holds the length of the last response
	var i = 0;

	// Long polling set-up
	xhr.timeout = 15000;
	xhr.ontimeout = function() {
		subscribecontinuity(continuity, reader);
	}

	xhr.onload = function() {
		// Read the most recent topic
		var msg = JSON.parse(this.responseText);

		// For sanity
		console.log(this.responseText);

		// For Vorkuta
		if (msg.type == 'POST') {
			var topic = msg.body;
			doctitlenotify();
			newtopic(reader, topic);
		}
		subscribecontinuity(continuity, reader);
	}

	var uri = '?subscribe&continuity=' + continuity;
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
