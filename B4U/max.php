<?php
$ROOT = '../';
include $ROOT.'includes/main.php';
include $ROOT.'includes/fetch.php';
include $ROOT.'includes/post.php';
include $ROOT.'includes/render.php';

// Which continuity we are reading
$continuity = urldecode($_GET['continuity']);
// Which topic (if any) we are reading
$topic = $_GET['topic'];
// Whether we are posting or only reading
$postmode = $_GET['postmode'];

// Default to the first page of continuities
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
	|| !($post = create_post($continuity, $topic, $auth, $content))) {
		header("HTTP/1.1 303 See Other");
		if (CONFIG_CLEAN_URL)
			$location = CONFIG_WEBROOT . "dariram";
		else
			$location = CONFIG_WEBROOT . "dariram.php";
		header("Location: $location?$_SERVER[QUERY_STRING]");
		die;
	}
	else {
		if (CONFIG_REALTIME_ENABLE)
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
// Posting to the continuity
else if (isset($_POST['content'])) {
	$auth = $_COOKIE['auth'];
	$content = $_POST['content'];

	// Strip down the content ; )
	$content = trim($content);
	$content = stripslashes($content);
	$content = htmlspecialchars($content);
	if (strlen($content) > CONFIG_RAL_POSTMAXLEN
	|| !strlen($content)
	|| !($topic = create_topic($continuity, $auth, $content))) {
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
	unset($_GET['continuity']);
	unset($_GET['topic']);
}

$continuities = fetch_continuities();

// Timeline parameter extraction and verification
$i = count($continuities);
for ($i = count($continuities); $i + 1; $i--) {
	if ($continuities[$i]->name == $continuity) break;
}
// 404 continuities which do not exist
if ($i < 0 || !isset($continuity)) {
	http_response_code(404);
	include "{$ROOT}template/404.php";
	die;
}
$continuity = $continuities[$i]->name;
$continuitydesc = $continuities[$i]->description;

if (isset($topic))
	$posts = fetch_posts($continuity, $topic);
else
	$posts = fetch_topics($continuity);
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	if (isset($topic)) {
		$pagetitle = titleize($posts[0]->content);
		$pagedesc = titleize($posts[0]->content, 320);
	}
	else {
		$pagetitle = "$continuity - $continuitydesc";
	}
	include "{$ROOT}template/head.php";
?>
</head>
<body>
<div id=continuities class=sidebar>
	<h2><a href="<?php print CONFIG_WEBROOT;?>">RAL</a></h2>
<?php
	if (CONFIG_CLEAN_URL)
		$a = CONFIG_WEBROOT . "info";
	else
		$a = CONFIG_WEBROOT . "info.php";
	print
<<<HTML
	<a href="$a">About</a>

HTML;
	$items = fetch_continuities();
	include "../template/nav.php";


	if (CONFIG_REALTIME_ENABLE) print
<<<LATENCY
	<span id=latency>&nbsp;</span>

LATENCY;
?></div>
<?php include "{$ROOT}/template/extrapanels.php"?>
<div id=rightpanel>
<?php
	if (isset($topic))
		$title = "[$continuity / $topic]";
	else
		$title = "[$continuity]";
	$subtitle = $continuitydesc;
	// Requires $title; $subtitle is optional
	include "../template/header.php";

	// Requires $continuity; $topic is optional
	include "../template/breadcrumb.php";

	// Browsing a topic (reader is in 'expanded' view)
	if (isset($topic)) {
		print
<<<HTML
	<div class="reader expanded"
	data-topic="$topic"
	data-continuity="$continuity">

HTML;
	// Browsing a continuity (reader is in 'continuity' view)
	} else {

		print
<<<HTML
	<div class="reader continuity"
	data-continuity="$continuity">

HTML;
	}
	foreach ($posts as $post) {
		if (isset($topic))
			$linkify = false;
		else
			$linkify = true;
		include "../template/post.php";
	}
	print
<<<HTML
	</div>

HTML;
	if (isset($postmode))
		// Requires $continuity; $topic optional
		include "../template/postbox.php";
	else
		// Requires $continuity; $topic optional
		include "../template/footer.php";
?>
</div>
<script src='<?php print CONFIG_WEBROOT?>js/remote.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/render.js'></script>
<script>
var reader = document.getElementById(
	'rightpanel'
).getElementsByClassName('reader')[0];
var continuities = document.getElementById('continuities');
var continuityname = reader.getAttribute('data-continuity');
var topicid = reader.getAttribute('data-topic');

<?php
	if (CONFIG_REALTIME_ENABLE) print
<<<REALTIME_JS
if (topicid !== null)
	subscribetopic(continuityname, topicid, reader);
REALTIME_JS;
?>

var collection = continuities.getElementsByClassName('collection')[0];
var leftnav = continuities.getElementsByClassName('leftnav')[0];
var rightnav = continuities.getElementsByClassName('rightnav')[0];

connectnav(collection, leftnav, rightnav);

connectreader(reader);
</script>
</body>
</HTML>
