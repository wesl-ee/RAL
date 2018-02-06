<?php if (!isset($items)) {
	print "Improper template usage!";
	die;
}
?>
<nav class=collection>
<?php
	for ($i = 0; $i < count($items); $i++) {
		// Convert objects into their arrays
		$items[$i] = (array)$items[$i];
		$name = $items[$i]['name'];
		$desc = $items[$i]['description'];
		$location = $items[$i]['url'];
		// Put all items in the DOM (but only
		// display some) (for JS)
		print
<<<HTML
	<a class=hoverbox href="$location">$name</a>

HTML;
	}
?>
</nav>
