<?php $ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/ContinuityIterator.php";
include "{$ROOT}includes/News.php";

$RM = new RAL\ResourceManager();
$iterator = new RAL\ContinuityIterator($RM);

// Translate GET parameters from their percent-encoded values
foreach ($_GET as $param => $value) $_GET[$param] = urldecode($value);

$action = @$_GET['a'];
switch ($action) {
	case 'view': view(
		@$_GET['continuity'],
		@$_GET['year'],
		@$_GET['topic'],
		(@$_GET['format'] ?: 'text')
	); break;
	default:
		http_response_code(405);
		print 'No action specified!';
}

function view($continuity, $year, $topic, $format) {
	$iterator = new RAL\ContinuityIterator();
	$iterator->select($continuity, $year, $topic);
	$iterator->render($format);
}
