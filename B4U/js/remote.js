function previewPost(text, container)
{
	var xhr = new XMLHttpRequest();
	var t1;
	xhr.onreadystatechange = function() {
	if (this.readyState == 1) {
		t1 = performance.now();
	}
	if (this.readyState == 2) {
		var t2 = performance.now();
		updatelatbar(Math.round(t2 - t1));
	}
	if (this.readyState == 4)
	if (this.status == 200) {
		var markup = this.responseText;
		container.innerHTML = markup;
	} }
	var uri = 'text=' + encodeURI(text);
	xhr.open('POST', '/api.php?preview');
	xhr.setRequestHeader("Content-type",
	"application/x-www-form-urlencoded");
	xhr.send(uri);
}
