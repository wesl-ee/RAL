<?php
function processbans($auth)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$auth = mysqli_real_escape_string($dbh, $auth);
	$query = "SELECT `Type` FROM `Bans` WHERE `Id`='$auth'";
	$result = mysqli_query($dbh, $query);
	while ($row = mysqli_fetch_assoc($result))
	switch($row['Type']) {
	case 'SLOW':
		sleep(rand(1, 5));
	break;
	}
}

function clearban($auth)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$auth = mysqli_real_escape_string($dbh, $auth);
	$query = "SELECT 1 FROM `Bans` WHERE `Id`='$auth'";
	$result = mysqli_query($dbh, $query);
	if ($row = mysqli_fetch_assoc($result)) {
		$query = "DELETE FROM `Bans` WHERE `Id`='$auth'";
		mysqli_query($dbh, $query);
	}
}

?>
