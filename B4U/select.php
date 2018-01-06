<?php
$ROOT = '../';
include $ROOT.'includes/main.php';
include $ROOT.'includes/fetch.php';

$page = $_GET['p'];
if (!isset($page)) $page = 0;
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php head('Continuities')?>
</head>
<body>
<div id=timelines class=frontcenter>
	<h3>RAL</h3>
	<span id=latency>&nbsp;</span>
	<div class=collection>
<?php /* Draw the timelines panel (left sidebar) */
	$per_page = CONFIG_TIMELINES_PER_PAGE;
	$timelines = fetch_timelines();
	for ($i = 0; $i < count($timelines); $i++) {
		$name = $timelines[$i]['name'];
		$desc = $timelines[$i]['description'];
		if (CONFIG_CLEAN_URL)
			$a = CONFIG_WEBROOT . "max/$name?p=$page";
		else
			$a = "max.php?timeline=$name&p=$page";
		// Put all timelines in the DOM (but only
		// display some) (for JS)
		if ($i < $page * $per_page
		|| $i >= ($page + 1) * $per_page)
			print
<<<HTML
			<a href="$a"
			style="visibility: hidden; display:none">$name</a>

HTML;
		else print
<<<HTML
			<a href="$a">$name</a>

HTML;
	} ?>
	</div>
<?php
	if (!$page) {
		print
<<<HTML
	<a class=leftnav style="visibility:hidden">◀</a>

HTML;

	} else {
		$nextpage = $page - 1;
		$q = "p=$nextpage";
		print
<<<HTML
	<a class=leftnav href="?$q">◀</a>;

HTML;
	}
	if ($page * $per_page < count($timelines) / $per_page) {
		$nextpage = $page + 1;
		$q = "p=$nextpage";
		print
<<<HTML
	<a class=rightnav href="?$q">▶</a>

HTML;
	} else {
		print
<<<HTML
		<a class=rightnav style="visibility:hidden">▶</a>

HTML;
	}
	?>
	<a class=help href=info>About</a>
</div>

<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/remote.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<script>
/* Make the site pretty if the user has JS */
var timelines = document.getElementById('timelines');
updatelatency();

var collection = timelines.getElementsByClassName('collection')[0];
var leftnav = timelines.getElementsByClassName('leftnav')[0];
var rightnav = timelines.getElementsByClassName('rightnav')[0];

connectnav(collection, leftnav, rightnav);
</script>
<!-- End of scripts -->
</body>
</HTML>
