<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/ContinuityIterator.php";

$iterator = new RAL\ContinuityIterator();

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

	$href = $iterator->Continuity->resolve(); $name = $continuity; $position = 2;
	include "{$ROOT}template/BreadCrumbItem.php";

	if (isset($year)) {
		$href = $iterator->Year->resolve(); $name = $year; $position = 3;
		include "{$ROOT}template/BreadCrumbItem.php";
	} if (isset($topic)) {
		$href = $iterator->Topic->resolve(); $name = $topic; $position = 4;
		include "{$ROOT}template/BreadCrumbItem.php";
	}
?>
</ol>
</header>
<?php include CONFIG_LOCALROOT . "template/Feelies.php" ?><hr />
<?php
$iterator->renderPostButton();
$iterator->render();
$iterator->renderPostButton();
?>
<hr /><footer>
	<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
