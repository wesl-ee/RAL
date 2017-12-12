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
// Attempt to append $content to the post
// The post will be created if it is not already so
function append_post($timeline, $topic, $auth, $content)
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

	// Verify that the user is indeed the owner of the post
	$query = "SELECT `Id`, `Content` FROM `Posts` WHERE `Topic`=$topic"
	. " AND `Auth`='$auth' AND `Open`=TRUE LIMIT 1";
	$res = mysqli_query($dbh, $query);
	if (!mysqli_num_rows($res)) {
		// Insert post
		$query = "INSERT INTO `Posts` (`Id`, `Timeline`, `Topic`, `Open`, `Auth`) SELECT"
		. " `Post Count` AS `Id`"
		. ", '$timeline' AS `Timeline`"
		. ", $topic AS `Topic`"
		. ", TRUE AS `Open`"
		. ", '$auth' AS `Auth`"
		. " FROM `Timelines` WHERE Name='$timeline'";
		mysqli_query($dbh, $query);
		if ($err = mysqli_error($dbh)) {
			ralog($err);
			return false;
		}

		// Update postcount
		$query = "SELECT `Post Count` FROM `Timelines`"
		. " WHERE `Name`='$timeline'";
		$post = mysqli_fetch_assoc(mysqli_query($dbh, $query))['Post Count'];
		$query = "UPDATE `Timelines` SET `Post Count`=`Post Count`+1"
		. " WHERE `Name`='$timeline'";
		mysqli_query($dbh, $query);
		if ($err = mysqli_error($dbh)) {
			ralog($err);
			return false;
		}
		ralog("Created Post");
	}
	else {
		$row = mysqli_fetch_assoc($res);
		$post = $row['Id'];
		$content = $row['Content'] . $content;
	}
	// Append the new content && update the modification time
	$query = "UPDATE `Posts` SET `Content`='$content',"
	. " `Modified`=current_timestamp() WHERE"
	. " Timeline='$timeline' AND Id=$post";
	mysqli_query($dbh, $query);

	// Retrieve post information
	$query = "SELECT `Id`, `Content`, `Created` FROM `Posts`"
	. " WHERE `Timeline`='$timeline' AND `Id`=$post";
	$res = mysqli_query($dbh, $query);
	$row = mysqli_fetch_assoc($res);

	// Return a BBEncoded version of our thread
	$id = $row['Id'];
	$content = bbbbbbb($row['Content']);
	$created = $row['Created'];

	return [
		'id' => $id,
		'content' => $content,
		'created' => $created
	];
}
function close_post($timeline, $topic, $auth)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$topic = mysqli_real_escape_string($dbh, $topic);
	$auth = mysqli_real_escape_string($dbh, $auth);

	$query = "UPDATE `Posts` SET `Open`=False WHERE"
	. " `Timeline`='$timeline' AND `Topic`=$topic AND `Auth`='$auth'";
	mysqli_query($dbh, $query);
}
?>
