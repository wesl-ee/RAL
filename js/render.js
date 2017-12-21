function newtopic(reader, topic)
{
	var article = document.createElement('article');
	var updated = document.createElement('time');
	var num = document.createElement('span');
	var content = document.createElement('a');

	// Date formatting
	var time = new Date(topic.modified);
	function pad(n){return n<10 ? '0'+n : n}
	updated.innerText = pad(time.getMonth()+1)
	+ '/' + pad(time.getDate())
	+ ' ' + pad(time.getHours())
	+ ':' + pad(time.getMinutes());
	console.log(updated.innerText);

	article.className = 'topic';

	article.id = topic.id;
	updated.dateTime = topic.modified;
	num.innerText = 'No. ' + topic.id;
	num.className = 'id';
	content.innerText = topic.content;
	content.className = 'content';

	content.href = '?timeline=' + reader.getAttribute('data-timeline')
	+ '&topic=' + topic.id;
//	content.addEventListener('click',
//		window.handlers.opentopic, this
//	);

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
	var content = document.createElement('content');

	// Date formatting
	var time = new Date(post.modified);
	function pad(n){return n<10 ? '0'+n : n}
	updated.innerText = pad(time.getMonth() + 1)
	+ '/' + pad(time.getDate())
	+ ' ' + pad(time.getHours())
	+ ':' + pad(time.getMinutes());

	article.className = 'post';
	article.id = post.id;
	updated.dateTime = post.modified;
	num.innerText = 'No. ' + post.id;
	num.className = 'num';
	content.innerText = post.content;
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
