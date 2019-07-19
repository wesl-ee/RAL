<?php
$ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/Ral.php";
include "{$ROOT}includes/Renderer.php";

$rm = new RAL\ResourceManager();
$Renderer = new RAL\Renderer($rm);
$Renderer->themeFromCookie($_COOKIE);
$Ral = new RAL\Ral($rm);

// Which continuity we are reading
$continuity = urldecode($_GET['continuity']);
// Which year are we browsing?
$year = @$_GET['year'];
// Which topic (if any) we are reading
$topic = @$_GET['topic'];
// Which posts (if any) we are reading
$replies = @$_GET['replies'];
//
$compose = @$_GET['compose'];

if ($topic) $specifies = "topic";
else if ($year) $specifies = "year";
else if ($continuity) $specifies ="continuity";

$resource = $Ral->Select($continuity, $year, $topic, $replies);
if (!$specifies) {
	http_response_code(404);
	include "{$ROOT}template/404.php";
	die;
}

if (isset($compose))
	$Renderer->ShowComposer = true;
else if (@$_POST['preview'])
	$Renderer->PendingMessage = $_POST['content'];
else if (@$_POST['post']) {
	$page = $resource->resolve();
	$until = 3;
	if ($_POST['robocheck-fail']) {
		$reason = "Sorry, I don't let robots post here!";
		header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request"); 
		header("Refresh: $until; url=$page");
		include "{$ROOT}template/PostFailure.php";
		die;
	} else if (!isset($_POST['robocheck'])) {
		$reason = "Did you forget to verify your humanity?";
		header("Refresh: $until; url=$page");
		include "{$ROOT}template/PostFailure.php";
		die;
	} else if (empty(@$_POST['content'])) {
		$reason = "Just what are you trying to do?";
		header("Refresh: $until; url=$page");
		include "{$ROOT}template/PostFailure.php";
		die;
	} else if (strlen($_POST['content']) < CONFIG_MIN_POST_BYTES) {
		$reason = "Your post is too short... please write some more!";
		header("Refresh: $until; url=$page");
		include "{$ROOT}template/PostFailure.php";
		die;
	}

	$b8 = $rm->getb8();
	$spamminess = $b8->classify($rm->asHtml($_POST['content']));
	if ($spamminess > CONFIG_SPAM_THRESHOLD) {
		$reason = "Hmm... error code: " . round($spamminess * 100);
		header("Refresh: $until; url=$page");
		include "{$ROOT}template/PostFailure.php";
		die;
	}

	switch ($specifies) {
		case "continuity":
		case "year":
			$Ral->PostTopic($continuity, $_POST['content'], $_COOKIE['id']);
			break;
		case "topic":
			$Ral->PostReply($continuity, $year, $topic, $_POST['content'], $_COOKIE['id']);
	}
	header("Refresh: $until; url=$page");
	include "{$ROOT}template/PostSuccess.php";
	die;
}

?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	$Renderer->Title = $resource->Title();
	$Renderer->Desc = $resource->Description();
	$Renderer->putHead();
?>
</head>
<body>
<div><header><?php $Renderer->PutBanner($resource); ?>
<?php include "{$ROOT}template/Feelies.php"; ?></header></div>
<div class=main><main>
<?php switch($specifies) {
	case "topic":
		$Renderer->Topic($resource, "html");
		break;
	case "year":
		$Renderer->Year($resource, "html");
		break;
	case "continuity":
		$Renderer->Continuity($resource, "html");
		break;
	default:
		$Renderer->Continuities($resource, "html");
} ?></main></div>
<div class=discovery>
<?php include "{$ROOT}template/Sponsors.php"; ?>
</div>
<footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
