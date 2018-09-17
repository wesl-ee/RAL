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
	$Renderer->title = "Site Configuration";
	$Renderer->putHead();
?>
</head>
<body>
<div class=hf-container><header>
	<h1>Site Configuration</h1>
	<span>Adjust your Experience</span><br />
</header></div>
<?php include "{$ROOT}template/Feelies.php" ?><hr />
<article><?php $Renderer->configForm(); ?></article>
<hr />
<div class=hf-container><footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer></div>
</body>
</HTML>
