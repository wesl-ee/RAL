<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/Ral.php";
include "{$ROOT}includes/News.php";
include "{$ROOT}includes/Renderer.php";

$RM = new RAL\ResourceManager();
$Renderer = new RAL\Renderer($RM);
$Renderer->themeFromCookie($_COOKIE);
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$Renderer->Title = "BBCode Help";
	$Renderer->putHead();
?>
</head>
<body>
<header>
<div><div class=header-box><h1>Using BBCode on RAL</h1>
	<span>or, making your text look pretty on-line</span></div>
	<?php include "{$ROOT}template/Feelies.php"; ?></div>
</header>
<div class=main><main>
<?php include "{$ROOT}info/BBCode.txt" ?>
</main></div>
<div class=discovery>
<?php include "{$ROOT}template/Sponsors.php"; ?>
</div>
<footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
