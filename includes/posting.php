<?php
// Magical BBCode parse
function bbbbbbb($string)
{
	$tags = 'b|i|color|quote|url|code';
	while (preg_match_all('`\[('.$tags.')=?(.*?)\](.+?)\[/\1\]`',
	$string, $matches))
	foreach ($matches[0] as $key => $match) {
		$tag = $matches[1][$key];
		$param = $matches[2][$key];
		$innertext = $matches[3][$key];
		switch ($tag) {
		case 'b':
			$replacement = "<strong>$innertext</strong>";
			break;
                case 'i':
			$replacement = "<em>$innertext</em>";
			break;
		case 'color':
			$replacement = "<span style=\"color:"
			." $param;\">$innertext</span>";
			break;
		case 'quote':
			$replacement = "<blockquote>$innertext</blockquote>"
			. $param? "<cite>$param</cite>" : '';
			break;
		case 'url':
			$replacement = '<a href="'
			. ($param ? $param : $innertext) . "\">$innertext</a>";
			break;
		case 'code':
			$replacement = "<pre>$innertext</pre>";
			break;
		}
		$string = str_replace($match, $replacement, $string);
	}
	return $string;
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
?>
