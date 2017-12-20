<?php
include '../includes/config.php';
include '../includes/fetch.php';

$page = $_GET['p'];
if (!isset($page)) $page = 0;
?>
<!DOCTYPE HTML>
<HTML>
<head>
	<meta name=viewport content="width=device-width, maximum-scale=1, minimum-scale=1">
	<link rel=stylesheet href="../css/base.css">
	<link rel=stylesheet href="../css/20XX.css">
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

// Zoom out animation
var children = collection.childNodes;
for (var i = 0; i < children.length; i++) {
	children[i].addEventListener('click', function(e) {
		e.preventDefault();
		var timelines = document.getElementById('timelines');
		timelines.style.opacity = '0';
		timelines.style.top = '0';
		timelines.addEventListener('transitionend', function(t) {
			// Box shadows trigger for some reason
			if (t.propertyName == 'opacity' || t.propertyName == 'top')
			window.location = e.target.href;
			console.log(t);
		});
	});
}

// Disable bfCache on firefox (we want JS to execute again!)
window.onunload= function(){};
</script>
<!-- End of scripts -->
</body>
</HTML>
