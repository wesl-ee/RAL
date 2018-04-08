<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/fetch.php";
include "{$ROOT}includes/post.php";
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
	$continuities = fetch_continuities();
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

</head>
<body>
<main id=main class=welcome>
<?php

	$items = $continuities;
	include "{$ROOT}template/toolbar.php";
?>

<header>
	<h1>RAL</h1>
	<em>Neo-Forum Textboard</em>
</header>
<img class=flair onClick="animateOnce(this, 'spin')" src="res/strawberry.gif">

<?php
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
?><table>
<thead><tr>
	<th>Topic</th>
</tr></thead>
<tbody>
<?php
	foreach ($continuities as $c) {
		print <<<HTML
	<tr>
	<td><a href="$c->url">$c->name</a></td>
	<td>$c->postcount</td>
	<td><a href="$c->url">$c->description</a></td>
	</tr>

HTML;
	}
?></tbody></table>
	<h2>Fresh Posts</h2>
<?php
	print <<<HTML
	<div class=recent id=reader>

HTML;
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
</main>
<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/render.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<!-- End of scripts -->
</body>
</HTML>
