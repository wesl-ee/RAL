<?php
include '../includes/config.php';
include '../includes/courier.php';

$page = $_GET['p'];
if (!isset($page)) $page = 0;
$timeline = $_GET['timeline'];
$topic = $_GET['topic'];
$postmode = $_GET['postmode'];

// Update a variable in the HTTP GET
function new_get($a, $value)
{
	$q = $_GET;
	$q[$a] = $value;
	return http_build_query($q);
}
?>
<!DOCTYPE HTML>
<HTML>
<head>
	<link rel=stylesheet href="/css/base.css">
	<link rel=stylesheet href="/css/20XX.css">
	<meta name=viewport
	content="width=device-width; maximum-scale=1; minimum-scale=1">
	<title>RAL</title>
</head>
<body>
<div id=timelines class=sidebar>
	<h3>RAL</h3>
	<span class=latency>&nbsp</span>
	<div class=collection><?php
	$per_page = 5;
	$timelines = fetch_timelines();
	for ($i = $page * $per_page; ($i < ($page + 1) * $per_page)
	&& ($i < count($timelines)); $i++) {
		$name = $timelines[$i]['name'];
		$desc = $timelines[$i]['name'];
		$q = "p=$page&timeline=$name";
		print "<a href=max.php?$q>$name</a>";
	}
	?></div>
	<?php
	if ($page > 0) {
		$nextpage = $page - 1;
		$q = new_get('p', $nextpage);
		print "<a class='leftnav' href='?$q'>"
		. "◀"
		. "</a>";
	}
	if ($page * $per_page < count($timelines) / $per_page) {
		$nextpage = $page + 1;
		$q = new_get('p', $nextpage);
		print "<a class='rightnav' href='?$q'>"
		. "▶"
		. "</a>";
	}
	?>
</div>
<div id=rightpanel>
	<?php if (isset($topic)) {
		$title = strtoupper("$timeline No. $topic");
		print "<h3>$title</h3>"
		. "<div class='reader expanded'>";
		$posts = fetch_posts($timeline, $topic);
		foreach ($posts as $post) {
			$content = $post['content'];
			$time = date('m/d h:m', strtotime($post['date']));
			$id = $post['id'];
			print "<article>"
			. "<time>$time</time>"
			. "<span class=id>No. $id</span>"
			. "<span class=content>$content</a>"
			. "</article>";
		}
		print "</div>";
		if (isset($postmode)) {
			$q = $_GET;
			unset($q['postmode']);
			$q = http_build_query($q);
			print "<form class=reply method=POST>"
			. "<textarea rows=5 name=content></textarea>"
			. "<div class=buttons>"
			. "<a href=?$q class='cancel'>Cancel</a>"
			. "<input value=Post type=submit>"
			. "</div>"
			. "</form>";
		} else {
			$q = http_build_query($_GET);
			print "<footer>"
			. "<span class=minorbox>"
			. "<a href=?$q&postmode>Reply to Topic</a>"
			. "</span>"
			. "</footer>";
		}
	} else {
		$title = strtoupper($timeline);
		print "<h3>$title</h3>"
		. "<div class='reader timeline'>";
		$topics = fetch_topics($timeline);
		foreach ($topics as $topic) {
			$content = $topic['content'];
			$time = date('m/d  h:m', strtotime($topic['date']));
			$id = $topic['id'];
			$q = new_get('topic', $id);
			print "<article>"
			. "<time>$time</time>"
			. "<span class=id>No. $id</span>"
			. "<a href='?$q'class=content>$content</a>"
			. "</article>";
		}
		print "</div>";
		if (isset($postmode)) {
			$q = $_GET;
			unset($q['postmode']);
			$q = http_build_query($q);
			print "<form class=reply method=POST>"
			. "<textarea rows=5 name=content></textarea>"
			. "<div class=buttons>"
			. "<a href=?$q class='cancel'>Cancel</a>"
			. "<input value=Post type=submit>"
			. "</div>"
			. "</form>";
		} else {
			$q = http_build_query($_GET);
			print "<footer>"
			. "<span class=minorbox>"
			. "<a href=?$q&postmode>Create a Topic</a>"
			. "</span>"
			. "</footer>";
		}
	}
	?>
</div>
</body>
<script src='/js/esthetic.js'></script>
<script src='/js/remote.js'></script>
<script>
var timelines = document.getElementById('timelines');
var latency = timelines.getElementsByClassName('latency')[0];
window.remote.updatelatency(latency);
</script>
</HTML>
