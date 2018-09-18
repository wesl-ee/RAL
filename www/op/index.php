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
<div class=hf-container><header>
	<h1>Sysop / Co-sysop Panel</h1>
	<span>Bless this mess</span><br />
</header></div>
<?php include "{$ROOT}template/Feelies.php" ?><hr />
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
}?></article>
<hr />
<div class=hf-container><footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer></div>
</body>
</HTML>
