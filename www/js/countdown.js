window.setTimeout(downOne, 1000);

function downOne() {
	var countdown = document.getElementById('countdown')
	var currUntil = countdown.firstChild.nodeValue;
	var nextUntil = currUntil - 1;
	countdown.removeChild(countdown.firstChild);
	countdown.appendChild(document.createTextNode(nextUntil));
	if (nextUntil > 0) window.setTimeout(downOne, 1000);
}
