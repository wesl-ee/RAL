<?php
$cd = dirname(__FILE__) . '/';
include "{$cd}config.php";
include "{$cd}ResourceManager.php";
include "{$cd}mod.php";

// Generate an ID for posting authentication
if (!isset($_COOKIE['auth'])) {
	$auth = uniqid();
	setcookie('auth', $auth, CONFIG_COOKIE_TIMEOUT + time(), '/');
}
function say($s) { print "$s\n"; }
