function newtopic(reader, topic)
{
	var article = createpostelement(topic, true);

	reader.insertBefore(article, reader.children[0]);
	highlightnew(article);
}
function newfrontpagepost(reader, post)
{
	var article = createpostelement(post, true);
	var mostpost = reader.getAttribute('data-mostpost');

	reader.insertBefore(article, reader.children[0]);
	if (reader.children.length > mostpost)
		reader.removeChild(reader.lastElementChild);
	highlightnew(article);
}
function newpost(reader, post)
{
	var article = createpostelement(post, false);

	reader.appendChild(article);
	highlightnew(article);
}
function createpostelement(post, linkify)
{
	var article = document.createElement('article');
	var info = document.createElement('a');
	var updated = document.createElement('time');
	var num = document.createElement('span');
	var content = document.createElement('span');

	// Date formatting
	var time = new Date(post.date);
	updated.innerText = formatdate(time);

	article.setAttribute('data-post', post.id);
	updated.dateTime = post.date;

	num.className = 'id';
	num.appendChild(document.createTextNode(
		'[' + post.continuity + '/' + post.id + ']'
	));

	info.className = 'info';
	if (linkify)
		info.href = post.url;

	// post.content may contain HTML (parsed BBcode)
	content.innerHTML = post.content;
	content.className = 'content';

	info.appendChild(num);
	info.appendChild(updated);
	article.appendChild(info);
	article.appendChild(content);

	return indent(article);
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
			}, 2000);
		});
	}
}
function formatdate(date)
{
	var monthNames = ["January", "February", "March", "April", "May",
	"June",	"July", "August", "September", "October", "November",
	"December"];
	function pad(n){return n<10 ? '0'+n : n}
	return monthNames[date.getMonth()].substr(0, 3)
	+ ' ' + pad(date.getDate())
	+ ' ' + pad(date.getFullYear());
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
