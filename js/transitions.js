window.handlers = [];
window.handlers.opentopic = function(evt)
{
	// Clicking twice doesn't make it load faster!
	evt.target.removeEventListener('click', window.handlers.opentopic);
	var reader = document.getElementById('reader');
	var article = evt.target.parentNode;
	var topicnum = article.id;
	var timeline = reader.getAttribute('data-timeline');

	console.log('Opening topic ' + topicnum + ' at ' + timeline);
	window.transitions.opentopic(timeline, topicnum);
}
window.handlers.close = function(evt)
{
	evt.target.removeEventListener('click', window.handlers.close);
	var article = evt.target.parentNode;
	var reader = document.getElementById('reader');

	reader.className = 'timeline';
}
/* TRANSITIONS */
window.transitions = [];
/*
* Controls what happens when a user selects a timeline
*/
window.transitions.timelineselect = function(evt)
{
	var button = evt.target;
	var timeline = button.innerText;
	var reader = document.getElementById('reader');

	if (!reader) {
		document.getElementById('timelines').classList.add('sidebar');
		document.getElementById('timelines').classList.remove('frontcenter');

		window.creation.reader(timeline);
	}
	else {
		window.transitions.timelinejump(timeline);
	}
}
window.transitions.timelinejump = function(timeline)
{
	var reader = document.getElementById('reader');
	var rightpanel = document.getElementById('rightpanel');

	var animationtime = 800;
	rightpanel.style.right = '-100%';

	setTimeout(function() {
		var reader = document.createElement('main');
		// Clear the rightpanel
		while (rightpanel.firstChild) {
			rightpanel.removeChild(rightpanel.firstChild);
		}

		// Fill out the rightpanel with the new timeline
		var header = document.createElement('header');
		var h3 = document.createElement('h3');
		var footer = document.createElement('footer');

		reader.id = 'reader';
		reader.className = 'timeline';
		h3.id = 'title';
		h3.appendChild(
			document.createTextNode(timeline)
		);
		header.appendChild(h3);
		rightpanel.appendChild(header);
		rightpanel.appendChild(reader);
		rightpanel.appendChild(footer);

		reader.setAttribute('data-timeline', timeline);

		// Unsubscribe from the previous view
		if (window.xhr) window.xhr.abort();

		// Initialize the rightpanel with all topics
		window.remote.rendertopics(timeline, reader);
//		window.remote.subscribetimeline(timeline, reader);
		rightpanel.style.right = '0px';
	}, animationtime);


}
/*
* Handles next / previous page clicks
*/
window.transitions.newpageclick = function(evt)
{
	var page;
	var collection = this.collection;
	if (this.className == 'rightnav')
		page = collection.currpage + 1;
	else if (this.className == 'leftnav')
		page = collection.currpage - 1;

	window.transitions.timelinepage(collection, page);
}
/*
* Next / previous page rendering
*/
window.transitions.timelinepage = function(collection, page)
{
	var results_per_page = 5;

	var children = collection.childNodes;
	var delay = 0;
	var i;

	// Gradually hide currently shown elements
	for (i = 0; i < children.length; i++) {
		if (children[i].style.display == 'none') continue;
		setTimeout(function(node) {
			node.style.visibility = 'hidden';
		}, delay, children[i]);
		delay += 100;
	}

	// Remove old page from document flow and add in the new page
	setTimeout(function() {
		for (i = 0; i < children.length; i++) {
			if (i >= results_per_page*page &&
			i < results_per_page*(page+1)) {
				children[i].style.display = '';
			}
			else {
				children[i].style.display = 'none';
			}
		}
		// Gradually show the current page
		var offset = 0;
		for (i = 0; i < children.length; i++) {
			if (children[i].style.display == 'none') continue;
			setTimeout(function(node) {
				node.style.visibility = 'visible';
			}, offset, children[i]);
			offset += 100;
		}

		// Should we show the right / left page nav?
		var timelines = collection.parentNode;
		var leftnav = timelines.getElementsByClassName('leftnav')[0];

		if (!page)
			leftnav.style.visibility = 'hidden';
		else
			leftnav.style.visibility = 'visible';
		var rightnav = timelines.getElementsByClassName('rightnav')[0];
		if (page + 1 >= children.length / results_per_page)
			rightnav.style.visibility = 'hidden';
		else
			rightnav.style.visibility = 'visible';
	}, delay);



	collection.currpage = page;
}
window.transitions.opentopic = function(timeline, topicnum)
{
	var topic = document.getElementById(topicnum);
	var offset = 0; var animationtime = 800; var delay = 0;
	var maxtime = 2000;
	var topics = topic.parentNode.children;

	// Unsubscribe from updates
	if (window.xhr) window.xhr.abort();

	/* Collapse all topics except for the one we're interested in */
	for (var i = topics.length-1; i+1; i--) {
		if (topics[i] != topic) {
			setTimeout(function(node) {
				node.style.width = '200%';
				node.style.padding = '0';
				node.style.margin = '0';
				setTimeout(function(node) {
					node.parentNode.removeChild(node);
				}, animationtime, node);
			}, offset, topics[i]);
		}
		if (offset < maxtime) offset += delay;
	}
	/* Bring the topic to the top */
	if (offset > maxtime) offset = maxtime;
	else offset = delay * (topics.length - 1) + animationtime;
	setTimeout(function() {
		var header = reader.parentNode.getElementsByTagName('header')[0];
		var title = document.getElementById('title');
		var backbutton = document.createElement('a');
		var subtitle = document.createElement('h3');
		subtitle.innerText = ' Topic No. ' + topicnum ;
		backbutton.innerText = '←';
		backbutton.addEventListener('click', function() {
			window.transitions.timelinejump(timeline);
		});
		header.insertBefore(backbutton, title);
		header.appendChild(subtitle);
		reader.className = 'expanded';

		window.remote.renderposts(timeline, topicnum, reader);
	}, offset);
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
	var title = document.createElement('h3');
	var latency = document.createElement('span');
	var collection = document.createElement('div');
	var nav = document.createElement('nav');
	var leftnav = document.createElement('a');
	var rightnav = document.createElement('a');

	timelines.className = 'frontcenter';
	timelines.id = 'timelines';
	title.appendChild(
		document.createTextNode('RAL')
	);
	window.lagInterval = setInterval(function() {
		if (latency.className != 'error')
			window.remote.updatelatency(latency);
	}, 60000);
	window.addEventListener('blur', function() {
		window.clearInterval(window.lagInterval);
	});
	window.addEventListener('focus', function() {
		window.lagInterval = setInterval(function() {
		if (latency.className != 'error')
			window.remote.updatelatency(latency);
		}, 60000);
	});
	latency.id = 'latency';
	collection.className = 'collection';
	leftnav.innerText = '◀';
	leftnav.className = 'leftnav';
	rightnav.innerText = '▶';
	rightnav.className = 'rightnav';
	nav.id = 'timelinenav';
	nav.appendChild(leftnav);
	nav.appendChild(rightnav);

	timelines.appendChild(title);
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

	leftnav.addEventListener('click', window.transitions.newpageclick);
	rightnav.addEventListener('click', window.transitions.newpageclick);

	window.remote.rendertimelines(collection);
}
window.creation.reader = function(name)
{
	var rightpanel = document.createElement('div');
	var header = document.createElement('header');
	var h3 = document.createElement('h3');
	var reader = document.createElement('main');
	var footer = document.createElement('footer');

	rightpanel.id = 'rightpanel';
	reader.id = 'reader';
	reader.className = 'timeline';
	h3.id = 'title';
	h3.appendChild(
		document.createTextNode(name)
	);
	header.appendChild(h3);
	rightpanel.appendChild(header);
	rightpanel.appendChild(reader);
	rightpanel.appendChild(footer);
	reader.setAttribute('data-timeline', name);

	// Initialize the timeline with all topics
	window.remote.rendertopics(name, reader);
//	window.remote.subscribetimeline(name, reader);

	rightpanel.style.right = '-100%';
	document.body.appendChild(rightpanel);
	setTimeout(function() {
		rightpanel.style.right = '0px';
	}, 100);
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
window.render = [];
window.render.newtopic = function(reader, topic) {
	var article = document.createElement('article');
	var updated = document.createElement('time');
	var num = document.createElement('span');
	var content = document.createElement('content');

	// Date formatting
	var time = new Date(topic.bump);
	function pad(n){return n<10 ? '0'+n : n}
	updated.innerText = pad(time.getMonth()+1)
	+ '/' + pad(time.getDate())
	+ ' ' + pad(time.getHours())
	+ ':' + pad(time.getMinutes());
	console.log(updated.innerText);

	article.className = 'topic';
	article.id = topic.id;
	updated.dateTime = topic.bump;
	num.innerText = 'No. ' + topic.id;
	num.className = 'num';
	content.innerText = topic.content;
	content.className = 'content';

	content.addEventListener('click',
		window.handlers.opentopic, this
	);

	article.appendChild(updated);
	article.appendChild(num);
	article.appendChild(content);

	// Animation
	if (!document.hasFocus()) {
		article.classList.add('new');
		window.addEventListener('focus', function() {
			setTimeout(function() {
				article.classList.remove('new');
			}, 2000);
		});
	}

	reader.insertBefore(article, reader.childNodes[0]);
}
window.render.newpost = function(reader, post) {
	var article = document.createElement('article');
	var updated = document.createElement('time');
	var num = document.createElement('span');
	var content = document.createElement('content');

	// Date formatting
	var time = new Date(post.modified);
	function pad(n){return n<10 ? '0'+n : n}
	updated.innerText = pad(time.getMonth() + 1)
	+ '/' + pad(time.getDate())
	+ ' ' + pad(time.getHours())
	+ ':' + pad(time.getMinutes());

	article.className = 'post';
	article.id = post.id;
	updated.dateTime = post.modified;
	num.innerText = 'No. ' + post.id;
	num.className = 'num';
	content.innerText = post.content;
	content.className = 'content';

	if (post.open) content.classList.add('open');

	num.addEventListener('click', function() {
		alert('Replies not available yet!');
	});

	article.appendChild(updated);
	article.appendChild(num);
	article.appendChild(content);

	// Animation
	article.style.height = '0';
	setTimeout(function() {
		article.style.height = '';
	}, 100);
	reader.appendChild(article);
}
