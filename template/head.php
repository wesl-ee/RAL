<?php
$CONFIG_WEBROOT = CONFIG_WEBROOT;
$pagetitle = str_replace('"', '', $pagetitle);
$pagedesc = str_replace('"', '', $pagedesc);
print
<<<HTML
	<meta name=viewport content="width=device-width,
	maximum-scale=1, minimum-scale=1">
	<link rel=stylesheet href="${CONFIG_WEBROOT}css/base.css">


HTML;
$theme = get_theme();

$path = dirname(__FILE__);
if (file_exists("$path/../B4U/js/themes/$theme.js"))
	print
<<<HTML
	<script src="${CONFIG_WEBROOT}js/themes/$theme.js"></script>

HTML;
if (file_exists("$path/../B4U/css/$theme.css"))
	print
<<<HTML
	<link rel=stylesheet href="${CONFIG_WEBROOT}css/$theme.css">

HTML;
if (file_exists("$path/../B4U/css/favicons/favicon-$theme.ico"))
	$favicon = "${CONFIG_WEBROOT}css/favicons/"
	. "favicon-$theme.ico";
else
	$favicon = "${CONFIG_WEBROOT}css/favicons/favicon.ico";
print
<<<HTML
	<link rel=icon type="image/x-icon" href="$favicon">
	<title>$pagetitle - RAL Neo-Forum Textboard</title>
	<meta name=description content="$pagedesc"/>

HTML;
?>
