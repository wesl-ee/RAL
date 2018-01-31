<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/fetch.php";
include "{$ROOT}includes/post.php";
include "{$ROOT}includes/render.php";

$page = $_GET['np'];
if (!isset($page)) $page = 0;
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$pagetitle = "Home";
	$pagedesc = "The world's first and last Neo-Forum / Textboard."
	. " Experience the VIRTUAL WORLD today.";
	include "{$ROOT}template/head.php";
?>
	<link rel=alternate type="application/rss+xml" title=RSS
<?php
	if (CONFIG_CLEAN_URL)
		$href = CONFIG_WEBROOT . "rss";
	else
		$href = CONFIG_WEBROOT . "rss.php";
	print
<<<HREF
	href="$href"
HREF;
?>>
	<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
</head>
<body>
<div id=welcome>
<?php
	$title = "RAL";
	$subtitle = "Neo-Forum Textboard";
	include "{$ROOT}template/header.php";
?>
	<span id=latency>&nbsp;</span>
	<div id=continuities>
<?php
	$items = fetch_continuities();
	if ($items)
		include "{$ROOT}template/nav.php";
	else print
<<<HTML
		<span class=error>No continuities have been created!</span>

HTML;
?>
	</div>
	<header>Recent Posts</header>
	<div class="reader recent">
<?php
	$recent = fetch_recent_posts(10);
	$linkify = true;
	foreach ($recent as $post)
		include "{$ROOT}template/post.php";

?>
	</div>
	<footer>
		<?php if (CONFIG_CLEAN_URL) {
			$a = CONFIG_WEBROOT . "info/";
			print
<<<HTML
		<a href={$a}theme>Theme</a>
		<a href={$a}>About</a>
		<a href={$a}help>Help</a>

HTML;
		}
		else {
			$a = CONFIG_WEBROOT . "info/";
			print
<<<HTML
		<a href={$a}?theme>Theme</a>
		<a href={$a}?>About</a>
		<a href={$a}?help>Help</a>

HTML;
		}
?>
	<br/>
<?php
	$icon = CONFIG_WEBROOT . "res/GitHub-Mark-14px.png";
	if (CONFIG_CLEAN_URL)
		$href = CONFIG_WEBROOT . "rss";
	else
		$href = CONFIG_WEBROOT . "rss.php";
	print
<<<SOURCE
	<img src=$icon>
	<a href='https://github.com/yumi-xx/RAL'>Source Code</a><br/>
SOURCE;
	$icon = CONFIG_WEBROOT . "res/feed-icon-14x14.png";
	if (CONFIG_CLEAN_URL)
		$href = CONFIG_WEBROOT . "rss";
	else
		$href = CONFIG_WEBROOT . "rss.php";
	print
<<<RSS
	<img src=$icon>
	<a href="$href">RSS</a><br/>
RSS;

?>
	<span>(<?php print date('Y')?>) BSD 3-Clause</span>
	</footer>
</div>
<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/remote.js'></script>

<script src='<?php print CONFIG_WEBROOT?>js/render.js'></script>
<script>
/* Make the site pretty if the user has JS */
var reader = document.getElementById(
	'welcome'
).getElementsByClassName('reader')[0];
var continuities = document.getElementById('continuities');

if (reader) {
	connectreader(reader);
	subscribeall(reader);
}
</script>
<!-- End of scripts -->
</body>
</HTML>
