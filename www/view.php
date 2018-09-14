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
	$pagetitle = $iterator->title();
	$pagedesc = $iterator->description();
	include "{$ROOT}template/head.php";
?>
</head>
<body>
<header>
<?php $iterator->renderBanner(); ?>
<?php $iterator->breadcrumb(); ?>
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
