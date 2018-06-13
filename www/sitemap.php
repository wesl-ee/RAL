<?php
$ROOT="../";
include "{$ROOT}includes/main.php";
include "{$ROOT}includes/ContinuityIterator.php";

header("Content-type: text/xml;charset=UTF-8");
$CONFIG_WEBROOT = CONFIG_WEBROOT;
$RM = new RAL\ResourceManager();
$iterator = new RAL\ContinuityIterator($RM);
$posts = $iterator->select();

header("Content-type: text/xml");

print <<<XML_HEAD
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

XML_HEAD;

$iterator->render('sitemap');
print <<<XML_DONE
</urlset>
XML_DONE;
