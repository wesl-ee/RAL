<?php
$CONFIG_WEBROOT = CONFIG_WEBROOT;
$pagetitle = htmlspecialchars($pagetitle, ENT_QUOTES);
$pagedesc = htmlspecialchars($pagedesc, ENT_QUOTES);
print
<<<HTML
	<meta name=viewport content="width=device-width,
	maximum-scale=1, minimum-scale=1">
	<link rel=stylesheet href="${CONFIG_WEBROOT}css/base.css">
	<link rel=icon type="image/x-icon" href="${CONFIG_WEBROOT}favicon.ico">

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
print
<<<HTML
	<title>$pagetitle - RAL Neo-Forum Textboard</title>
	<meta name=description content="$pagedesc"/>

HTML;
?>
