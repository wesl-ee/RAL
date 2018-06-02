<?php
$ROOT="../";
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/ContinuityIterator.php";

header("Content-type: text/xml");
$CONFIG_CANON_URL = CONFIG_CANON_URL;
$RM = new RAL\ResourceManager();
$iterator = new RAL\ContinuityIterator($RM);
$posts = $iterator->selectRecent(20);

print <<<XML_HEAD
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>
		<title>RAL Neoforum Textboard</title>
		<description>The world's first
		and last Neoforum / Textboard.
		Experience the VIRTUAL WORLD today</description>
		<link>$CONFIG_CANON_URL</link>
		<lastBuildDate>{$iterator->Selection[0]->Created}</lastBuildDate>
		<image>
			<url>{$CONFIG_CANON_URL}favicon.ico</url>
			<title>RAL Favicon</title>
			<link>$CONFIG_CANON_URL</link>
		</image>
		<generator>RAL</generator>

XML_HEAD;
$iterator->render('rss');
print <<<XML_DONE
	</channel>
</rss>
XML_DONE;
