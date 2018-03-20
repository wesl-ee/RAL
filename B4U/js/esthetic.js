function readerhighlight(reader, element)
{
	reader.highlighted.classList.remove('selected');
	element.classList.add('selected');
	reader.highlighted = element;

}
function animateOnce(item, classname)
{
	var newitem = item.cloneNode(true);
	item.parentNode.replaceChild(newitem, item);
	item = newitem;
	item.classList.add(classname);
	item.addEventListener("animationend", function x(e) {
		if (e.animationName == classname) {
			item.removeEventListener("animationend", x);
			item.classList.remove(classname);
		}
	});
}
function toggleSpoiler(e)
{
	var s = e.target;
	if (s.classList.contains('shown'))
		s.classList.remove('shown');
	else
		s.classList.add('shown');
}
function clickSpoiler(e)
{
	var s = e.target;
	if (!s.clicked) {
		s.clicked = true;
		s.removeEventListener('mouseover', toggleSpoiler);
		s.removeEventListener('mouseout', toggleSpoiler);
	} else {
		s.clicked = false;
		s.addEventListener('mouseover', toggleSpoiler);
		s.addEventListener('mouseout', toggleSpoiler);
	}
}
var nodes = document.getElementsByClassName('spoiler');
for (var i = 0; i < nodes.length; i++) {
	nodes[i].addEventListener('mouseover', toggleSpoiler);
	nodes[i].addEventListener('mouseout', toggleSpoiler);
	nodes[i].addEventListener('click', clickSpoiler);
}
