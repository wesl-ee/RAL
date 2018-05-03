<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/Continuity.php";
include "{$ROOT}includes/ContinuityIterator.php";

$iterator = new RAL\ContinuityIterator();

// Which continuity we are reading
$cname = urldecode($_GET['continuity']);
// Which topic (if any) we are reading
$topic = @$_GET['topic'];
// Which year are we browsing?
$year = @$_GET['year'];

if (!$cname || !$iterator->fetchContinuity($cname)) {
	http_response_code(404);
	include "{$ROOT}template/404.php";
	die;
}
$continuity = $iterator->fetchContinuity($cname);

?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$pagetitle = "[$cname]";
	$pagedesc = "DEVELOPER MODE";
	include "{$ROOT}template/head.php";
?>
</head>
<body><main>
<header>
<?php $continuity->drawBanner(); ?>
<ol vocab='http://schema.org/' typeof=BreadcrumbList
class=breadcrumb>[
<?php
	$href = CONFIG_WEBROOT; $name = 'RAL'; $position = 1;
	include "{$ROOT}template/BreadCrumbItem.php";

	$href = $continuity->resolve(); $name = $cname; $position = 2;
	include "{$ROOT}template/BreadCrumbItem.php";

	if (isset($year)) {
		$continuity->year = $year;
		$href = $continuity->resolve(); $name = $year; $position = 3;
		include "{$ROOT}template/BreadCrumbItem.php";
	} if (isset($topic)) {
		$continuity->topic = $topic;
		$href = $continuity->resolve(); $name = $topic; $position = 4;
		include "{$ROOT}template/BreadCrumbItem.php";
	}
?>
]</ol></header>
<nav class=info-links>
	[ <a href>About</a>
	| <a href>IRC</a>
	| <a href>Settings</a> ]
</nav><hr />
<?php

$continuity->drawContent();
?>
</main>
<hr /><footer>
	<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
