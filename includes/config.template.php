<?php
// MySQL server config options
define("CONFIG_RAL_SERVER", "MY_SERVER");
define("CONFIG_RAL_USERNAME", "MY_USERNAME");
define("CONFIG_RAL_PASSWORD", "MY_PASSWORD");
define("CONFIG_RAL_DATABASE", "MY_DATABASE");

// Logs
define("CONFIG_RAL_LOG", "/var/log/MY_LOG.log");

// 0xC0FFEE
define("CONFIG_RAL_QUEUEKEY", 12648430);
define("CONFIG_RAL_SHMKEY", 12648431);
define("CONFIG_RAL_SEMKEY", 12648432);

// Shared memory variables
define("CONFIG_RAL_SHMCLIENTLIST", 12648430);

define("CONFIG_RAL_POSTMAXLEN", 5000);

// Number of timelines displayed per page
define("CONFIG_TIMELINES_PER_PAGE", 5);

define("CONFIG_CLIENT_TIMEOUT", 15);

define("CONFIG_ADMIN_MAIL", "webmaster@domain.tld");
define("CONFIG_ADMIN_NAME", "Admininstrator");

define("CONFIG_DEFAULT_THEME", "20XX");

define("CONFIG_COOKIE_TIMEOUT", 60*60*24*30);

define("CONFIG_THEMES",
[
	'20XX',
	'Lain'
]);
?>
