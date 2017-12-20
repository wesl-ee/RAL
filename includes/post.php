<?php
// Magical BBCode parse
function bbbbbbb($string)
{
	$opened = []; $contents = [];
	$offset = 0;
	while (($a = indexOf($string, "[", $offset)) >= 0
	&& ($b = indexOf($string, "]", $offset)) > $a) {
		// Push the parsed contents to an array
		$contents[] = substr($string, $offset, $a - $offset);
		$tag = substr($string, $a, $b + 1 - $a);
		$contents[] = $tag;

		// Since we finished scanning the part of the string
		// that is behind the last seen bracket, advance
		// the scanning offset
		$offset = $b + 1;

		// Strip brackets
		$inside = substr($tag, 1, strlen($tag) - 2);

		// Is this a closing or opening tag?
		if ($inside{0} == "/") {
			$tag = substr($inside, 1);
			if (!count($opened[$tag]))
				continue;
			$opening_tag = array_pop($opened[$tag]);
			$from = $opening_tag["index"];
			$param = $opening_tag["param"];
			$to = count($contents) - 1;

			switch($tag) {
			case  'b':
				$open = "<strong>";
				$close = "</strong>";
				break;
			case 'i':
				$open = "<em>";
				$close = "</em>";
				break;
			case 'color':
				$color = htmlspecialchars($param);
				$open = "<span style='color:$color'>";
				$close = "</span>";
				break;
			case 'quote':
				$open = "<blockquote>";
				$close = "</blockquote>";
				break;
			case 'url':
				$url = htmlspecialchars($param);
				$open = "<a href='$url'>";
				$close = "</a>";
				break;
			case 'code':
				$open = "<pre>";
				$close = "</pre>";
				break;
			default: continue;
			}
			$contents[$from] = $open;
			$contents[$to] = $close;
		} else {
			if (($c = indexOf($inside, "=")) > 0) {
				$tag = substr($inside, 0, $c);
				$param = substr($inside, $c + 1);
			} else {
				$tag = $inside;
				unset($param);
			}
			$opened[$tag][] = [
				"index" => count($contents) - 1,
				"param" => $param
			];
		}
	}
	$contents[] = substr($string, $offset);
	return join($contents);
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
