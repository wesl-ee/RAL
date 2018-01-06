<?php
$ROOT = '../';
include $ROOT."includes/main.php";

header("X-Robots-Tag: noindex");
if (CONFIG_CLEAN_URL) {
	$timeline = $_GET['timeline'];
	$topic = $_GET['topic'];
	unset($_GET['timeline']);
	unset($_GET['topic']);
	$q = http_build_query($_GET);
	$location = CONFIG_WEBROOT . "max";
	if (isset($timeline)) $location .= "/$timeline";
	if (isset($topic)) $location .= "/$topic";
} else {
	$q = http_build_query($_GET);
	$location = CONFIG_WEBROOT ."max.php";
}
if (empty($q)) $a = $location;
else $a  = "$location?$q";

header("Refresh: 3;$a");
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php head('Post Failure')?>
</head>
<body>
<div id=welcome>
	<h1 class=xxx-failure>Failure</h1>
</div>
<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<!-- End of scripts -->
</body>
</HTML>
