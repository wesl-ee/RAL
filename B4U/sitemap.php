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
	"cleanurl" => CONFIG_WEBROOT,
	"dirtyurl" => CONFIG_WEBROOT,
	"changefreq" => "Daily",
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
	"cleanurl" => CONFIG_WEBROOT . "info/help",
	"dirtyurl" => CONFIG_WEBROOT . "info.php?help",
	"changefreq" => "Monthly",
	],
	[
	"cleanurl" => CONFIG_WEBROOT . "doc/hacking",
	"dirtyurl" => CONFIG_WEBROOT . "doc.php?hacking",
	"changefreq" => "Monthly",
	],
	[
	"cleanurl" => CONFIG_WEBROOT . "doc/readme",
	"dirtyurl" => CONFIG_WEBROOT . "doc.php?readme",
	"changefreq" => "Monthly",
	],
	[
	"cleanurl" => CONFIG_WEBROOT . "doc/install",
	"dirtyurl" => CONFIG_WEBROOT . "doc.php?install",
	"changefreq" => "Monthly",
	],
	[
	"cleanurl" => CONFIG_WEBROOT . "doc/license",
	"dirtyurl" => CONFIG_WEBROOT . "doc.php?license",
	"changefreq" => "Monthly",
	]

];

// Map all continuities
$continuities = fetch_continuities();
foreach ($continuities as $c) {
	$pages[] = [
	"cleanurl" => CONFIG_WEBROOT . "max/$c[name]",
	"dirtyurl" => CONFIG_WEBROOT . "max.php?continuity=$c[name]",
	"changefreq" => "Daily"
	];
	$copics = fetch_topic_nums($c["name"]);
	foreach ($copics as $p) {
		$pages[] = [
			"cleanurl" => CONFIG_WEBROOT . "max/$c[name]/$p",
			"dirtyurl" => CONFIG_WEBROOT . "max.php?continuity=$c[name]&topic=$p"
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
