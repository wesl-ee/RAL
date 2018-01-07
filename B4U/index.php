<?php
$ROOT = '../';
include $ROOT.'includes/main.php';
include $ROOT.'includes/fetch.php';
include $ROOT.'includes/post.php';

$page = $_GET['p'];
if (!isset($page)) $page = 0;
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php head('RAL')?>
</head>
<body>
<div id=welcome>
	<h1>RAL</h1>
	<h4>Neo-Forum Text Board</h4>
	<div id=timelines>

<?php

	print
<<<HTML
	<div class=collection>
HTML;

	/* Draw the timelines panel (left sidebar) */
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
	}
?>
		</div>
		<nav class=arrows>
<?php
	// Left navigation arrow
	if (!$page) {
		print
<<<HTML
	<a class=leftnav style="visibility:hidden">◀</a>

HTML;
	} else {
		$nextpage = $page - 1;
		// Preserve $_GET across timelines navigation
		$q = $_GET;
		$q['p'] = $nextpage;
		$q = http_build_query($q);
		print
<<<HTML
	<a class=leftnav href="?$q">◀</a>

HTML;
	}

	// Right navigation arrow
	if ($page * $per_page < floor(count($timelines) / $per_page)) {
		$nextpage = $page + 1;
		// Preserve $_GET across timelines navigation
		$q = $_GET;
		$q['p'] = $nextpage;
		$q = http_build_query($q);
		print
<<<HTML
	<a class=rightnav href='?$q'>▶</a>

HTML;
	} else {
		print
<<<HTML
	<a class=rightnav style="visibility:hidden">▶</a>

HTML;
	}
?>
	</nav>
	<h4>Recent Posts</h4>
	<div class="reader recent">
<?php
	$recent = fetch_recent_posts(10);
	foreach ($recent as $post) {
		$content = $post['content'];
		// Dress up the content
		$time = date("M d Y", strtotime($post['date']));
		$id = $post['id'];
		$timeline = $post['timeline'];
		if (CONFIG_CLEAN_URL)
			$a = CONFIG_WEBROOT . "max/$timeline/$id";
		else if (CONFIG_CLEAN_URL)
			$a = CONFIG_WEBROOT . "max/$timeline/$id?$p";
		print
<<<HTML
		<article data-post=$id>
			<span class=info>
				<span class=id>[$timeline/$id]</span>
				<time>$time</time>
			</span>
			<span class=content data-topic=$id>
			<a href='$a'>$content</a>
			</span>
		</article>

HTML;
	}
?>
	</div>
	<footer>
		<?php if (CONFIG_CLEAN_URL) {
			$a = CONFIG_WEBROOT . "info/";
			print
<<<HTML
		<a href={$a}theme>Theme</a>
		<a href={$a}announce>Notices</a>
		<a href={$a}>About</a>
		<a href={$a}help>Help</a>

HTML;
		}
		else {
			$a = CONFIG_WEBROOT . "info/";
			print
<<<HTML
		<a href={$a}?theme>Theme</a>
		<a href={$a}?staff>Staff</a>
		<a href={$a}?>About</a>
		<a href={$a}?help>Help</a>

HTML;
		}
?>
	</footer>
</div>
<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/remote.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<script>
/* Make the site pretty if the user has JS */
var reader = document.getElementById(
	'welcome'
).getElementsByClassName('reader')[0];
var timelines = document.getElementById('timelines');

var collection = timelines.getElementsByClassName('collection')[0];
var leftnav = timelines.getElementsByClassName('leftnav')[0];
var rightnav = timelines.getElementsByClassName('rightnav')[0];

connectnav(collection, leftnav, rightnav);
connectreader(reader);

</script>
<!-- End of scripts -->
</body>
</HTML>
