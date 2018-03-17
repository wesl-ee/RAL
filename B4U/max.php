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

// Posting in a topic
if (isset($_POST['content']) && isset($topic)) {
	$auth = $_COOKIE['auth'];
	$content = $_POST['content'];

	// Strip down the content ; )
	$content = trim($content);
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
	<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
	<script src='<?php print CONFIG_WEBROOT?>js/render.js'></script>
</head>
<body><main id=main>
<?php
	$items = $continuities;
	include "../template/toolbar.php";

	// Requires $continuity; $topic is optional
	include "../template/breadcrumb.php";

	if (isset($topic))
		$title = "[$continuity / $topic]";
	else
		$title = "[$continuity]";
	$subtitle = $continuitydesc;
	// Requires $title; $subtitle is optional
	include "../template/header.php";



	// Browsing a topic (reader is in 'expanded' view)
	if (isset($topic)) {
		// Special attributes for telling the client
		// where to fetch realtime updates and verification
		if (CONFIG_REALTIME_ENABLE) {
			$realtimeurl = CONFIG_WEBROOT
			. "api.php?subscribe&continuity=$continuity"
			. "&topic=$topic&format=html";
			$verifyurl = CONFIG_WEBROOT
			. "api.php?verify&continuity=$continuity"
			. "&topic=$topic";
			print <<<HTML
	<div id=reader class=expanded
	data-topic="$topic"
	data-continuity="$continuity"
	data-realtimeurl="$realtimeurl"
	data-verifyurl="$verifyurl"
	data-append=bottom>

HTML;
		} else {
			print <<<HTML
	<div id=reader class=expanded
	data-topic="$topic"
	data-continuity="$continuity"

HTML;
	// Browsing a continuity (reader is in 'continuity' view)
	} } else {
		if (CONFIG_REALTIME_ENABLE) {
			$realtimeurl = CONFIG_WEBROOT
			. "api.php?subscribe&continuity=$continuity"
			. "&format=html";
			$verifyurl = CONFIG_WEBROOT
			. "api.php?verify&continuity=$continuity";
			print <<<HTML
	<div class=continuity id=reader
	data-realtimeurl="$realtimeurl"
	data-verifyurl="$verifyurl"
	data-append=top
	data-continuity="$continuity">

HTML;
		} else {
			print <<<HTML
	<div class=continuity id=reader
	data-continuity="$continuity">

HTML;
	} }
	foreach ($posts as $post) {
		if (isset($topic))
			$linkify = false;
		else
			$linkify = true;
		include "../template/post.php";
	} print
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
</main>
<script src='<?php print CONFIG_WEBROOT?>js/remote.js'></script>

<script>
var reader = document.getElementById('reader');
var continuity = reader.getAttribute('data-continuity');
var topic = reader.getAttribute('data-topic');

<?php
	if (CONFIG_REALTIME_ENABLE) print
<<<REALTIME_JS

subscribe(reader, continuity, topic);

REALTIME_JS;
?>
</script>
</body>
</HTML>
