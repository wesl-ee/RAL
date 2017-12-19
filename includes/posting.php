<?php
// Magical BBCode parse
function bbbbbbb($string)
{
	$lines = explode(PHP_EOL, $string);
	$newlines = $lines;
	$opened = [
		'b' => 0,
		'i' => 0,
		'color' => 0,
		'quote' => 0,
		'url' => 0,
		'code' => 0
	];
	$commits = [];
	// First, find all changes which should be made and make note of them
	for ($i = 0; $i < count($lines); $i++) {
		$a = 0;
		while (($a = indexOf($lines[$i], "[", $a) + 1) > 0
		&& ($b = indexOf($lines[$i], "]", $a)) > $a) {
			$inside = substr($lines[$i], $a, $b - $a);
			if ($inside{0} == "/") {
				$tag = substr($inside, 1);
				if (!$opened[$tag]) continue;

				switch($tag) {
				case  'b':
					$replacement = "</strong>";
					break;
				case 'i':
					$replacement = "</em>";
					break;
				case 'color':
					$replacement = "</span>";
					break;
				case 'quote':
					$replacement = "</blockquote>";
					break;
				case 'url':
					$replacement = "</a>";
					break;
				case 'code':
					$replacement = "</pre>";
					break;
				default: continue;
				}

				// Replace the bbCode tag
				$lines[$i] = substr($lines[$i], 0, $a - 1)
				. substr($lines[$i], $b + 1);

				$commits[$i][$a-1] = $replacement;
				$opened[$tag]--;
			} else {
				if (($c = indexOf($inside, "=")) >= 0) {
					$tag = substr($inside, 0, $c);
					$param = substr($inside, $c + 1);
				} else $tag = $inside;

				switch ($tag) {
				case  'b':
					$replacement = "<strong>";
					break;
				case 'i':
					$replacement = "<em>";
					break;
				case 'color':
					$replacement = "<span style=color:$param>";
					break;
				case 'quote':
					$replacement = "<blockquote>";
					break;
				case 'url':
					$replacement = "<a href='$param'>";
					break;
				case 'code':
					$replacement = "<pre>";
					break;
				default: continue;
				}

				// Replace the bbCode tag
				$lines[$i] = substr($lines[$i], 0, $a - 1)
				. substr($lines[$i], $b + 1);

				$commits[$i][$a-1] = $replacement;
				$opened[$tag]++;
			}
		}
	}

	// Commit the changes which were suggested
	foreach ($commits as $n => $changes) {
	$o = 0;
	foreach ($changes as $i => $change) {
		$lines[$n] = substr($lines[$n], 0, $i + $o)
		. $change
		. substr($lines[$n], $i + $o);
		$o += strlen($change);
	} }
	return join(PHP_EOL, $lines);
}
// Creates the post on a given (timeline, topic)
function create_post($timeline, $topic, $auth, $content)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$topic = mysqli_real_escape_string($dbh, $topic);
	$auth = mysqli_real_escape_string($dbh, $auth);
	$content = mysqli_real_escape_string($dbh, $content);

	mysqli_query("BEGIN TRANSACTION");
		// Insert post
		$query = "INSERT INTO `Posts` (`Id`, `Timeline`, `Topic`, `Auth`, `Content`) SELECT"
		. " `Post Count` AS `Id`"
		. ", '$timeline' AS `Timeline`"
		. ", $topic AS `Topic`"
		. ", '$auth' AS `Auth`"
		. ", '$content' AS `Content`"
		. " FROM `Timelines` WHERE Name='$timeline'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog($err);
			return false;
		}
		// Update postcount
		$query = "UPDATE `Timelines` SET `Post Count`=`Post Count`+1"
		. " WHERE `Name`='$timeline'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			ralog($err);
			mysqli_query("ROLLBACK");
			return false;
		}
	mysqli_query("COMMIT");
	ralog("Created Post");
	return true;
}
// Creates the post on a given (timeline, topic)
function create_topic($timeline, $auth, $content)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$auth = mysqli_real_escape_string($dbh, $auth);
	$content = mysqli_real_escape_string($dbh, $content);

	mysqli_query("BEGIN TRANSACTION");
		// Insert post
		$query = "INSERT INTO `Posts` (`Id`, `Timeline`, `Topic`, `Auth`, `Content`) SELECT"
		. " `Post Count` AS `Id`"
		. ", '$timeline' AS `Timeline`"
		. ", `Post Count` AS `Topic`"
		. ", '$auth' AS `Auth`"
		. ", '$content' AS `Content`"
		. " FROM `Timelines` WHERE Name='$timeline'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog("$err while creating topic");
			return false;
		}
		// Update postcount
		$query = "UPDATE `Timelines` SET `Post Count`=`Post Count`+1"
		. " WHERE `Name`='$timeline'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			ralog("$err while updating post count after creating a topic");
			mysqli_query("ROLLBACK");
			return false;
		}
	mysqli_query("COMMIT");
	ralog("Created topic");
	return true;
}
/*
 * Like strpos but does not loop over the
 * entire string when given an offset
*/
function indexOf($string, $substring, $offset = 0)
{
	$stringlen = strlen($string);
	$sublen = strlen($substring);
	for ($i = $offset, $j = 0; $i < $stringlen; $i++) {
		if ($string{$i} == $substring{$j}) {
			if (!(++$j - $sublen))
			return $i - $sublen + 1;
		} else $j = 0;
	}
	return -1;
}
function lastIndexOf($string, $substring, $offset = 0)
{
	$stringlen = strlen($string);
	$sublen = strlen($substring);
	for ($i = $stringlen - 1, $j = $sublen; $i + 1 - $offset; $i--) {
		if ($string{$i} == $substring{$j - 1}) {
			if (!--$j) return $i;
		}
		else $j = $sublen;
	}
	return $i - $offset;
}
?>
