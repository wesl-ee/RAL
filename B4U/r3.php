<?php
$ROOT = '../';
include $ROOT."includes/main.php";

header("X-Robots-Tag: noindex");
if (CONFIG_CLEAN_URL) {
	$continuity = $_GET['continuity'];
	$topic = $_GET['topic'];
	unset($_GET['continuity']);
	unset($_GET['topic']);
	$q = http_build_query($_GET);
	$location = CONFIG_WEBROOT . "max";
	if (isset($continuity)) $location .= "/$continuity";
	if (isset($topic)) $location .= "/$topic";
} else {
	$q = http_build_query($_GET);
	$location = CONFIG_WEBROOT ."max.php";
}
if (empty($q)) $a = $location;
else $a  = "$location?$q";

header("X-Robots-Tag: noindex");
header("Refresh: 3;$a");
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php head('Post Success')?>
</head>
<body>
<div id=welcome>
	<header>RAL says</header>
	<h1 class=xxx-success>Successful</h1>
</div>
<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/xxx.js'></script>
<!-- End of scripts -->
</body>
</HTML>
