window.remote = [];
window.remote.timelines = function()
{
	return [
		'Anime',
		'Work',
		'Music',
		'Warez',
		'Hackz',
		'PC',
		'Tokyo'
	];
}
window.remote.rendertopics = function(timeline, reader)
{
	var xhr = new XMLHttpRequest();
	var t1 = performance.now();
	xhr.onreadystatechange = function() {
	if (this.readyState == 2) {
		var latency = document.getElementById('latency');
		var t2 = performance.now();
		latency.classList.remove('error');
		latency.innerText = Math.round(t2 - t1) + "ms latency";
	}
	if (this.readyState == 4)
	if (this.status == 200)
	if (this.responseText) {
		var topics = JSON.parse(this.responseText);
		for (var i = 0; i - topics.length; i++) {
			var topic = topics[i];
			console.log(JSON.stringify(topic));
			window.render.newtopic(reader, topic);
		} }
		else {
			var errors = ++window.render.connerror.errors;
			if (errors > 5) {
				window.render.connerror('Lost connection');
			} else {
				window.render.connerror('Out of sync');
				setTimeout(function() {
					window.remote.rendertopics(timeline, reader);
				}, 1000);
			}
	} }
	var uri = '?fetch&timeline=' + timeline;
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
window.remote.updatelatency = function(display)
{
	var t1 = performance.now();
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
	// HEADERS_RECEIVED
	if (this.readyState == 2) {
		var t2 = performance.now();
		display.innerText = Math.round(t2 - t1) + "ms latency";
	}
	}
	xhr.open('GET', '/');
	xhr.send();
}
window.remote.subscribetopic = function(timelinename, topicnum)
{
	window.xhr = new XMLHttpRequest();
	// i holds the length of the last response
	var i = 0;

	// Long polling set-up
	window.xhr.timeout = 60000;
	window.xhr.ontimeout = function() {
		window.remote.subscribetopic(timelinename, topicnum);
	}

	window.xhr.onreadystatechange = function() {
	if (this.readyState == 3) {
		// Read the most recent topic
		var newtopic = JSON.parse(this.responseText.substring(i));
		i = this.responseText.length;

		// For sanity
		console.log(newtopic);

		//For valor
		var reader = document.getElementById('reader');
	}
	if (this.readyState == 4) {
		window.remote.subscribetopic(timelinename, topicnum);
	} }

	var uri = '?subscribe&timeline=' + timelinename + '&topic=' + topicnum;
	window.xhr.open('GET', '/courier.php' + uri);
	window.xhr.send();
}
window.remote.subscribetimeline = function(timelinename, reader)
{
	window.xhr = new XMLHttpRequest();
	// i holds the length of the last response
	var i = 0;

	// Long polling set-up
	window.xhr.timeout = 60000;
	window.xhr.ontimeout = function() {
		window.remote.subscribetimeline(timelinename, reader);
	}

	window.xhr.onreadystatechange = function() {
	if (this.readyState == 3) {
		// Read the most recent topic
		var newtopic = JSON.parse(this.responseText.substring(i));
		i = this.responseText.length;

		// For sanity
		console.log(newtopic);

		// For Vorkuta
		window.render.newtopic(reader, newtopic);
	}
	if (this.readyState == 4) {
		console.log('Unsubscribed from ' + timelinename);
//		window.remote.subscribetopic(timelinename, topicnum);
	} }

	var uri = '?subscribe&timeline=' + timelinename;
	window.xhr.open('GET', '/courier.php' + uri);
	window.xhr.send();
}
