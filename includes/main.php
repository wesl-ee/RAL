<?php
include 'config.php';
include 'ResourceManager.php';
include 'mod.php';

// Generate an ID for posting authentication
if (!isset($_COOKIE['auth'])) {
	$auth = uniqid();
	setcookie('auth', $auth, CONFIG_COOKIE_TIMEOUT + time(), '/');
}
function say($s) { print "$s\n"; }
