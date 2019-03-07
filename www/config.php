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

if (isset($_POST['Theme'])) {
	setcookie('Theme', $_POST['Theme']);
	$_COOKIE['Theme'] = $_POST['Theme'];
	$page = CONFIG_WEBROOT;
	header("Refresh: 5; url=$page");
	include "{$ROOT}template/ThemeChange.php";
	die;
}
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$Renderer->Title = "Site Configuration";
	$Renderer->putHead();
?>
</head>
<body>
<?php $iterator->renderHeader(); ?>
<main>
<article>
<h2>Configuration</h2>
<?php $Renderer->configForm(); ?>
</article>
</main>
<div class=discovery>
<?php include "{$ROOT}template/Sponsors.php"; ?>
</div>
<footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
