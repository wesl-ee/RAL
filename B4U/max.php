<?php
$ROOT = '../';
include $ROOT.'includes/main.php';
include $ROOT.'includes/fetch.php';
include $ROOT.'includes/post.php';
include $ROOT.'includes/render.php';

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
	|| !strlen($content)
	|| !check_robocheck($_POST['robocheckid'], $_POST['robocheckanswer'])
	|| !($post = create_post($timeline, $topic, $auth, $content))) {
		header("HTTP/1.1 303 See Other");
		if (CONFIG_CLEAN_URL)
			$location = CONFIG_WEBROOT . "dariram";
		else
			$location = CONFIG_WEBROOT . "dariram.php";
		header("Location: $location?$_SERVER[QUERY_STRING]");
		die;
	}
	else {
		notify_listeners('POST', $post);
		header("HTTP/1.1 303 See Other");
		if (CONFIG_CLEAN_URL)
			$location = CONFIG_WEBROOT . "r3";
		else
			$location = CONFIG_WEBROOT . "r3.php";
		header("Location: $location?$_SERVER[QUERY_STRING]");
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
	|| !strlen($content)
	|| !($topic = create_topic($timeline, $auth, $content))) {
		header("HTTP/1.1 303 See Other");
		if (CONFIG_CLEAN_URL)
			$location = CONFIG_WEBROOT . "dariram";
		else
			$location = CONFIG_WEBROOT . "dariram.php";
		header("Location: $location?$_SERVER[QUERY_STRING]");
		die;
	}
	else {
		notify_listeners('POST', $topic);
		header("HTTP/1.1 303 See Other");
		if (CONFIG_CLEAN_URL)
			$location = CONFIG_WEBROOT . "r3";
		else
			$location = CONFIG_WEBROOT . "r3.php";
		header("Location: $location?$_SERVER[QUERY_STRING]");
		die;
	}
}

// These parameters are in our URL, not in the querystring
if (CONFIG_CLEAN_URL) {
	unset($_GET['timeline']);
	unset($_GET['topic']);
}

$timelines = fetch_timelines();

// Timeline parameter extraction and verification
$i = count($timelines);
for ($i = count($timelines); $i + 1; $i--) {
	if ($timelines[$i]['name'] == $timeline) break;
}
// 404 timelines which do not exist
if ($i < 0) {
	http_response_code(404);
	include "{$ROOT}static/404.php";
	die;
}
$timeline = $timelines[$i]['name'];
$timelinedesc = $timelines[$i]['description'];
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$title = '';
	if (isset($timeline)) $title .= " $timeline";
	if (isset($topic)) $title .= "/$topic";
	head($title);
?>
</head>
<body>
<div id=timelines class=sidebar>
	<h3>RAL</h3>
	<span id=latency>&nbsp;</span>
<?php
	// Requires $timelines and $page
	include "../static/nav.php";
	if (CONFIG_CLEAN_URL)
		$a = CONFIG_WEBROOT . "info";
	else
		$a = CONFIG_WEBROOT . "info.php";
	print
<<<HTML
	<a class=help href="$a">About</a>

HTML;
?></div>
<div id=rightpanel>
<?php
	if (isset($topic)) {
		$title = strtoupper("$timeline [$topic]");
	}
	else {
		$title = strtoupper($timeline);
		$subtitle = $timelinedesc;
	}
	// Requires $title; $subtitle is optional
	include "../static/header.php";

	// Requires $timeline; $topic is optional
	include "../static/breadcrumb.php";

	// Browsing a topic (reader is in 'expanded' view)
	if (isset($topic)) {
		$posts = fetch_posts($timeline, $topic);
		print
<<<HTML
	<div class="reader expanded"
	data-topic="$topic"
	data-timeline="$timeline">

HTML;
	// Browsing a continuity (reader is in 'timeline' view)
	} else {
		$posts = fetch_topics($timeline);
		print
<<<HTML
	<div class="reader timeline"
	data-timeline="$timeline">

HTML;
	}
	foreach ($posts as $post) {
		// Requires $post
		include "../static/post.php";
	}
	print
<<<HTML
	</div>

HTML;
	if (isset($postmode))
		// Requires $timeline; $topic optional
		include "../static/postbox.php";
	else
		// Requires $timeline; $topic optional
		include "../static/footer.php";
?>
</div>
<script src='<?php print CONFIG_WEBROOT?>js/remote.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/render.js'></script>
<script>
var reader = document.getElementById(
	'rightpanel'
).getElementsByClassName('reader')[0];
var timelines = document.getElementById('timelines');
var timelinename = reader.getAttribute('data-timeline');
var topicid = reader.getAttribute('data-topic');

if (topicid !== null)
	subscribetopic(timelinename, topicid, reader);

var collection = timelines.getElementsByClassName('collection')[0];
var leftnav = timelines.getElementsByClassName('leftnav')[0];
var rightnav = timelines.getElementsByClassName('rightnav')[0];

connectnav(collection, leftnav, rightnav);

connectreader(reader);
</script>
</body>
</HTML>
