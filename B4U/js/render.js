function handletogglepreview(e) { togglepreview(e.target.parentNode); }
function togglepreview(replybox)
{
	var preview = replybox.getElementsByClassName('preview')[0];
	var ta = replybox.getElementsByTagName('textarea')[0];
	var toggle = replybox.getElementsByClassName('toggle-preview')[0];

	if (!replybox.classList.contains('previewing')) {
		preview.innerText = ta.value;
		replybox.classList.add('previewing');
		toggle.innerText = 'Continue Writing';
	}
	else {
		replybox.classList.remove('previewing');
		toggle.innerText = 'Preview Formatting';
		ta.focus();
	}

	previewPost(ta.value, preview);
}
/*
 * Fairy-tale perfect and logical indentation
*/
function indent(element, level)
{
	if (!level) level = 0;
	var children = element.children;
	for (var i = 0; i < children.length; i++) {
		element.insertBefore(
			document.createTextNode("\n"), children[i]
		);
		for (var t = level + 1; t; t--)
			element.insertBefore(
				document.createTextNode("\t"), children[i]
			);
		if (element.firstElementChild)
			indent(children[i], level + 1);
	}
	return element;
}
function getCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ')
			c = c.substring(1);
		if (!c.indexOf(name))
			return c.substring(name.length, c.length);
	} return false;
}
