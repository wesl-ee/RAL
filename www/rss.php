<?php
$ROOT="../";
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/ContinuityIterator.php";

header("Content-type: text/xml");
$CONFIG_WEBROOT = CONFIG_WEBROOT;
$iterator = new RAL\ContinuityIterator();
$posts = $iterator->selectRecent(20);

print <<<XML_HEAD
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>
		<title>RAL Neoforum Textboard</title>
		<description>The world's first
		and last Neoforum / Textboard.
		Experience the VIRTUAL WORLD today</description>
		<link>$CONFIG_WEBROOT</link>
		<lastBuildDate>{$posts[0]->date}</lastBuildDate>
		<image>
			<url>{$CONFIG_WEBROOT}favicon.ico</url>
			<title>RAL Favicon</title>
			<link>$CONFIG_WEBROOT</link>
		</image>
		<generator>RAL</generator>

XML_HEAD;
$iterator->renderAsRSSItems();
print <<<XML_DONE
	</channel>
</rss>
XML_DONE;
