<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/Ral.php";
include "{$ROOT}includes/News.php";
include "{$ROOT}includes/Renderer.php";

$rm = new RAL\ResourceManager();
$Renderer = new RAL\Renderer($rm);
$Renderer->themeFromCookie($_COOKIE);
$ral = new RAL\Ral($rm);
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
<header><div>
<?php include "{$ROOT}template/SiteBanner.php"; ?>
<?php include "{$ROOT}template/Feelies.php"; ?>
</div></header>
<div class=main><main>
<?php $Renderer->Put($ral->select(), "html"); ?><hr />
<?php $Renderer->Put($ral->SelectRecent(10), "html"); ?>
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
