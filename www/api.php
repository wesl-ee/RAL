<?php $ROOT = '../';
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/Ral.php";
include "{$ROOT}includes/Renderer.php";
include "{$ROOT}includes/News.php";


// Translate GET parameters from their percent-encoded values
foreach ($_GET as $param => $value) $_GET[$param] = urldecode($value);

$action = @$_GET['a'];
switch ($action) {
	case 'view': view(
		@$_GET['continuity'],
		@$_GET['year'],
		@$_GET['topic'],
		(@$_GET['format'] ?: 'json')
	); break;
	default:
		http_response_code(405);
		print 'No action specified!';
}

function view($continuity, $year, $topic, $format) {
	$rm = new RAL\ResourceManager();
	$Renderer = new RAL\Renderer($rm);
	$ral = new RAL\Ral($rm);
	$Renderer->Put(
		$ral->Select($continuity, $year, $topic),
		$format);
}
