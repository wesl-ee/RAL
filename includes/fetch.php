<?php
/*
 * Return all timelines by [name, description]
*/
function fetch_timelines()
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$query = "SELECT `Name`, `Description`  FROM `Timelines`"
	. " ORDER BY `Name`";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = [
			'name' => $row['Name'],
			'description' => $row['Description'],
		];
	}
	return $ret;
}
/*
 * Return all topics in a $timeline in the standard form
*/
function fetch_topics($timeline)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$query = "SELECT `Id`, `Auth`, `Content`, MAX(`Created`) AS `Date` FROM `Posts`"
	. " WHERE `Timeline`='$timeline' GROUP BY `Topic` ORDER BY MAX(`Created`) DESC";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = [
			'id' => $row['Id'],
			'timeline' => $timeline,
			'topic' => $row['Id'],
			'auth' => $row['Auth'],
			'date' => $row['Date'],
			'content' => nl2br(bbbbbbb($row['Content']))
		];
	}
	return $ret;
}
/*
 * Returns all posts in a ($timeline, $topic) keyed pair in the standard form
*/
function fetch_posts($timeline, $topic)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$topic = mysqli_real_escape_string($dbh, $topic);
	$query = "SELECT `Id`, `Auth`, `Content`, `Created` AS `Date` FROM `Posts`"
	. " WHERE `Timeline`='$timeline' AND `Topic`=$topic";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = [
			'id' => $row['Id'],
			'timeline' => $timeline,
			'topic' => $topic,
			'auth' => $row['Auth'],
			'date' => $row['Date'],
			'content' => nl2br(bbbbbbb($row['Content'])),
			'mine' => ($_COOKIE['auth'] == $row['Auth'])
		];
	}
	return $ret;
}
/*
 * Return a list of all post numbers in a given ($timeline, $topic) keyed pair
 * We use the result for error checking and validation when
 * CONFIG_REALTIME_ENABLE is tripped out
*/
function fetch_post_nums($timeline, $topic)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$topic = mysqli_real_escape_string($dbh, $topic);
	$query = "SELECT `Id` FROM `Posts`"
	. " WHERE `Timeline`='$timeline' AND `Topic`=$topic";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = $row['Id'];
	}
	return $ret;
}
/*
 * Return a list of all topic numbers in a given $timeline
 * We use the result for error checking an validation when
 * CONFIG_REALTIME_ENABLE is tripped out
*/
function fetch_topic_nums($timeline)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$query = "SELECT `Id` FROM `Posts`"
	. " WHERE `Timeline`='$timeline' GROUP BY `Topic` ORDER BY MAX(`Created`) DESC";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = $row['Id'];
	}
	return $ret;
}
/*
 * Grab the first $n posts hot off the press from all timelines
*/
function fetch_recent_posts($n)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$query = "SELECT `Id`, `Auth`, `Content`, `Created` AS `Date`, `Topic`, `Timeline` FROM `Posts` ORDER BY `Date` DESC LIMIT $n";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = [
			'id' => $row['Id'],
			'timeline' => $row['Timeline'],
			'topic' => $row['Topic'],
			'auth' => $row['Auth'],
			'date' => $row['Date'],
			'content' => nl2br(bbbbbbb($row['Content'])),
			'mine' => ($_COOKIE['auth'] == $row['Auth'])
		];
	}
	return $ret;
}
?>
