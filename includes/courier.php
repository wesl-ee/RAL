<?php
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
function fetch_topics($timeline)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$query = "SELECT `Id`, `Owner`, `Content`, MAX(Modified) AS `Modified` FROM `Posts`"
	. " WHERE `Timeline`='$timeline' GROUP BY `Topic` ORDER BY MAX(`Modified`) DESC";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = [
			'id' => $row['Id'],
			'modified' => $row['Modified'],
			'content' => $row['Content']
		];
	}
	return $ret;
}
function fetch_posts($timeline, $topic)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$topic = mysqli_real_escape_string($dbh, $topic);
	$query = "SELECT `Id`, `Auth`, `Open`, `Content`, `Modified` FROM `Posts`"
	. " WHERE `Timeline`='$timeline' AND `Topic`=$topic AND (Id!=$topic)";
	$res = mysqli_query($dbh, $query);
	$ret = [];
	while ($row = mysqli_fetch_assoc($res)) {
		$ret[] = [
			'id' => $row['Id'],
			'modified' => $row['Modified'],
			'content' => $row['Content'],
			'open' => $row['Open'] == 1,
			'mine' => ($_COOKIE['auth'] == $row['Auth'])
		];
	}
	return $ret;
}
?>
