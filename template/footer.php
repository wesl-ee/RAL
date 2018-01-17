<?php if (!isset($continuity)) {
		print "Improper template usage!";
		die;
} ?>
<footer>
<?php
	if (isset($topic)) {
		$q = $_GET;
		if (CONFIG_CLEAN_URL) {
			unset($q['continuity']); unset($q['topic']);
		}
		$q = http_build_query($q);
		if (empty($q)) $a = "?postmode";
		else $a = "?postmode&$q";
		print
<<<HTML
	<a href='$a' class=hoverbox>Reply to Topic</a>

HTML;
		$q = $_GET;
		unset($q['topic']);
		if (CONFIG_CLEAN_URL) {
			$q = http_build_query($q);
			if (empty($q)) $a =  CONFIG_WEBROOT
			. "max/$continuity";
			else $a = CONFIG_WEBROOT
			. "max/$continuity?$q";
		}
		else {
			$q = http_build_query($q);
			$a = "?$q";
		}

		print
<<<HTML
	<a href='$a' class=hoverbox>Return</a>
HTML;
	} else {
		$q = $_GET;
		if (CONFIG_CLEAN_URL) {
			unset($q['continuity']); unset($q['topic']);
		}
		$q = http_build_query($q);
		if (empty($q)) $a = "?postmode";
		else $a = "?postmode&$q";
		print
<<<HTML
		<a href='$a' class=hoverbox>Create a Topic</a>

HTML;
	}
	print
<<<HTML
	</footer>

HTML;
?>
