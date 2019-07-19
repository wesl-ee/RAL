<?php
$ROOT="../";
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/Ral.php";
include "{$ROOT}includes/Renderer.php";

header("Content-type: text/xml;charset=UTF-8");
$CONFIG_CANON_URL = CONFIG_CANON_URL;
$rm = new RAL\ResourceManager();
$Renderer = new RAL\Renderer($rm);
$Renderer->themeFromCookie($_COOKIE);
$Ral = new RAL\Ral($rm);
$recent = $Ral->SelectRecent(20);

// RFC822 date with the year extended to 4-digits
$lastbuild = date(DATE_RSS, strtotime($iterator->Selection[0]->Created));

// Portability (W3C recommendation)
if (CONFIG_CLEAN_URL) $self = CONFIG_CANON_URL . "/rss";
else $self = CONFIG_CANON_URL . "/rss.php";

print <<<XML_HEAD
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>RAL Neoforum Textboard</title>
		<description>The world's first
		and last Neoforum / Textboard.
		Experience the VIRTUAL WORLD today</description>
		<link>$CONFIG_CANON_URL</link>
		<lastBuildDate>$lastbuild</lastBuildDate>
		<image>
			<url>{$CONFIG_CANON_URL}/favicon.gif</url>
			<title>RAL Neoforum Textboard</title>
			<link>$CONFIG_CANON_URL</link>
		</image>
		<generator>RAL</generator>
		<atom:link href="$self" rel="self"
		type="application/rss+xml" />

XML_HEAD;
$Renderer->RecentSlice($recent, "rss");
print <<<XML_DONE
	</channel>
</rss>
XML_DONE;
