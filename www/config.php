<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/Ral.php";
include "{$ROOT}includes/Renderer.php";

$rm = new RAL\ResourceManager();
$Renderer = new RAL\Renderer($rm);
$Renderer->themeFromCookie($_COOKIE);
$Ral = new RAL\Ral($rm);

if (isset($_POST['Theme'])) {
	setcookie('Theme', $_POST['Theme']);
	$_COOKIE['Theme'] = $_POST['Theme'];
	if (CONFIG_CLEAN_URL) $page = CONFIG_WEBROOT . 'config';
	else $page = CONFIG_WEBROOT . 'config.php';
	$until = 3;
	header("Refresh: $until; url=$page");
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
<header><div>
	<div class=header-box><h1>Configuration Panel</h1>
	<span>Customize your Reality</span></div>
	<?php include "{$ROOT}template/Feelies.php" ?>
</div></header>
<div class=main><main>
<article>
<h2>Configuration</h2>
<?php $Renderer->configForm(); ?>
</article>
</main></div>
<div class=discovery>
<?php include "{$ROOT}template/Sponsors.php"; ?>
</div>
<footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
