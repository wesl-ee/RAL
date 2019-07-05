<?php
$cd = dirname(__FILE__) . '/';
include "{$cd}config.php";
include "{$cd}ResourceManager.php";
include "{$cd}mod.php";

// Generate a user identification independent of IP address
if (!isset($_COOKIE['id'])) {
	$auth = md5(userIp() . CONFIG_SECRET_SALT);
	setcookie('id', $auth, CONFIG_COOKIE_TIMEOUT + time(), '/');
}
function say($s) { print "$s\n"; }
function randomHex($len) {
	$chars = 'abcdef01234567890';
	for ($i = 0; $i < $len; $i++)
		$hex .= $chars[rand(0, strlen($chars) - 1)];
	return $hex;
}
function userIp() {
	if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
		return $_SERVER['HTTP_X_REAL_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		return $_SERVER['REMOTE_ADDR'];
	}
}
