<?php
include '../includes/config.php';
include '../includes/courier.php';

// Track which page of timelines we are looking at
$page = $_GET['p'];
// Which timeline we are reading
$timeline = $_GET['timeline'];
// Which topic (if any) we are reading
$topic = $_GET['topic'];
// Whether we are posting or only reading
$postmode = $_GET['postmode'];

// Default to the first page of timelines
if (!isset($page)) $page = 0;

$timelines = fetch_timelines();
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
	/* Draw the timelines panel (left sidebar) */
	$per_page = CONFIG_TIMELINES_PER_PAGE;
	for ($i = 0; $i < count($timelines); $i++) {
		$name = $timelines[$i]['name'];
		$desc = $timelines[$i]['description'];
		$q = "p=$page&timeline=$name";
		// Put all timelines in the DOM (but only
		// display some) (for JS)
		if ($i < $page * $per_page
		|| $i >= ($page + 1) * $per_page)
			print "<a href=max.php?$q"
			. " style=display:none>$name</a>";
		else
			print "<a href=max.php?$q>$name</a>";
	}
	?></div>
	<?php
	// Left navigation arrow
	if ($page > 0) {
		$nextpage = $page - 1;
		// Preserve $_GET across timelines navigation
		$q = $_GET;
		$q['p'] = $nextpage;
		$q = http_build_query($q);
		print "<a class='leftnav' href='?$q'>"
		. "◀"
		. "</a>";
	}
	// Right navigation arrow
	if ($page * $per_page < count($timelines) / $per_page) {
		$nextpage = $page + 1;
		// Preserve $_GET across timelines navigation
		$q = $_GET;
		$q['p'] = $nextpage;
		$q = http_build_query($q);
		print "<a class='rightnav' href='?$q'>"
		. "▶"
		. "</a>";
	}
	?>
</div>
<div id=rightpanel>
	<?php
	// Browsing a topic (reader is in 'expanded' view)
	if (isset($topic)) {
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
			print "<form class=reply method=POST action=?$q>"
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
			. "</span>";
			$q = $_GET;
			unset($q['topic']);
			$q = http_build_query($q);
			print "<span class=minorbox>"
			. "<a href=?$q>Return</a>"
			. "</span>"
			. "</footer>";
		}
	// Browsing a timeline (reader is in 'timeline' view)
	} else {
		$title = strtoupper($timeline);
		print "<h3>$title</h3>"
		. "<div class='reader timeline'>";
		$topics = fetch_topics($timeline);
		foreach ($topics as $topic) {
			$content = $topic['content'];
			$time = date('m/d  h:m', strtotime($topic['date']));
			$id = $topic['id'];
			$q = $_GET;
			$q['topic'] = $id;
			$q = http_build_query($q);
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
			print "<form class=reply method=POST action=?$q>"
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
