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
window.remote.updatedelay = function()
{
	var t1 = performance.now();
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (this.readyState == HEADERS_RECEIVED)
		console.log(this.readyState);
	}
	var t2 = performance.now();
}
