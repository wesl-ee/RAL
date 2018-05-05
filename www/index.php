<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/ContinuityIterator.php";

$iterator = new RAL\ContinuityIterator();
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
</head>
<body>
<header>
	<div>
		<h1 class=glitch data-text=RAL>RAL</h1>
	</div>
	<span>Neo-Forum Textboard</span>
</header>
<nav class=info-links>
<a href>About</a><a href>IRC</a><a href>Settings</a>
</nav><hr />
<?php
	$motd = "{$ROOT}info/MOTD";
	if (is_file($motd) && filesize($motd)) {
		$bbparser = $GLOBALS[RM]->getbbparser();
		$bbparser->parse(file_get_contents($motd));

		print <<<HTML
		<h2>Announcements</h2>
		<article>
		{$bbparser->getAsHtml()}
		</article><hr />

HTML;
	}
?>
<?php
	$iterator->select();
	$iterator->render();
?>
<hr /><footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
