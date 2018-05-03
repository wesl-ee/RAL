<?php
/*
 * Return all continuites by [name, description]
*/
function fetch_continuities()
{
	$dbh = $GLOBALS['RM']->getdb();
	$ret = [];

	$query = <<<SQL
		SELECT `Name`, `Description`, `Post Count`
		FROM `Continuities` ORDER BY `Name`
SQL;
	$res = $dbh->query($query);
	while ($row = $res->fetch_assoc()) {
		$ret[] = $row;
	}
	return $ret;
}
function fetch_overview($continuity)
{
	$dbh = $GLOBALS['RM']->getdb();
	$ret = [];

	$query = <<<SQL
		SELECT `Year`, COUNT(*) AS `Num` FROM `Topics`
		WHERE `Continuity`=? GROUP BY `Year`
SQL;
	$stmt = $dbh->prepare($query);
	$stmt->bind_param('s', $continuity);

	$stmt->execute();
	$res = $stmt->get_result();
	while ($row = $res->fetch_assoc()) {
		$ret[$row['Year']] = $row['Num'];
	}
	return $ret;
}
/*
 * Return all topics in a $continuity in the standard form
*/
function fetch_topics($continuity)
{
	$dbh = $GLOBALS['RM']->getdb();
	$ret = [];

	$query = <<<SQL
		SELECT `Id`, `Continuity`, `Topic`, `Content`
		, `Created`, `Year`, FROM `Topics`
		WHERE `Continuity`=? ORDER BY `Created` DESC";
SQL;
	$stmt = $dbh->prepare($query);
	$stmt->bind_param('s', $continuity);

	$stmt->execute();
	$res = $stmt->get_result();
	while ($row = $res->fetch_assoc()) {
		$ret[] = new post($row);
	}
	return $ret;
}
/*
 * Returns all posts in a ($continuity, $topic) keyed pair in the standard form
*/
function fetch_posts($continuity, $topic)
{
	$dbh = $GLOBALS['RM']->getdb();
	$ret = [];

	$query = <<<SQL
		SELECT `Id`, `Continuity`, `Topic`, `Content`
		, `Created` AS `Date`, `Auth` FROM `Posts`
		WHERE `Continuity`=? AND `Topic`=?
SQL;
	$stmt = $dbh->prepare($query);
	$stmt->bind_param('si', $continuity, $topic);

	$stmt->execute();
	$res = $stmt->get_result();
	while ($row = $res->fetch_assoc()) {
		$ret[] = new post($row);
	}
	return $ret;
}
/*
 * Return a list of all post numbers in a given ($continuity, $topic) keyed pair
*/
function fetch_post_nums($continuity, $topic)
{
	$dbh = $GLOBALS['RM']->getdb();
	$ret = [];

	$query = <<<SQL
		SELECT `Id` FROM `Posts`
		WHERE `Continuity`=? AND `Topic`=?
SQL;
	$stmt = $dbh->prepare($query);
	$stmt->bind_param('si', $continuity, $topic);

	$stmt->execute();
	$res = $stmt->get_result();
	while ($row = $res->fetch_assoc()) {
		$ret[] = $row['Id'];
	}
	return $ret;
}
/*
 * Return a list of all topic numbers in a given $continuity
*/
function fetch_topic_nums($continuity)
{
	$dbh = $GLOBALS['RM']->getdb();
	$ret = [];

	$query = <<<SQL
		SELECT `Id` FROM `Posts`
		WHERE `Continuity`=? GROUP BY `Topic`
		ORDER BY MAX(`Created`) DESC
SQL;
	$stmt = $dbh->prepare($query);
	$stmt->bind_param('s', $continuity);

	$stmt->execute();
	$res = $stmt->get_result();
	while ($row = $res->fetch_assoc()) {
		$ret[] = $row['Id'];
	}
	return $ret;
}
function fetch_recent_post_nums($n)
{
	$dbh = $GLOBALS['RM']->getdb();
	$ret = [];

	$query = <<<SQL
		SELECT `Id` FROM `Posts` ORDER BY `Created` DESC LIMIT ?
SQL;
	$stmt = $dbh->prepare($query);
	$stmt->bind_param('i', $n);

	$stmt->execute();
	$res = $stmt->get_result();
	$res = mysqli_query($dbh, $query);
	while ($row = $res->fetch_assoc()) {
		$ret[] = $row['Id'];
	}
	return $ret;
}
/*
 * Grab the first $n posts hot off the press from all continuities
*/
function fetch_recent_posts($n)
{
	$dbh = $GLOBALS['RM']->getdb();
	$ret = [];

	$query = <<<SQL
		SELECT `Id`, `Continuity`, `Topic`, `Content`
		, `Created`, `Year` FROM `Replies` ORDER
		BY `Created` DESC LIMIT ?
SQL;
	$stmt = $dbh->prepare($query);
	$stmt->bind_param('i', $n);

	$stmt->execute();
	$res = $stmt->get_result();
	while ($row = $res->fetch_assoc()) {
		$ret[] = new reply($row);
	}
	return $ret;
}
?>
