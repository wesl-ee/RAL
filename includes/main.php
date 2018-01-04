<?php
include 'config.php';
include 'mod.php';

// Generate an ID for posting authentication
if (!isset($_COOKIE['auth'])) {
	$auth = uniqid();
	clearban($auth);
	setcookie('auth', $auth, CONFIG_COOKIE_TIMEOUT + time(), '/');
} else {
	processbans($_COOKIE['auth']);
}
if (!isset($_COOKIE['theme']))
	setcookie('theme', CONFIG_DEFAULT_THEME,
	CONFIG_COOKIE_TIMEOUT + time(), '/');


// Core functions
function ralog($m)
{
	$ip = $_SERVER['HTTP_X_REAL_IP'];
	$time = date('c');
	file_put_contents(
		CONFIG_RAL_LOG,
		"$time - ($ip) $m\n",
		FILE_APPEND|LOCK_EX
	);
}
function head($title)
{
	$ROOT = CONFIG_WEBROOT;
	print "<meta name=viewport content='width=device-width,"
	. " maximum-scale=1, minimum-scale=1'>"
	. "<link rel=stylesheet href='$ROOT"."css/base.css'>";
	$theme = get_theme();

	$path = dirname(__FILE__);
	if (file_exists("$path/../B4U/js/themes/$theme.js"))
		print "<script src='$ROOT"."js/themes/$theme.js'></script>";
	if (file_exists("$path/../B4U/css/$theme.css"))
		print "<link rel=stylesheet href='$ROOT"."css/$theme.css'>";
	print "<title>$title - RAL Neo-forum Textboard</title>";
}
function get_theme()
{
	if (!isset($_COOKIE['theme']))
		return CONFIG_DEFAULT_THEME;
	return $_COOKIE['theme'];
}
