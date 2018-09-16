<?php
/*
 X X X X X X X X X X X X X X X X X X X X X X X
 X                  R A L                    X
 X                 Y U M I                   X
 X               ( 2 0 X X )                 X
 X                                           X
 X          A U T H O R I Z I N G            X
 X         SHIBARAKU O-MACHI KUDASAI         X
 X                  . . .                    X
 X                                           X
 X                                           X
 X A U T H O R I Z A T I O N C O M P L E T E X
 X                                           X
 X W E L C O M E V I R T U A L R E A L I T Y X
 X                                           X
 X U N L O C K R ( E ) A L E X I S T E N C E X
 X                                           X
 X C O N F I G U R A T I O N O P T I O N S   X
 X A L L O W T H E M A N I P U L A T I O N   X
 X        O F Y O U R R E A L I T Y          X
 X                                           X
 XX X X X X X X X X X X X X X X X X X X X X XX
*/

// MySQL server config options
define("CONFIG_RAL_SERVER", "MY_SERVER");
define("CONFIG_RAL_USERNAME", "MY_USERNAME");
define("CONFIG_RAL_PASSWORD", "MY_PASSWORD");
define("CONFIG_RAL_DATABASE", "MY_DATABASE");

// Maximum post length in characters
define("CONFIG_RAL_POSTMAXLEN", 5000);

// Expiry date of the auth cookie we hand out
define("CONFIG_COOKIE_TIMEOUT", 60*60*24*30);

// Your website! (trailing slash is important)
define("CONFIG_WEBROOT", "/");
define("CONFIG_LOCALROOT", "/var/http/hub/ral/");

define("CONFIG_TMP_EXPIRY", 1440);

// Only used for the RSS feed
define("CONFIG_CANON_URL", "https://ral.space");

// Only set if you have set up lighttpd like in the example config!
// true: https:ral.space/max/Anime/1
// false: https:ral.space/max.php?continuity=Anime&topic=1
define("CONFIG_CLEAN_URL", false);

// A line-seperated list of words to use for the robocheck
define("CONFIG_WORDLIST", "/usr/share/wordlists/rockyou.txt");

// Themes
define("CONFIG_THEMES", [
	"Classic",
]);
define("CONFIG_DEFAULT_THEME", "Classic"

// <video>s to be displayed on various user-facing screens
define("CONFIG_RESULT_VIDEOS", [
	"Success" => "https://cdn.prettyboytellem.com/ral/congratulations.webm",
	"Failure" => "https://cdn.prettyboytellem.com/ral/innocence.webm",
	"Config" => "https://cdn.prettyboytellem.com/ral/sailor-transform.webm"
]);
