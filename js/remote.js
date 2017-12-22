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
function fetchtopics(timeline, reader)
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
			console.log(JSON.stringify(topic));
			newtopic(reader, topic);
		}
	} }
	var uri = '?fetch&timeline=' + timeline;
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
function fetchposts(timeline, topic, reader)
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
			console.log(JSON.stringify(posts));
//
		}
	} }
	var uri = '?fetch&timeline=' + timeline + "&topic=" + topic;
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
function subscribetopic(timeline, topic, reader)
{
	window.xhr = new XMLHttpRequest();
	// i holds the length of the last response
	var i = 0;

	// Long polling set-up
	window.xhr.timeout = 60000;
	window.xhr.ontimeout = function() {
		subscribetopic(timeline, topic);
	}

	window.xhr.onreadystatechange = function() {
	if (this.readyState == 3) {
		// Read the most recent topic
		var post = JSON.parse(this.responseText.substring(i));
		i = this.responseText.length;

		// For sanity
		console.log(post);

		// For Vorkuta
		newpost(reader, post);
	}
	if (this.readyState == 4) {
		console.log('Unsubscribed from ' + timeline + ' topic No. ' + topic);
	} }

	var uri = '?subscribe&timeline=' + timeline + '&topic=' + topic;
	window.xhr.open('GET', '/courier.php' + uri);
	window.xhr.send();
}
function subscribetimeline(timelinename, reader)
{
	window.xhr = new XMLHttpRequest();
	// i holds the length of the last response
	var i = 0;

	// Long polling set-up
	window.xhr.timeout = 60000;
	window.xhr.ontimeout = function() {
		subscribetimeline(timelinename, reader);
	}

	window.xhr.onreadystatechange = function() {
	if (this.readyState == 3) {
		// Read the most recent topic
		var topic = JSON.parse(this.responseText.substring(i));
		i = this.responseText.length;

		// For sanity
		console.log(topic);

		// For Vorkuta
		newtopic(reader, topic);
	}
	if (this.readyState == 4) {
		console.log('Unsubscribed from ' + timelinename);
	} }

	var uri = '?subscribe&timeline=' + timelinename;
	window.xhr.open('GET', '/courier.php' + uri);
	window.xhr.send();
}
