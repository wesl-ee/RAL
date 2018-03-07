<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/fetch.php";
include "{$ROOT}includes/post.php";
include "{$ROOT}includes/render.php";
include "{$ROOT}includes/git.php";
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
	include "{$ROOT}template/toolbar.php";

	$title = "RAL";
	$subtitle = "Neo-Forum Textboard";
	include "{$ROOT}template/header.php";

	$flair = CONFIG_WEBROOT . "res/strawberry.gif";
	$url = CONFIG_WEBROOT;
	print <<<HTML
	<img class=flair onClick="animateOnce(this, 'spin')" src="$flair">

HTML;
?>
<?php
	$navtitle = 'Continuities';
	$items = fetch_continuities();
	if ($items)
		include "{$ROOT}template/nav.php";
	else print
<<<HTML
		<span class=error>No continuities have been created!</span>

HTML;

	$motd = "{$ROOT}info/MOTD.pod";
	if (is_file($motd) && filesize($motd)) {
		print <<<HTML
		<h2>Announcements</h2>
		<article>

HTML;
		ppppppp("{$ROOT}info/MOTD.pod");
		print <<<HTML
		</article><hr />
HTML;
	}
?>
	<h2>Recent Posts</h2>
	<div class="reader recent" data-mostpost=<?php
	print CONFIG_FRONTPAGE_POSTS ?>>
<?php
	$recent = fetch_recent_posts(CONFIG_FRONTPAGE_POSTS);
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
	$icon = CONFIG_WEBROOT . "res/tor-14px.png";
	if (CONFIG_ONION_URL) {
		$href = CONFIG_ONION_URL;
		print
<<<ONION
	<img src=$icon>
	<a href="$href">TOR Onion</a><br/>
ONION;
	}


?>
	<span>(<?php print date('Y')?>) BSD 3-Clause</span><br />
<?php
	if ($head = git_head(CONFIG_LOCALROOT))
		print <<<HTML
	<span>RAL $head[tag] "$head[cutename]" ($head[checksum])</span>

HTML;
?>
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

if (reader) {
	subscribeall(reader);
}
</script>
<!-- End of scripts -->
</body>
</HTML>
