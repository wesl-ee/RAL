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

// Expiry date of the auth cookie we hand out
define("CONFIG_COOKIE_TIMEOUT", 60*60*24*30);

// Your website! (trailing slash is important)
define("CONFIG_WEBROOT", "/");
define("CONFIG_LOCALROOT", "/var/http/hub/ral/");

// SPAM parameters
define("CONFIG_SPAM_DB", "/var/http/hub/ral/tmp/b8-wordlist.db");
define("CONFIG_SPAM_THRESHOLD", 0.75);
define("CONFIG_MIN_POST_BYTES", 25);
define("CONFIG_MAX_POST_BYTES", 5000);

// OP and poster identities
define("CONFIG_IDENTITY_LEN", 8);

define("CONFIG_SECRET_SALT", "NOT_SO_SECRET");

define("CONFIG_TMP_EXPIRY", 1440);

define("CONFIG_BANNER", "https://ralee.org/res/RAL.gif");

// Only used for the RSS feed
define("CONFIG_CANON_URL", "https://ralee.org");

// Only set if you have set up lighttpd like in the example config!
// true: https:ralee.org/max/Anime/1
// false: https:ralee.org/max.php?continuity=Anime&topic=1
define("CONFIG_CLEAN_URL", false);

// Themes
define("CONFIG_THEMES", [
	"Classic",
]);

// Default theme from above
define("CONFIG_DEFAULT_THEME", "Classic");
