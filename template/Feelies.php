<?php
$irc = "http://irc.ral.space";
$home = CONFIG_WEBROOT;
if (CONFIG_CLEAN_URL) {
	$settings = CONFIG_WEBROOT . "settings";
} else {
	$settings = CONFIG_WEBROOT . "settings.php";
}
print <<<HTML
	<nav class="info-links right">
	<a href="$home">Home</a><a href="$irc">IRC</a><a href="$settings">Settings</a>
	</nav>
HTML;
