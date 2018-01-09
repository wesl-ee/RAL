<?php
function rendercontinuitynav($page)
{
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
	<a class=hoverbox href="$a"
	style="visibility: hidden; display:none">$name</a>

HTML;
		else print
<<<HTML
	<a class=hoverbox href="$a">$name</a>

HTML;
	}
	print
<<<HTML
</div>
<nav class=arrows>

HTML;
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
	print
<<<HTML
</nav>

HTML;
}
?>
