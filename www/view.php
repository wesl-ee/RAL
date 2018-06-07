<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/ContinuityIterator.php";

$RM = new RAL\ResourceManager();
$iterator = new RAL\ContinuityIterator($RM);

// Which continuity we are reading
$continuity = urldecode($_GET['continuity']);
// Which year are we browsing?
$year = @$_GET['year'];
// Which topic (if any) we are reading
$topic = @$_GET['topic'];

$iterator->select($continuity, $year, $topic);
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
	$pagetitle = $iterator->title();
	$pagedesc = "DEVELOPER MODE";
	include "{$ROOT}template/head.php";
?>
</head>
<body>
<header>
<?php $iterator->renderBanner(); ?>
<ol vocab='http://schema.org/' typeof=BreadcrumbList
class=breadcrumb>
<?php
	$href = CONFIG_WEBROOT; $name = 'RAL'; $position = 1;
	include "{$ROOT}template/BreadCrumbItem.php";

	$href = htmlentities($iterator->Continuity->resolve());
	$name = $continuity; $position = 2;
	include "{$ROOT}template/BreadCrumbItem.php";

	if (isset($year)) {
		$href = htmlentities($iterator->Year->resolve());
		$name = $year; $position = 3;
		include "{$ROOT}template/BreadCrumbItem.php";
	} if (isset($topic)) {
		$href = htmlentities($iterator->Topic->resolve());
		$name = $topic; $position = 4;
		include "{$ROOT}template/BreadCrumbItem.php";
	}
?>
</ol>
</header>
<?php include CONFIG_LOCALROOT . "template/Feelies.php" ?>
<?php $iterator->renderPostButton();?><hr />
<?php $iterator->render();?><hr />
<?php $iterator->renderPostButton(); ?>
<footer>
	<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
