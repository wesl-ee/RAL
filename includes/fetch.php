<?php
/*
 * Return all continuites by [name, description]
*/
function fetch_continuities()
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	if (!$dbh) return false;
	mysqli_set_charset($dbh, 'utf8');
	$query = "SELECT `Name`, `Description`  FROM `Continuities`"
	. " ORDER BY `Name`";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = new continuity($row);
	}
	return $ret;
}
/*
 * Return all topics in a $continuity in the standard form
*/
function fetch_topics($continuity)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	if (!$dbh) return false;
	mysqli_set_charset($dbh, 'utf8');
	$continuity = mysqli_real_escape_string($dbh, $continuity);
	$query = "SELECT `Id`, `Continuity`, `Topic`, `Content`"
	. ", `Created` AS `Date`, `Auth` FROM `Posts`"
	. " WHERE `Continuity`='$continuity' GROUP BY `Topic` ORDER BY MAX(`Created`) DESC";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = new post($row);
	}
	return $ret;
}
/*
 * Returns all posts in a ($continuity, $topic) keyed pair in the standard form
*/
function fetch_posts($continuity, $topic)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	if (!$dbh) return false;
	mysqli_set_charset($dbh, 'utf8');
	$continuity = mysqli_real_escape_string($dbh, $continuity);
	$topic = mysqli_real_escape_string($dbh, $topic);
	$query = "SELECT `Id`, `Continuity`, `Topic`, `Content`"
	. ", `Created` AS `Date`, `Auth` FROM `Posts`"
	. " WHERE `Continuity`='$continuity' AND `Topic`=$topic";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = new post($row);
	}
	return $ret;
}
/*
 * Return a list of all post numbers in a given ($continuity, $topic) keyed pair
 * We use the result for error checking and validation when
 * CONFIG_REALTIME_ENABLE is tripped out
*/
function fetch_post_nums($continuity, $topic)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	if (!$dbh) return false;
	mysqli_set_charset($dbh, 'utf8');
	$continuity = mysqli_real_escape_string($dbh, $continuity);
	$topic = mysqli_real_escape_string($dbh, $topic);
	$query = "SELECT `Id` FROM `Posts`"
	. " WHERE `Continuity`='$continuity' AND `Topic`=$topic";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = $row['Id'];
	}
	return $ret;
}
/*
 * Return a list of all topic numbers in a given $continuity
 * We use the result for error checking an validation when
 * CONFIG_REALTIME_ENABLE is tripped out
*/
function fetch_topic_nums($continuity)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	if (!$dbh) return false;
	mysqli_set_charset($dbh, 'utf8');
	$continuity = mysqli_real_escape_string($dbh, $continuity);
	$query = "SELECT `Id` FROM `Posts`"
	. " WHERE `Continuity`='$continuity' GROUP BY `Topic` ORDER BY MAX(`Created`) DESC";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = $row['Id'];
	}
	return $ret;
}
function fetch_recent_post_nums($n)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	if (!$dbh) return false;
	mysqli_set_charset($dbh, 'utf8');
	$query = "SELECT `Id` FROM `Posts` ORDER BY `Created` DESC LIMIT $n";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = $row['Id'];
	}
	return $ret;
}
/*
 * Grab the first $n posts hot off the press from all continuities
*/
function fetch_recent_posts($n)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	if (!$dbh) return false;
	mysqli_set_charset($dbh, 'utf8');
	$query = "SELECT `Id`, `Continuity`, `Topic`, `Content`"
	. ", `Created` AS `Date`, `Auth` FROM `Posts` ORDER"
	. " BY `Date` DESC LIMIT $n";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = new post($row);
	}
	return $ret;
}
?>
