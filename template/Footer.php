<span>(<?php print date('Y')?>) BSD 3-Clause</span><br />
<a href="https://github.com/yumi-xx/RAL">RAL v3.1</a>
	<span>(<em>Z1</em>)</span>
<?php

$irc = "https://irc.prettyboytellem.com";
$home = CONFIG_WEBROOT;
if (CONFIG_CLEAN_URL) $rss = CONFIG_WEBROOT . "rss";
else $rss = CONFIG_WEBROOT . "rss.php";
if (CONFIG_CLEAN_URL) $config = CONFIG_WEBROOT . "config";
else $config = CONFIG_WEBROOT . "config.php";
$sysop = CONFIG_WEBROOT . "op/";
print <<<HTML
	<nav class="info-links">
	<a href="$home">Home</a>
	<a href="$irc">Chat Room</a>
	<a href="$rss">RSS</a>
	<a href="$config">Configuration</a>
	<a href="$sysop">Sysop Panel</a>
	</nav>
HTML;
