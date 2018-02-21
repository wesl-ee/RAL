<?php
$ROOT="../";
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/post.php";
include "{$ROOT}includes/fetch.php";

header("Content-type: text/xml");
$CONFIG_WEBROOT = CONFIG_WEBROOT;
$CONFIG_ADMIN_MAIL = CONFIG_ADMIN_MAIL;
$posts = fetch_recent_posts(20);

print
<<<XML_HEAD
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>
		<title>RAL Neoforum Textboard</title>
		<description>The world's first
		and last Neoforum / Textboard.
		Experience the VIRTUAL WORLD today</description>
		<link>$CONFIG_WEBROOT</link>
		<lastBuildDate>{$posts[0]->date}</lastBuildDate>
		<webMaster>$CONFIG_ADMIN_MAIL</webMaster>
		<image>
			<url>{$CONFIG_WEBROOT}favicon.ico</url>
			<title>RAL Favicon</title>
			<link>$CONFIG_WEBROOT</link>
		</image>
		<generator>RAL</generator>

XML_HEAD;

foreach ($posts as $post) {
	if (!($post->topic - $post->id))
		$title = "User Created New Topic on [$post->continuity]";
	else
		$title = "New Post in [$post->continuity / $post->topic]";
	print
<<<ITEM
		<item>
			<title><![CDATA[$title]]></title>
			<link>$post->url</link>
			<guid isPermaLink="true">$post->url</guid>
			<description><![CDATA[{$post->toHtml()}]]></description>
			<pubDate>$post->date</pubDate>
		</item>

ITEM;
}

print
<<<XML_DONE
	</channel>
</rss>
XML_DONE;
