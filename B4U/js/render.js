/* We need permission to create notifications if it's been requested */
if (getCookie('dnotify') && Notification.permission !== 'granted'
&& Notification.permission !== 'denied')
	Notification.requestPermission();

function appendToReader(reader, posthtml)
{
	var range = document.createRange(); range.selectNode(reader);
	var post = range.createContextualFragment(
		posthtml
	);
	var mostpost = reader.getAttribute('data-mostpost');
	var direction = reader.getAttribute('data-append');
	if (direction == 'top') {
		reader.insertBefore(post, reader.children[0]);
		var children = reader.children;
		if (children.length > mostpost)
			reader.removeChild(children[children.length-1]);
		return children[0];
	} else {
		reader.appendChild(post);
		var children = reader.children;
		if (children.length > mostpost)
			reader.removeChild(children[0]);
		children = reader.children;
		return children[children.length-1];
	}
}
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
 * Spawn a notification for a new post
*/
function notifyuser(post)
{
	/* Create a desktop notification */
	if (getCookie('dnotify')) {
		var options = {
			body: post.innerText,
		}
		new Notification('RAL', options);
	}
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
function highlightnew(article)
{
	// Highlight the new post
	if (!document.hasFocus()) {
		article.classList.add('new');
		window.addEventListener('focus', function x() {
			window.removeEventListener('focus', x);
			setTimeout(function() {
				article.classList.remove('new');
			}, 5000);
		});
	}
}
function formatrecentdate(date)
{
	var options = {
		timeZoneName: 'short',
		hour: '2-digit',
		minute: '2-digit',
	};
	return date.toLocaleTimeString([], options);
}
function doctitlenotify()
{ if (!document.hasFocus()) {
	incrementdoctitle();
	window.addEventListener('focus', function x(e) {
		resetdoctitle();
		window.removeEventListener('focus', x, true);
	}, true);
} }
function incrementdoctitle()
{
	var n;
	var title = document.title;
	var from = title.lastIndexOf('(');
	var to = title.lastIndexOf(')');

	var newtitle;
	if (from < 0 || to < 0) {
		n = 1;
		newtitle = "(" + n + ") " + title;
	}
	else {
		n = title.substr(from + 1, to - from - 1);
		newtitle = title.substr(0, from)
		+ " (" + ++n + ")" + title.substr(to + 1);
	}
	document.title = newtitle;
}
function resetdoctitle()
{
	var title = document.title;
	var from = title.lastIndexOf('(');
	var to = title.lastIndexOf(')');
	if (from < 0 || to < 0) return;

	document.title = title.substr(0, from) + title.substr(to + 1);
}
function verifyreader(reader, posts)
{
	var children = reader.getElementsByTagName('article');
	if (posts.length !== children.length)
		return false;
	for (var i = 0; i < posts.length; i++) {
		if (children[i].getAttribute('data-post') != posts[i]) {
			console.log('Missing post' + posts[i])
			return false;
		}
	}
	return true;
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
