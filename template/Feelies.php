<?php
$irc = "http://irc.ral.space";
$home = CONFIG_WEBROOT;
print <<<HTML
	<nav class="info-links">
	<a href="$home">Home</a><a href="$irc">IRC</a>
	</nav>
HTML;
