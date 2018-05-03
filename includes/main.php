<?php
include 'config.php';
include 'ResourceManager.php';
include 'mod.php';

$GLOBALS['RM'] = new RAL\ResourceManager();

// Generate an ID for posting authentication
if (!isset($_COOKIE['auth'])) {
	$auth = uniqid();
	clearban($auth);
	setcookie('auth', $auth, CONFIG_COOKIE_TIMEOUT + time(), '/');
} else {
	processbans($_COOKIE['auth']);
}

/*
 * Fill out the <head> for an HTML document (and put the $title in our format)
*/
function head($title, $desc, $theme = null)
{
	$WROOT = CONFIG_WEBROOT;
	$LOCALROOT = CONFIG_LOCALROOT;
	if (!$theme) $theme = 'Canon';
	print
<<<HTML
	<meta name=viewport content="width=device-width,
	maximum-scale=1, minimum-scale=1">
	<link rel=stylesheet href="${WROOT}css/Base.css">
	<link rel=stylesheet href="${WROOT}css/$theme.css">
	<link rel=icon type="image/x-icon" href="${ROOT}favicon.ico">
	<title>$title - RAL Neo-Forum Textboard</title>
	<meta name=description content="$pagedesc"/>

HTML;

	if (file_exists("${LOCALROOT}www/js/themes/$theme.js"))
		print
<<<HTML
	<script src="${WROOT}js/themes/$theme.js"></script>

HTML;
}
