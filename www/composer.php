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

$iterator->select($continuity, $year, $topic);
if (!$continuity) {
	http_response_code(404);
	include "{$ROOT}template/404.php";
	die;
}
if (@$_POST['post'] && @$_POST['robocheckid']) {
	$id = $_POST['robocheckid'];
	$answer = $_POST['robocheckanswer'];
	$page = $iterator->resolve();
	if (!isset($id, $answer)) {
		header("Refresh: 5; url=$page");
		$reason = "Did you forget to verify your humanity?";
		include "{$ROOT}template/PostFailure.php";
	} else if (!check_robocheck($id, $answer)) {
		header("Refresh: 5; url=$page");
		$reason = "You failed the humanity check!";
		include "{$ROOT}template/PostFailure.php";
	} else if (empty(@$_POST['content'])) {
		header("Refresh: 5; url=$page");
		$reason = "Just what are you trying to do?";
		include "{$ROOT}template/PostFailure.php";
	} else {
		$iterator->post($_POST['content']);
		header("Refresh: 5; url=$page");
		include "{$ROOT}template/PostSuccess.php";
	} die;
}
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$Renderer->Title = "[$continuity]";
	$Renderer->Desc = "Posting to " . $iterator->description();
	$Renderer->putHead();
?>
	<meta name="robots" content="noindex,follow"/>
</head>
<body>
<div class=hf-container><header class=no-flair>
<?php $iterator->renderBanner(); ?>
<?php $iterator->breadcrumb(); ?>
</header></div>
<?php include CONFIG_LOCALROOT . "template/Feelies.php" ?><hr />
<article><?php if (@$_POST['preview'] && !empty(@$_POST['content'])) {
	$iterator->renderRobocheck($_POST['content']);
} else { $iterator->renderComposer(@$_POST['content']);
} ?></article><hr />
<?php $iterator->render(); ?>
<hr />
<div class=hf-container><footer>
	<?php include "{$ROOT}template/Footer.php"; ?>
</footer></div>
</body>
</HTML>
