// Add JS functionality to timeline navigators and
// block their HTML hrefs
function connectnav(collection, leftnav, rightnav)
{
	collection.currpage = 0;

	rightnav.setAttribute('href', '');
	rightnav.addEventListener('click', function(e) {
		e.preventDefault();
		timelinescroll(collection, collection.currpage + 1);
	});
	leftnav.setAttribute('href', '');
	leftnav.addEventListener('click', function(e) {
		e.preventDefault();
		timelinescroll(collection, collection.currpage - 1);
	});

	// Remove the ?p= from the href query string (since JS
	// keeps track of the current page
	var children = collection.childNodes;
	for (var i = 0; i < children.length; i++) {
		var href = children[i].href;
		var query = href.slice(href.indexOf('?') + 1);
		var page = href.slice(0, href.indexOf('?'));
		query = removequeryparts(query, ['p']);
		children[i].href = page + '?' + query;
	}
}
// Remove a parameter from the GET query string (do not pass
// the ? or location!)
function removequeryparts(query, params)
{
	var parts = query.split('&');
	var newparts = [];
	for (var k = 0; k < parts.length; k++) {
		if (params.indexOf(parts[k].split('=')[0]) < 0)
		newparts.push(parts[k]);
	}
	return newparts.join('&');
}
function timelinescroll(collection, page)
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
function flashmessages(elem, messages, delay) {
	var i, j;
	function writer() {
		do { j = Math.floor(Math.random()*messages.length);
		} while (j == i);
		i = j;
		txt = messages[i];
		elem.innerText = txt;
	}
	setInterval(writer, delay);
}
