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
