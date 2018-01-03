function newtopic(reader, topic)
{
	var article = document.createElement('article');
	var updated = document.createElement('time');
	var num = document.createElement('span');
	var content = document.createElement('span');
	var text = document.createElement('a');

	// Date formatting
	var time = new Date(topic.date);
	updated.innerText = formatdate(time);

	article.className = 'topic';

	article.setAttribute('data-post', topic.id);
	updated.dateTime = topic.modified;

	num.appendChild(document.createTextNode('#' + topic.id));
	num.className = 'id';

	content.className = 'content';

	// topic.content may contain HTML (parsed BBcode)
	text.innerHTML = topic.content;
	text.href = '?timeline=' + reader.getAttribute('data-timeline')
	+ '&topic=' + topic.id;

	content.appendChild(text);
	article.appendChild(updated);
	article.appendChild(num);
	article.appendChild(content);

/*	if (!document.hasFocus()) {
		// Add to window title
		window.addEventListener('focus', function() {
			// Reset title
		});
	}*/

	reader.insertBefore(article, reader.childNodes[0]);
}
function newpost(reader, post)
{
	var article = document.createElement('article');
	var updated = document.createElement('time');
	var num = document.createElement('span');
	var content = document.createElement('span');

	// Date formatting
	var time = new Date(post.date);
	updated.innerText = formatdate(time);

	article.className = 'post';
	article.setAttribute('data-post', post.id);
	updated.dateTime = post.date;
	num.innerText = '#' + post.id;
	num.className = 'id';

	// post.content may contain HTML (parsed BBcode)
	content.innerHTML = post.content;
	content.className = 'content';

	if (post.open) content.classList.add('open');

	num.addEventListener('click', function() {
		alert('Replies not available yet!');
	});

	article.appendChild(updated);
	article.appendChild(num);
	article.appendChild(content);

	reader.appendChild(article);
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
	console.log(from + ' ' + to);
	if (from < 0 || to < 0) {
		n = 1;
		newtitle = title + " (" + n + ")";
	}
	else {
		n = title.substr(from + 1, to - from - 1);
		console.log(n);
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
		if (children[i].getAttribute('data-post') != posts[i])
			return false;
	}
	return true;
}
