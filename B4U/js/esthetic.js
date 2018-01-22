// Add JS functionality to continuity navigators and
// block their HTML hrefs
function connectnav(collection, leftnav, rightnav)
{
	collection.currpage = 0;

	rightnav.removeAttribute('href');
	rightnav.addEventListener('click', function(e) {
		continuityscroll(collection, collection.currpage + 1);
	});
	leftnav.removeAttribute('href');
	leftnav.addEventListener('click', function(e) {
		e.preventDefault();
		continuityscroll(collection, collection.currpage - 1);
	});

	// Remove the ?p= from the href query string (since JS
	// keeps track of the current page
	var children = collection.children;
	for (var i = 0; i < children.length; i++) {
		var href = children[i].href;
		if (href.indexOf('?') < 0) continue;
		var query = href.slice(href.indexOf('?') + 1);
		var page = href.slice(0, href.indexOf('?'));
		query = removequeryparts(query, ['np']);
		if (query.length > 0)
			children[i].href = page + '?' + query;
		else
			children[i].href = page;
	}
}
function connectreader(reader)
{
/*	// Adjust times to be in user's locale
	var dates = reader.getElementsByTagName('time');
	for (var i = 0; i < dates.length; i++) {
		var datetime = dates[i].dateTime;
		var d = new Date(datetime).toLocaleDateString('en-US', {
			day: 'numeric',
			month: 'short',
			year: 'numeric',
			timeZoneName: 'short'
		} );
		// Remove the nast commas
		d = d.split(',').join('');
		dates[i].innerText = d;
	}*/
}
function readerhighlight(reader, element)
{
	reader.highlighted.classList.remove('selected');
	element.classList.add('selected');
	reader.highlighted = element;

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
function continuityscroll(collection, page)
{
	/* CONFIG_PER_PAGE */
	var results_per_page = 5;

	var children = collection.children;
	var delay = 0;
	var i;

	// Gradually hide currently shown elements
	for (i = 0; i < children.length; i++) {
		if (children[i].style.display == 'none') continue;
		setTimeout(function(node) {
				node.style.visibility = 'hidden';
		}, delay, children[i]);
		delay += 50;
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
		offset += 50;
	}

	// Should we show the right / left page nav?
	var continuities = collection.parentNode;
	var leftnav = continuities.getElementsByClassName('leftnav')[0];

	if (!page)
		leftnav.style.visibility = 'hidden';
	else
		leftnav.style.visibility = 'visible';
	var rightnav = continuities.getElementsByClassName('rightnav')[0];
	if (page + 1 >= children.length / results_per_page)
		rightnav.style.visibility = 'hidden';
	else
		rightnav.style.visibility = 'visible';
	}, delay);

	collection.currpage = page;
}
