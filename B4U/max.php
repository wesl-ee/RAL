<?php
$ROOT = '../';
include $ROOT.'includes/main.php';
include $ROOT.'includes/fetch.php';
include $ROOT.'includes/post.php';

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

// Posting in a topic
if (isset($_POST['content']) && isset($topic)) {
	$auth = $_COOKIE['auth'];
	$content = $_POST['content'];

	// Strip down the content ; )
	$content = trim($content);
	$content = htmlspecialchars($content);
	if (strlen($content) > CONFIG_RAL_POSTMAXLEN
	|| !($post = create_post($timeline, $topic, $auth, $content))) {
		print 'Failed to create post. . .';
		die;
	}
	else {
		notify_listeners('POST', $post);
		header("HTTP/1.1 303 See Other");
		header("Location: r3.php?$_SERVER[QUERY_STRING]");
		die;
	}
}
// Posting to the timeline
else if (isset($_POST['content'])) {
	$auth = $_COOKIE['auth'];
	$content = $_POST['content'];

	// Strip down the content ; )
	$content = trim($content);
	$content = stripslashes($content);
	$content = htmlspecialchars($content);
	if (strlen($content) > CONFIG_RAL_POSTMAXLEN
	|| !($topic = create_topic($timeline, $auth, $content))) {
		print 'Failed to create topic. . .';
	}
	else {
		notify_listeners('POST', $topic);
		header("HTTP/1.1 303 See Other");
		header("Location: r3.php?$_SERVER[QUERY_STRING]");
		die;
	}
}

$timelines = fetch_timelines();
?>
<!DOCTYPE HTML>
<HTML>
<head>
	<?php head($ROOT)?>
	<title>RAL</title>
</head>
<body>
<div id=timelines class=sidebar>
	<h3>RAL</h3>
	<span id=latency>&nbsp</span>
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
			. " style='visibility: hidden; display:none'>$name</a>";
		else
			print "<a href=max.php?$q>$name</a>";
	}
	?></div>
	<?php
	// Left navigation arrow
	if (!$page) {
		print "<a class='leftnav' style='visibility:hidden'>"
		. "◀"
		. "</a>";
	} else {
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
	} else {
		print "<a class='rightnav' style='visibility:hidden'>"
		. "▶"
		. "</a>";
	}
	?>
	<a class=help href=help.php>About</a>
</div>
<div id=rightpanel>
	<?php
	// Browsing a topic (reader is in 'expanded' view)
	if (isset($topic)) {
		$title = strtoupper("$timeline/#$topic");
		print "<h3>$title</h3>"
		. "<div class='reader expanded'"
		. " data-topic='$topic'"
		. " data-timeline='$timeline'>";
		$posts = fetch_posts($timeline, $topic);
		foreach ($posts as $post) {
			$content = $post['content'];
			$time = date('M d Y', strtotime($post['date']));
			$id = $post['id'];
			print "<article>"
			. "<time>$time</time>"
			. "<span class=id>#$id</span>"
			. "<span class=content>$content</span>"
			. "</article>";
		}
		print "</div>";
		if (isset($postmode)) {
			$q = $_GET;
			unset($q['postmode']);
			$q = http_build_query($q);
			print "<form class=reply method=POST action=?$q>"
			. "<textarea rows=5"
			. " maxlength=" . CONFIG_RAL_POSTMAXLEN
			. " name=content></textarea>"
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
		. "<div class='reader timeline'"
		. " data-timeline='$timeline'>";
		$q = $_GET;
		unset($q['postmode']);
		$topics = fetch_topics($timeline);
		foreach ($topics as $topic) {
			$content = $topic['content'];
			// Dress up the content
			$time = date("M d Y", strtotime($topic['date']));
			$id = $topic['id'];
			$q['topic'] = $id;
			$p = http_build_query($q);
			print "<article>"
			. "<time>$time</time>"
			. "<span class=id>#$id</span>"
			. "<span class=content data-topic=$id>"
			. "<a href='?$p'>$content</a>"
			. "</span>"
			. "</article>";
		}
		print "</div>";
		if (isset($postmode)) {
			$q = $_GET;
			unset($q['postmode']);
			$q = http_build_query($q);
			print "<form class=reply method=POST action=?$q>"
			. "<textarea rows=5"
			. " maxlength=" . CONFIG_RAL_POSTMAXLEN
			. " name=content></textarea>"
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
<script src='../js/remote.js'></script>
<script src='../js/esthetic.js'></script>
<script src='../js/render.js'></script>
<script>
var reader = document.getElementById(
	'rightpanel'
).getElementsByClassName('reader')[0];
var timelines = document.getElementById('timelines');
var timelinename = reader.getAttribute('data-timeline');
var topicid = reader.getAttribute('data-topic');

if (topicid !== null)
	subscribetopic(timelinename, topicid, reader);

updatelatency();

var collection = timelines.getElementsByClassName('collection')[0];
var leftnav = timelines.getElementsByClassName('leftnav')[0];
var rightnav = timelines.getElementsByClassName('rightnav')[0];

connectnav(collection, leftnav, rightnav);
</script>
</HTML>
