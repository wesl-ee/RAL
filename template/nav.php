<?php if (!isset($items)) {
	print "Improper template usage!";
	die;
}
// Track which page of items we are looking at
$page = $_GET['np'];
// Default to the first page of items
if (!isset($page)) $page = 0;
?>
<div class=collection>
<?php
	$per_page = CONFIG_PER_PAGE;
	for ($i = 0; $i < count($items); $i++) {
		// Convert objects into their arrays
		$items[$i] = (array)$items[$i];
		$name = $items[$i]['name'];
		$desc = $items[$i]['description'];
		$location = $items[$i]['url'];
		// Put all items in the DOM (but only
		// display some) (for JS)
		if ($i < $page * $per_page
		|| $i >= ($page + 1) * $per_page)
			print
<<<HTML
	<a class=hoverbox href="$location"
	style="visibility: hidden; display:none">$name</a>

HTML;
			else print
<<<HTML
	<a class=hoverbox href="$location">$name</a>

HTML;
	}
?>
</div>
<?php if (count($items) > $per_page) {
	print
<<<HTML
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
		// Preserve $_GET across items navigation
		$q = $_GET;
		$q['np'] = $nextpage;
		$q = http_build_query($q);
		print
<<<HTML
	<a class=leftnav href="?$q">◀</a>

HTML;
	}

	// Right navigation arrow
	if ($page * $per_page < floor(count($items) / $per_page)) {
		$nextpage = $page + 1;
		// Preserve $_GET across items navigation
		$q = $_GET;
		$q['np'] = $nextpage;
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
} ?>
