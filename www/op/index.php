<?php
$ROOT = '../../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/Renderer.php";
include "{$ROOT}includes/Syspanel.php";

$Renderer = new RAL\Renderer();
$Renderer->themeFromCookie($_COOKIE);
$RM = new RAL\ResourceManager();
$Syspanel = new RAL\Syspanel($RM);
$Syspanel->loadSession($_COOKIE['id']);

if ($Syspanel->isAuthorizationAttempt($_POST)) {
	$Syspanel->authorize($_POST);
}
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$Renderer->Title = "Co/Sysop Log-in Page";
	$Renderer->putHead();
?>
</head>
<body>
<header>
<div>
	<h1>Sysop / Co-sysop Panel</h1>
	<span>Bless this mess</span><br />
	<?php include "{$ROOT}template/Feelies.php" ?>
</div></header>
<div class=main><main>
<article>
<?php if (!$Syspanel->Authorized) {
	print <<<HTML
	<h2>Who are you?</h2>

HTML;
	$Syspanel->renderLogin();
} else {
	$view = $_POST['view'];
	print <<<HTML
	<h2>Welcome Home!</h2>

HTML;
	$Syspanel->renderPanel($view);
}?>
</article></main></div>
<div class=discovery>
<?php include "{$ROOT}template/Sponsors.php"; ?>
</div>
<footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
