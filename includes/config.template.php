<?php
// MySQL server config options
define("CONFIG_RAL_SERVER", "MY_SERVER");
define("CONFIG_RAL_USERNAME", "MY_USERNAME");
define("CONFIG_RAL_PASSWORD", "MY_PASSWORD");
define("CONFIG_RAL_DATABASE", "MY_DATABASE");

// Logs
define("CONFIG_RAL_LOG", "/var/log/MY_LOG.log");

define("CONFIG_RAL_POSTMAXLEN", 5000);

// Number of timelines displayed per page
define("CONFIG_TIMELINES_PER_PAGE", 5);

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
?>
