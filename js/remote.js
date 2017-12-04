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
window.remote.fetchtopics = function(timeline)
{
	var topics = [];
	topics[0] = {};
	topics[0].num = 1;
	topics[0].updated = '2017-12-04T04:22:07+00:00';
	topics[0].content = 'First!';
	return topics;
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
