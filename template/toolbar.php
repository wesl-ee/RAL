<?php
	if (CONFIG_CLEAN_URL) {
		$theming = CONFIG_WEBROOT . "info/theme";
		$help = CONFIG_WEBROOT . "info/help";
	} else {
		$theming = CONFIG_WEBROOT . "info.php?page=theme";
		$help = CONFIG_WEBROOT . "info.php?page=help";
	}
?>

<nav class=toolbar>
	<a class=hoverbox href="<?php print CONFIG_WEBROOT?>">
		<img src="<?php print CONFIG_WEBROOT?>res/home.gif"
		title=Home>
	</a>
	<a class=hoverbox href=<?php print $theming?>>
		<img src="<?php print CONFIG_WEBROOT?>res/theme.gif"
		title=Theming>
	</a>
	<a class=hoverbox href=<?php print $help?>>
		<img src="<?php print CONFIG_WEBROOT?>res/help.gif"
		title=Help>
	</a>
<!--	<a href=<?php print CONFIG_WEBROOT . "feedback"?>>
		<img src="<?php print CONFIG_WEBROOT?>res/ideas.gif"
		title=Feedback>
	</a>-->
</nav>
<nav class=collection>
<?php
	for ($i = 0; $i < count($items); $i++) {
		// Convert objects into their arrays
		$items[$i] = (array)$items[$i];
		$name = $items[$i]['name'];
		$desc = $items[$i]['description'];
		$location = $items[$i]['url'];
		if (isset($desc)) print <<<HTML
	<a class=hoverbox title="$desc" href="$location">$name</a>

HTML;
		else print <<<HTML
	<a class=hoverbox href="$location">$name</a>

HTML;
	}
?></nav><hr />
