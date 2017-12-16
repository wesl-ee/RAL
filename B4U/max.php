<?php
include '../includes/config.php';
include '../includes/courier.php';

$page = $_GET['p'];
if (!isset($page)) $page = 0;
$timeline = $_GET['timeline'];

// Update a variable in the HTTP GET
function new_get($a, $value)
{
	$q = $_GET;
	$q[$a] = $value;
	return http_build_query($q);
}
?>
<!DOCTYPE HTML>
<HTML>
<head>
	<link rel=stylesheet href="/css/base.css">
	<link rel=stylesheet href="/css/20XX.css">
	<meta name=viewport
	content="width=device-width; maximum-scale=1; minimum-scale=1">
	<title>RAL</title>
</head>
<body>
<div id=timelines class=sidebar>
	<h3>RAL</h3>
	<span class=latency>&nbsp</span>
	<div class=collection><?php
	$per_page = 5;
	$timelines = fetch_timelines();
	for ($i = $page * $per_page; ($i < ($page + 1) * $per_page)
	&& ($i < count($timelines)); $i++) {
		$name = $timelines[$i]['name'];
		$desc = $timelines[$i]['name'];
		$q = new_get('timeline', $name);
		print "<a href=max.php?$q>$name</a>";
	}
	?></div>
	<?php
	if ($page > 0) {
		$nextpage = $page - 1;
		$q = new_get('p', $nextpage);
		print "<a class='leftnav' href='?$q'>"
		. "◀"
		. "</a>";
	}
	if ($page * $per_page < count($timelines) / $per_page) {
		$nextpage = $page + 1;
		$q = new_get('p', $nextpage);
		print "<a class='rightnav' href='?$q'>"
		. "▶"
		. "</a>";
	}
	?>
</div>
<div id=rightpanel>
	<h3><?php print strtoupper($timeline)?></h3>
	<div class=reader>
		<article>a</article>
		<article>a</article>
		<article>a</article>
		<article>a</article>
	</div>
	<footer>
		<span><a>Reply</a></span>
	</footer>
</div>
</body>
<script src='/js/esthetic.js'></script>
<script src='/js/remote.js'></script>
<script>
var timelines = document.getElementById('timelines');
var latency = timelines.getElementsByClassName('latency')[0];
window.remote.updatelatency(latency);
</script>
</HTML>
