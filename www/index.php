<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/ContinuityIterator.php";
include "{$ROOT}includes/News.php";
include "{$ROOT}includes/Renderer.php";

$Renderer = new RAL\Renderer();
$Renderer->themeFromCookie($_COOKIE);
$RM = new RAL\ResourceManager();
$iterator = new RAL\ContinuityIterator($RM);
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$Renderer->Title = "Home";
	$Renderer->Desc = "The world's first and last Neo-Forum / Textboard."
	. " Experience the VIRTUAL WORLD today.";
	$Renderer->putHead();
?>
	<link rel=alternate type="application/rss+xml" title=RSS
<?php
	if (CONFIG_CLEAN_URL)
		$rss = CONFIG_WEBROOT . "rss";
	else
		$rss = CONFIG_WEBROOT . "rss.php";
	print
<<<HREF
	href="$rss"
HREF;
?>>
</head>
<body>
<?php $iterator->renderHeader(); ?><hr />
<div class=main><main>
<?php $iterator->select(); $iterator->render(); ?>
<?php $iterator->selectRecent(10); $iterator->render(); ?><hr />
<?php (new RAL\News($RM))->select()->draw(); ?><hr />
<?php include "{$ROOT}info/About.txt" ?><hr />
<?php include "{$ROOT}info/Rules.txt" ?>
</main></div>
<div class=discovery>
<?php include "{$ROOT}template/Sponsors.php"; ?>
</div>
<footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
