<?php
$ROOT="../";
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/fetch.php";


header("Content-type: text/xml");


print
<<<XML_HEAD
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

XML_HEAD;

// (Relatively) static pages
$pages = [
	[
	"cleanurl" => CONFIG_WEBROOT . "select",
	"dirtyurl" => CONFIG_WEBROOT . "select.php",
	"changefreq" => "Never",
	],
	[
	"cleanurl" => CONFIG_WEBROOT . "info",
	"dirtyurl" => CONFIG_WEBROOT . "info.php",
	"changefreq" => "Monthly",
	],
	[
	"cleanurl" => CONFIG_WEBROOT . "info/theme",
	"dirtyurl" => CONFIG_WEBROOT . "info.php?theme",
	"changefreq" => "Monthly",
	],
	[
	"cleanurl" => CONFIG_WEBROOT . "info/announce",
	"dirtyurl" => CONFIG_WEBROOT . "info.php?announce",
	"changefreq" => "Weekly",
	],
	[
	"cleanurl" => CONFIG_WEBROOT . "info/help",
	"dirtyurl" => CONFIG_WEBROOT . "info.php?help",
	"changefreq" => "Monthly",
	]
];

// Map all timelines
$timelines = fetch_timelines();
foreach ($timelines as $t) {
	$pages[] = [
	"cleanurl" => CONFIG_WEBROOT . "max/$t[name]",
	"dirtyurl" => CONFIG_WEBROOT . "max.php?timeline=$t[name]",
	"changefreq" => "Daily"
	];
	$topics = fetch_topic_nums($t["name"]);
	foreach ($topics as $p) {
		$pages[] = [
			"cleanurl" => CONFIG_WEBROOT . "max/$t[name]/$p",
			"dirtyurl" => CONFIG_WEBROOT . "max.php?timeline=$t[name]&topic=$p"
		];
	}
}

foreach ($pages as $page) {
	if (CONFIG_CLEAN_URL) $loc = $page["cleanurl"];
	else $loc = $page["dirtyurl"];
	print
<<<XML
	<url>
		<loc>$loc</loc>

XML;
	if ($page["changefreq"])
		print
<<<XML
		<changefreq>$page[changefreq]</changefreq>

XML;
	print
<<<XML
	</url>

XML;
}

print
<<<XML_DONE
</urlset>
XML_DONE;
