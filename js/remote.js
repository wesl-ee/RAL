function updatelatency(display)
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
		display.innerText = (Math.round(t2 - t1) + "ms latency");
	}
	}
	xhr.open('GET', '/');
	xhr.send();
}
