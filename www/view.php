<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/ContinuityIterator.php";
include "{$ROOT}includes/Renderer.php";

$Renderer = new RAL\Renderer();
$Renderer->themeFromCookie($_COOKIE);
$RM = new RAL\ResourceManager();
$iterator = new RAL\ContinuityIterator($RM);

// Which continuity we are reading
$continuity = urldecode($_GET['continuity']);
// Which year are we browsing?
$year = @$_GET['year'];
// Which topic (if any) we are reading
$topic = @$_GET['topic'];
// Which posts (if any) we are reading
$replies = @$_GET['replies'];

$iterator->select($continuity, $year, $topic, $replies);
if (!$continuity) {
	http_response_code(404);
	include "{$ROOT}template/404.php";
	die;
}

?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$Renderer->Title = $iterator->title();
	$Renderer->Desc = $iterator->description();
	$Renderer->putHead();
?>
</head>
<body>
<header>
<?php $iterator->renderBanner(); ?>
<?php $iterator->breadcrumb(); ?>
</header></div>
<?php include CONFIG_LOCALROOT . "template/Feelies.php" ?><hr />
<?php $iterator->render();?><hr />
<div class=hf-container><footer>
	<?php include "{$ROOT}template/Footer.php"; ?>
</footer></div>
</body>
</HTML>
