<?php
$ROOT="../";
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/post.php";
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
	"cleanurl" => CONFIG_CANON_URL,
	"dirtyurl" => CONFIG_CANON_URL,
	"changefreq" => "Daily",
	],
	[
	"cleanurl" => CONFIG_CANON_URL . "info",
	"dirtyurl" => CONFIG_CANON_URL . "info.php",
	],
	[
	"cleanurl" => CONFIG_CANON_URL . "info/theme",
	"dirtyurl" => CONFIG_CANON_URL . "info.php?theme",
	],
	[
	"cleanurl" => CONFIG_CANON_URL . "info/help",
	"dirtyurl" => CONFIG_CANON_URL . "info.php?help",
	],
	[
	"cleanurl" => CONFIG_CANON_URL . "doc/hacking",
	"dirtyurl" => CONFIG_CANON_URL . "doc.php?hacking",
	],
	[
	"cleanurl" => CONFIG_CANON_URL . "doc/readme",
	"dirtyurl" => CONFIG_CANON_URL . "doc.php?readme",
	],
	[
	"cleanurl" => CONFIG_CANON_URL . "doc/install",
	"dirtyurl" => CONFIG_CANON_URL . "doc.php?install",
	],
	[
	"cleanurl" => CONFIG_CANON_URL . "doc/license",
	"dirtyurl" => CONFIG_CANON_URL . "doc.php?license",
	]

];

// Map all continuities
$continuities = fetch_continuities();
foreach ($continuities as $c) {
	$pages[] = [
	"cleanurl" => CONFIG_CANON_URL . "max/$c->name",
	"dirtyurl" => CONFIG_CANON_URL . "max.php?continuity=$c->name",
	"changefreq" => "Daily"
	];
	$topics = fetch_topic_nums($c->name);
	foreach ($topics as $p) {
		$pages[] = [
			"cleanurl" => CONFIG_CANON_URL . "max/$c->name/$p",
			"dirtyurl" => CONFIG_CANON_URL . "max.php?continuity=$c->name&topic=$p"
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
