<?php if (!isset($continuity)) {
		print "Improper template usage!";
		die;
} ?>
<footer>
<?php
	if (isset($topic)) {
		if (CONFIG_CLEAN_URL) {
			$a =  CONFIG_WEBROOT . "max/" . urlencode($continuity);
		} else {
			$a = CONFIG_WEBROOT . "max.php?continuity="
			. urlencode($continuity);
		}
		print
<<<HTML
	<a href="$a" class=hoverbox>Return</a>

HTML;

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
