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
// Finally, what are we searching for?
$query = @$_GET['query'];

if (!empty($_POST)) {
	$query = @$_POST['query'];
	header("Location: " . $iterator->resolveSearch($query));
	exit;
}

$iterator->selectSearch($query, $continuity, $year, $topic);

?>

<!DOCTYPE HTML>
<HTML>
<head>
<?php
	if (@$query) $Renderer->Title = "Search - $query";
	else $Renderer->Title = "Search";
	$Renderer->putHead();
?>
	<meta name="robots" content="noindex,follow"/>
</head>
<body>
<header>
<div><h1>RAL Site Search</h1>
	<span>Enquire within.</span>
	<?php include "{$ROOT}template/Feelies.php"; ?></div>
	<?php $iterator->drawSearchBar(@$query); ?>
</header>
<div class=main><main>
<?php
if (empty($query)) {
	print "You didn't search anything!";
} else if ($iterator->render() === false)
	print "Nobody's talking about {$query}!";
?>
</main></div>
<div class=discovery>
<?php include "{$ROOT}template/Sponsors.php"; ?>
</div>
<footer>
<?php include "{$ROOT}template/Footer.php"; ?>
</footer>
</body>
</HTML>
