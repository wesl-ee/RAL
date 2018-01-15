function newtopic(reader, topic)
{
	var href;
	var CONFIG_CLEAN_URL = true;
	var CONFIG_WEBROOT = 'https://ral.space/';
	if (CONFIG_CLEAN_URL)
		href = CONFIG_WEBROOT + 'max/' + topic.timeline
		+ '/' + topic.id;
	else
		href = CONFIG_WEBROOT + 'max.php?timeline=' + topic.timeline
		+ '&topic=' + topic.id;
	var article = createpostelement(topic, href);

	reader.insertBefore(article, reader.children[0]);
	highlightnew(article);
}
function newfrontpagepost(reader, post)
{
	var href;
	var CONFIG_CLEAN_URL = true;
	var CONFIG_WEBROOT = 'https://ral.space/';
	if (CONFIG_CLEAN_URL)
		href = CONFIG_WEBROOT + 'max/' + post.timeline
		+ '/' + post.topic;
	else
		href = CONFIG_WEBROOT + 'max.php?timeline=' + topic.timeline
		+ '&topic=' + post.topic;
	var article = createpostelement(post, href);

	reader.insertBefore(article, reader.children[0]);
	reader.removeChild(reader.lastElementChild);
	highlightnew(article);
}
function newpost(reader, post)
{
	var article = createpostelement(post, true);
	console.log(article);
	reader.appendChild(article);

	highlightnew(article);
}
function createpostelement(post, href)
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
	num.appendChild(document.createTextNode(
		'[' + post.timeline + '/' + post.id + ']'
	));
	num.className = 'id';

	// post.content may contain HTML (parsed BBcode)
	content.innerHTML = post.content;
	content.className = 'content';

	if (href)
		info.setAttribute('href', href);

	info.appendChild(num);
	info.appendChild(updated);
	article.appendChild(info);
	article.appendChild(content);

	return article;
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

var testpost = new Object();
testpost.id = .59;
testpost.topic = 1;
testpost.timeline = 'B4U';
testpost.content = '0 errors, 0 warnings';
testpost.date = new Date().toISOString();
