/* TRANSITIONS */
window.transitions = [];
window.transitions.newpageclick = function(evt)
{
	var page = this.toPage;
	var collection = this.collection;

	window.transitions.newpage(collection, page);
}
window.transitions.newpage = function(collection, page)
{
	var results_per_page = 5;

	var children = collection.childNodes;
	var alltimelines = window.remote.timelines();
	timelines = alltimelines.slice(results_per_page*page, results_per_page*(page+1));
	var delay = 0;
	for (child in children) {
		if (!children.hasOwnProperty(child)
		|| children[child].nodeType == 3) continue;
		setTimeout(function(node) {
			node.style.visibility = 'hidden';
		}, delay, children[child]);
		delay += 100;
	}
	var i;
	setTimeout(function() {
		var i = 0, j = 0;
		for (child = 0; child < children.length; child++) {
			if (!children.hasOwnProperty(child)
			|| children[child].nodeType == 3) continue;
			if (i >= timelines.length ) {
				children[child].parentNode.removeChild(children[child--]);
			}
			else {
				children[child].innerText = timelines[i++];
			}
		}
		while (typeof timelines[i] !== 'undefined') {
			var timeline = document.createElement('a');
			timeline.innerText = timelines[i++];
			timeline.style.visibility = 'hidden';
			collection.appendChild(timeline);
		}
	}, delay);
	delay += 100;
	setTimeout(function() {
	i = 0;
	delay = 0;
	children = collection.childNodes;
	for (child in children) {
		if (!children.hasOwnProperty(child)
		|| children[child].nodeType == 3) continue;
		setTimeout(function(node) {
			node.style.visibility = 'visible';
		}, delay, children[child]);
		delay += 100;
	}
	}, delay);

	var leftnav = collection.parentNode.getElementsByClassName('leftnav')[0];
	if (!page) {
		leftnav.style.visibility = 'hidden';
	}
	else {
		leftnav.toPage = page - 1;
		leftnav.style.visibility = 'visible';
	}
	var rightnav = collection.parentNode.getElementsByClassName('rightnav')[0];
	if (page + 1 >= alltimelines.length / results_per_page) {
		rightnav.style.visibility = 'hidden';
	}
	else {
		rightnav.toPage = page + 1;
		rightnav.style.visibility = 'visible';
	}
}

/* CREATION / DESTRUCTION */
window.creation = [];
window.creation.sakura = function()
{
	var container = document.createElement('div');
	var video = document.createElement('video');
	var source = document.createElement('source');
	var fallback = document.createElement('img');

	container.id = 'sakura';
	source.src = 'res/splash.webm';
	fallback.src = 'res/fallback.gif';
	video.appendChild(source);
	video.autoplay = true;
	video.loop = true;
	container.appendChild(video);
	container.appendChild(fallback);
	document.body.appendChild(container);
}
window.creation.timeline = function()
{
	var timelines = document.createElement('div');
	var header = document.createElement('h2');
	var latency = document.createElement('span');
	var collection = document.createElement('div');
	var nav = document.createElement('nav');
	var leftnav = document.createElement('a');
	var rightnav = document.createElement('a');


	timelines.className = 'frontcenter';
	timelines.id = 'timelines';
	header.appendChild(
		document.createTextNode('Connected')
	);
	window.remote.updatelatency(latency);
	window.lagInterval = setInterval(function() {
		window.remote.updatelatency(latency);
	}, 60000);
	latency.id = 'latency';
	collection.className = 'collection';
	leftnav.innerText = '◀';
	leftnav.className = 'leftnav';
	rightnav.innerText = '▶';
	rightnav.className = 'rightnav';
	nav.id = 'timelinenav';
	nav.appendChild(leftnav);
	nav.appendChild(rightnav);

	timelines.appendChild(header);
	timelines.appendChild(latency);
	timelines.appendChild(collection);
	timelines.appendChild(nav);

	timelines.style.opacity = 0;
	setTimeout(function() {
		timelines.style.opacity = 1;
	}, 100);
	document.body.appendChild(timelines);


	// Which collection does this nav control?
	leftnav.collection = collection;
	rightnav.collection = collection;
	rightnav.toPage = 1;

	leftnav.addEventListener('click', window.transitions.newpageclick);
	rightnav.addEventListener('click', window.transitions.newpageclick);

	window.transitions.newpage(collection, 0);
}
window.creation.welcome = function()
{
	var welcome = document.createElement('div');
	var header = document.createElement('h1');
	var xxx = document.createElement('span');
	var choicebox = document.createElement('div');
	var enter = document.createElement('a');

	/* Create the title line */
	welcome.id = 'welcome';
	header.appendChild(
		document.createTextNode('Welcome to')
	);
	header.appendChild(
		document.createElement('br')
	);
	welcome.appendChild(header);
	xxx.id = 'xxx';
	xxx.appendChild(
		document.createTextNode('Arcadia')
	);
	header.appendChild(xxx);

	enter.appendChild(
		document.createTextNode('Enter')
	);
	choicebox.className = 'choicebox';
	choicebox.addEventListener('click', function() {
		window.destruction.welcome(welcome)
	});
	choicebox.appendChild(enter);

	welcome.appendChild(header);
	welcome.appendChild(choicebox);

	document.body.appendChild(welcome);
	flashmessages(xxx);
}
window.destruction = [];
window.destruction.sakura = function(sakura)
{
	sakura.style.filter = 'blur(30px)';
	sakura.parentNode.removeChild(sakura);
}
window.destruction.welcome = function(welcome)
{
	clearInterval(window.textinterval);
	welcome.style.fontSize = '999%';
	welcome.style.opacity = '0';
	welcome.style.top = '0';
	setTimeout(function() {
		welcome.parentNode.removeChild(welcome);
	// Calculated from CSS animation time for #welcome
	}, 1100);
	window.creation.timeline();
}
