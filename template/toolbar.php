<?php
	$home = CONFIG_WEBROOT;
	if (CONFIG_REALTIME_ENABLE) {
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
<?php
	if (CONFIG_REALTIME_ENABLE) print
<<<LATENCY
	<span id=latency>
		<span class="bar disconnected"> </span>
		<span class=text></span>
	</span>

LATENCY;
?>
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
<hr />
