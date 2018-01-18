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
<?php head('Home')?>
	<meta name=description content="The world's first
	and last Neo-forum / Textboard. Experience the VIRTUAL WORLD today.">
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
	include "{$ROOT}template/nav.php"
?>
	</div>
	<header>Recent Posts</header>
	<div class="reader recent">
<?php
	$recent = fetch_recent_posts(10);
	$linkify = true;
	foreach ($recent as $post) {
		include "{$ROOT}template/post.php";
	}
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
	<a href='https://github.com/yumi-xx/RAL'>Source Code</a><br/>
	<span>(<?php print date('Y')?>) BSD 3-Clause</span>
	</footer>
</div>
<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/remote.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/render.js'></script>
<script>
/* Make the site pretty if the user has JS */
var reader = document.getElementById(
	'welcome'
).getElementsByClassName('reader')[0];
var continuities = document.getElementById('continuities');

var collection = continuities.getElementsByClassName('collection')[0];
var leftnav = continuities.getElementsByClassName('leftnav')[0];
var rightnav = continuities.getElementsByClassName('rightnav')[0];

connectnav(collection, leftnav, rightnav);
connectreader(reader);

subscribeall(reader);
</script>
<!-- End of scripts -->
</body>
</HTML>
