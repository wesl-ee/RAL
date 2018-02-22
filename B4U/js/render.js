/* We need permission to create notifications if it's been requested */
if (getCookie('dnotify') && Notification.permission !== 'granted'
&& Notification.permission !== 'denied')
	Notification.requestPermission();

function newtopic(reader, topic)
{
	var article = createpostelement(topic, true);

	reader.insertBefore(article, reader.children[0]);
	highlightnew(article); notifyuser(topic);
}
function newfrontpagepost(reader, post)
{
	var article = createpostelement(post, true);
	var mostpost = reader.getAttribute('data-mostpost');

	reader.insertBefore(article, reader.children[0]);
	if (reader.children.length > mostpost)
		reader.removeChild(reader.lastElementChild);
	highlightnew(article); notifyuser(post);
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
function newpost(reader, post)
{
	var article = createpostelement(post, false);

	reader.appendChild(article);
	highlightnew(article); notifyuser(post);
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
	updated.innerText = post.shortdate;

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
 * Spawn a notification for a new post
*/
function notifyuser(post)
{
	var notifycontent = document.createElement('a');
	notifycontent.href = post.url;
	if (post.id == post.topic)
		notifycontent.innerText = 'New topic in ['
		+ post.continuity + ']';
	else
		notifycontent.innerText = 'New post in ['
		+ post.continuity + '/' + post.topic + ']';

	var time = new Date(post.date);
	time = formatrecentdate(time);

	var priority = 'lowpriority';

	pushnotification(notifycontent, time, priority);
}
/*
 * Generates a notification given the content and priority level
*/
function pushnotification(contentElement, timeText, priority)
{
	var notifications = document.getElementById('notifications');
	if (!notifications) {
		notifications = document.createElement('ul');
		notifications.id = 'notifications'
		document.body.appendChild(notifications);
	}
	var notification = document.createElement('li');
	var colorblock = document.createElement('span');
	var time = document.createElement('time');
	time.innerText = timeText;

	if (!priority) priority = 'lowpriority';
	notification.className = priority;
	colorblock.innerHTML = '&nbsp;';
	colorblock.className = 'priority';

	notification.appendChild(colorblock);
	notification.appendChild(time);
	notification.appendChild(contentElement);

	notifications.appendChild(indent(notification));

	/* Create a desktop notification */
	if (getCookie('dnotify')) {
		var options = {
			body: contentElement.innerText,
		}
		new Notification('RAL', options);
	}

	/* Remove the HTML notification after some time */
	window.addEventListener('focus', function x() {
		window.removeEventListener('focus', x);
		setTimeout(function() {
			notifications.removeChild(notification);
			if (!notifications.lastChild)
				notifications.parentNode.removeChild(
					notifications
				);
		}, 5000);
	});
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
