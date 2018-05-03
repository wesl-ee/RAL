<?php
	$WROOT = CONFIG_WEBROOT;
	$LOCALROOT = CONFIG_LOCALROOT;
	print
<<<HTML
	<meta name=viewport content="width=device-width,
	maximum-scale=1, minimum-scale=1">
	<link rel=stylesheet href="${WROOT}css/Base.css">
	<link rel=icon type="image/x-icon" href="${WROOT}favicon.ico">

HTML;
	if ($theme) print <<<HTML
	<link rel=stylesheet href="${WROOT}css/$theme.css">

HTML;
	if (isset($pagetitle)) print <<<HTML
	<title>$pagetitle - RAL Neo-Forum Textboard</title>

HTML;
	if (isset($pagedesc)) print <<<HTML
	<meta name=description content="$pagedesc"/>

HTML;

if (file_exists("${LOCALROOT}www/js/themes/$theme.js")) print <<<HTML
	<script src="${WROOT}js/themes/$theme.js"></script>

HTML;
