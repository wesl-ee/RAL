<?php
// MySQL server config options
define("CONFIG_RAL_SERVER", "MY_SERVER");
define("CONFIG_RAL_USERNAME", "MY_USERNAME");
define("CONFIG_RAL_PASSWORD", "MY_PASSWORD");
define("CONFIG_RAL_DATABASE", "MY_DATABASE");

// Logs
define("CONFIG_RAL_LOG", "/var/log/MY_LOG.log");

// shmem array of listening clients
// 0xC0FFEE
define("CONFIG_RAL_SHMCLIENTLIST", 12648430);

// Message queue, general shared memory, and semaphore
define("CONFIG_RAL_QUEUEKEY", 12648430);
define("CONFIG_RAL_SHMKEY", 12648431);
define("CONFIG_RAL_SEMKEY", 12648432);

// Maximum post length in characters
define("CONFIG_RAL_POSTMAXLEN", 5000);

// Number of continuities displayed per page
define("CONFIG_PER_PAGE", 5);

// After this time, PHP will consider a listener
// who has not been updated recently as having timed out
define("CONFIG_CLIENT_TIMEOUT", 15);

// You!
define("CONFIG_ADMIN_MAIL", "webmaster@domain.tld");
define("CONFIG_ADMIN_NAME", "Admininstrator");

// The theme that everyone starts with
define("CONFIG_DEFAULT_THEME", "20XX");

// Expiry date of the auth cookie we hand out
define("CONFIG_COOKIE_TIMEOUT", 60*60*24*30);

// Your website! (trailing slash is important)
define("CONFIG_WEBROOT", "https:ral.space/");
define("CONFIG_LOCALROOT", "/var/http/hub/ral/");

// Only set if you have set up lighttpd like in the example config!
// true: https:ral.space/max/Anime/1
// false: https:ral.space/max.php?continuity=Anime&topic=1
define("CONFIG_CLEAN_URL", false);

// A line-seperated list of words to use for the robocheck
define("CONFIG_WORDLIST", "/usr/share/wordlists/rockyou.txt");

// Users receive real-time updates about topics; no need to refresh!
define("CONFIG_REALTIME_ENABLE", false);

// Site-wide theming
// Pulls in the CSS and JS files
define("CONFIG_THEMES",
[
	// Display name => File name
	'20XX' => '20XX',
	'30XX' => '30XX',
	'Kinoko' => 'Kinoko',
	'Rain' => 'Rain',
	'PR' => '【=◈︿◈=】'
]);
?>
