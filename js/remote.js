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
		for (var i = topics.length - 1; i + 1; i--) {
			var topic = topics[i];
			console.log(JSON.stringify(topic));
			var article = document.createElement('article');
			var updated = document.createElement('time');
			var num = document.createElement('span');
			var content = document.createElement('content');

			// Date formatting
			var time = new Date(topic.modified);
			updated.innerText = (time.getMonth() + 1)
			+ '/' + time.getDate()
			+ ' ' + time.getHours()
			+ ':' + time.getMinutes();

			article.className = 'topic';
			article.id = topic.id;
			updated.dateTime = topic.modified;
			num.innerText = 'No. ' + topic.id;
			num.className = 'num';
			content.innerText = topic.content;
			content.className = 'content';

			content.addEventListener('click',
				window.handlers.open, this
			);

			article.appendChild(updated);
			article.appendChild(num);
			article.appendChild(content);

			reader.appendChild(article);
		} }
		else {
			window.render.connerror('Could not receive topics');
			setTimeout(function() {
				window.remote.rendertopics(timeline, reader);
			}, 1000);
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
