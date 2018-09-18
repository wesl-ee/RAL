<?php
$irc = "https://irc.prettyboytellem.com";
$home = CONFIG_WEBROOT;
if (CONFIG_CLEAN_URL) $rss = CONFIG_WEBROOT . "rss";
else $rss = CONFIG_WEBROOT . "rss.php";
if (CONFIG_CLEAN_URL) $config = CONFIG_WEBROOT . "config";
else $config = CONFIG_WEBROOT . "config.php";
$sysop = CONFIG_WEBROOT . "op/";
$homeimg = CONFIG_WEBROOT . "res/home.gif";
$ircimg = CONFIG_WEBROOT . "res/irc.gif";
$rssimg = CONFIG_WEBROOT . "res/rss.gif";
$confimg = CONFIG_WEBROOT . "res/settings.gif";
$sysopimg = CONFIG_WEBROOT . "res/sysop.gif";
print <<<HTML
	<nav class="info-links">
	<a href="$home"><img alt=Home title=Home src="$homeimg"></a>
	<a href="$irc"><img alt=IRC title=IRC src="$ircimg"></a>
	<a href="$rss"><img alt=RSS title=RSS src="$rssimg"></a>
	<a href="$config"><img alt=Configuration title=Configuration
	src="$confimg"></a>
	<a href="$sysop"><img alt=Sysop title=Sysop src="$sysopimg"></a>
	</nav>
HTML;
