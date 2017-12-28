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

	article.id = topic.id;
	updated.dateTime = topic.modified;

	num.appendChild(document.createTextNode('No. ' + topic.id));
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
	article.id = post.id;
	updated.dateTime = post.date;
	num.innerText = 'No. ' + post.id;
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

/*	if (!document.hasFocus()) {
		// Add to window title
		window.addEventListener('focus', function() {
			// Reset title
		});
	}*/
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
