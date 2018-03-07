function readerhighlight(reader, element)
{
	reader.highlighted.classList.remove('selected');
	element.classList.add('selected');
	reader.highlighted = element;

}
function animateOnce(item, classname)
{
	item.classList.add(classname);
	item.addEventListener("animationend", function x(e) {
		if (e.animationName == classname) {
			item.removeEventListener("animationend", x);
			item.classList.remove(classname);
		}
	});
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
