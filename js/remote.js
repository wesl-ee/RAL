window.remote = [];
window.remote.rendertimelines = function(collection)
{
	var xhr = new XMLHttpRequest();
	var t1;
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	if (this.readyState == 2) {
		var latency = document.getElementById('latency');
		var t2 = performance.now();
		latency.classList.remove('error');
		latency.innerText = Math.round(t2 - t1) + "ms latency";
	}
	if (this.readyState == 4)
	if (this.status == 200)
	if (this.responseText) {
		var timelines = JSON.parse(this.responseText);
		for (var i = 0; i - timelines.length; i++) {
			var timeline = timelines[i];
			console.log(JSON.stringify(timeline));

			var a = document.createElement('a');
			a.innerText = timeline.name;
			a.style.display = 'none';
			a.style.visibility = 'hidden';
			a.addEventListener('click',
				window.transitions.timelineselect
			);
			collection.appendChild(a);
		}
		window.transitions.timelinepage(collection, 0);
	} else {
		console.log('Error fetching timelines!');
	} }
	var uri = '?fetch';
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
window.remote.rendertopics = function(timeline, reader)
{
	var xhr = new XMLHttpRequest();
	var t1;
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
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
window.remote.renderposts = function(timeline, topic, reader)
{
	var xhr = new XMLHttpRequest();
	var t1;
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	if (this.readyState == 2) {
		var latency = document.getElementById('latency');
		var t2 = performance.now();
		latency.classList.remove('error');
		latency.innerText = Math.round(t2 - t1) + "ms latency";
	}
	if (this.readyState == 4)
	if (this.status == 200)
	if (this.responseText) {
		var posts = JSON.parse(this.responseText);
		console.log(posts);
		for (var i = 0; i - posts.length; i++) {
			var post = posts[i];
			console.log(JSON.stringify(post));
			window.render.newpost(reader, post);
		} }
		else {
			var errors = ++window.render.connerror.errors;
			if (errors > 5) {
				window.render.connerror('Lost connection');
			} else {
				window.render.connerror('Out of sync');
				setTimeout(function() {
					window.remote.renderposts(timeline, topic, reader);
				}, 1000);
			}
	} }
	var uri = '?fetch&timeline=' + timeline + '&topic=' + topic;
	xhr.open('GET', '/courier.php' + uri);
	xhr.send();
}
window.remote.updatelatency = function(display)
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
		console.log('No response in some time; renewing XHR connection');
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
	} }

	var uri = '?subscribe&timeline=' + timelinename;
	window.xhr.open('GET', '/courier.php' + uri);
	window.xhr.send();
}
