/* TRANSITIONS */
window.transitions = [];
window.transitions.newpage = function(collection)
{
	collection.style.flexWrap = 'nowrap';
	var children = collection.childNodes;
	var newnames = ['Fuck', 'Ass', 'Honk', 'asssshiter'];
	var i = 0;
	var delay = 0;
	for (child in children) {
		if (!children.hasOwnProperty(child)
		|| children[child].nodeType == 3) continue;
		setTimeout(function(node) {
			if (i >= newnames.length)
				node.parentNode.removeChild(node);
			else {
				node.style.visibility = 'hidden';
				node.innerText = newnames[i++];
			}
		}, delay, children[child]);
		delay += 100;
	}
	var j = 0;
	children = collection.childNodes;
	for (child in children) {
		if (!children.hasOwnProperty(child)
		|| children[child].nodeType == 3) continue;
		setTimeout(function(node) {
			if (!j) collection.style.flexWrap = 'initial';
			if (j >= newnames.length) return;
			else {
				node.style.visibility = 'visible';
			}
		}, delay, children[child]);
		delay += 100;
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
