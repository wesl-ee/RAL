<?php
$irc = "http://irc.ral.space";
$home = CONFIG_WEBROOT;
$homeimg = CONFIG_WEBROOT . "res/home.gif";
$ircimg = CONFIG_WEBROOT . "res/irc.gif";
print <<<HTML
	<nav class="info-links">
	<a href="$home"><img alt=Home title=Home src="$homeimg"></a>
	<a href="$irc"><img alt=IRC title=IRC src="$ircimg"></a>
	</nav>
HTML;
