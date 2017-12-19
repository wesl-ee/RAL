<?php
include '../includes/config.php';
include '../includes/fetch.php';

$page = $_GET['p'];
if (!isset($page)) $page = 0;
?>
<!DOCTYPE HTML>
<HTML>
<head>
	<link rel=stylesheet href="../css/base.css">
	<link rel=stylesheet href="../css/20XX.css">
	<meta name=viewport
	content="width=device-width; maximum-scale=1; minimum-scale=1">
	<title>RAL</title>
</head>
<body>
<div id=timelines class=frontcenter>
	<h3>RAL</h3>
	<span class=latency>&nbsp</span>
	<div class=collection><?php
	/* Draw the timelines panel (left sidebar) */
	$per_page = CONFIG_TIMELINES_PER_PAGE;
	$timelines = fetch_timelines();
	for ($i = 0; $i < count($timelines); $i++) {
		$name = $timelines[$i]['name'];
		$desc = $timelines[$i]['description'];
		$q = "p=$page&timeline=$name";
		// Put all timelines in the DOM (but only
		// display some) (for JS)
		if ($i < $page * $per_page
		|| $i >= ($page + 1) * $per_page)
			print "<a href=max.php?$q"
			. " style='visibility: hidden; display:none'>$name</a>";
		else
			print "<a href=max.php?$q>$name</a>";
	}
	?></div>
	<?php
	if (!$page) {
		print "<a class='leftnav' style='visibility:hidden'>"
		. "◀"
		. "</a>";
	} else {
		$nextpage = $page - 1;
		$q = "p=$nextpage";
		print "<a class='leftnav' href='?$q'>"
		. "◀"
		. "</a>";
	}
	if ($page * $per_page < count($timelines) / $per_page) {
		$nextpage = $page + 1;
		$q = "p=$nextpage";
		print "<a class='rightnav' href='?$q'>"
		. "▶"
		. "</a>";
	} else {
		print "<a class='rightnav' style='visibility:hidden'>"
		. "▶"
		. "</a>";
	}
	?>
</div>

<!-- Scripts -->
<script src='../js/remote.js'></script>
<script src='../js/esthetic.js'></script>
<script>
/* Make the site pretty if the user has JS */
var timelines = document.getElementById('timelines');
var latency = timelines.getElementsByClassName('latency')[0];
updatelatency(latency);

var collection = timelines.getElementsByClassName('collection')[0];
var leftnav = timelines.getElementsByClassName('leftnav')[0];
var rightnav = timelines.getElementsByClassName('rightnav')[0];

connectnav(collection, leftnav, rightnav);
</script>
<!-- End of scripts -->
</body>
</HTML>
