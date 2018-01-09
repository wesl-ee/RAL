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
function gen_robocheck($user)
{
	$imgpath = CONFIG_LOCALROOT . "B4U/robocheck/$user/";
	$keypath = CONFIG_LOCALROOT . "tmp/robocheck-answers/$user/";
	if (strpos(get_absolute_path($imgpath)
	, CONFIG_LOCALROOT . "B4U/robocheck") !== 0
	|| (!is_dir($imgpath) && !mkdir($imgpath))
	|| (!is_dir($keypath) && !mkdir($keypath)))
		die;

	$id = uniqid();
	$imgfile = "$id.jpg";
	$keyfile = "$id.txt";

	$tmp = CONFIG_LOCALROOT . "tmp/";
	$key = rand_line(CONFIG_WORDLIST);

	system("convert -size 165x70 plasma:fractal $tmp/$id-fractal.jpg");
	system("convert $tmp/$id-fractal.jpg -paint 10 $tmp/$id-background.jpg");
	system("convert -size 165x -background 'rgba(0,0,0,0)' -fill black -blur 0x1 -blur 0x1 label:'$key' $tmp/$id-text.png");
	system("composite -gravity center $tmp/$id-text.png $tmp/$id-background.jpg $tmp/$id-final.jpg");

	unlink("$tmp/$id-background.jpg");
	unlink("$tmp/$id-fractal.jpg");
	unlink("$tmp/$id-text.png");

	rename("$tmp/$id-final.jpg", $imgpath . $imgfile);
	file_put_contents($keypath . $keyfile, $key);

	return [
		"id" => $id,
		"src" => CONFIG_WEBROOT . "robocheck/$user/$imgfile",
	];
}
function check_robocheck($user, $id, $answer)
{
	$imgfile = CONFIG_LOCALROOT . "B4U/robocheck/$user/$id.jpg";
	$keyfile = CONFIG_LOCALROOT . "tmp/robocheck-answers/$user/$id.txt";

	if (file_get_contents($keyfile) !== $answer)
		return false;
	unlink($imgfile);
	unlink($keyfile);
	return true;
}
function rand_line($fname, $maxlen = 4096) {
	$handle = fopen($fname, "r");
	if (!$handle) return False;
	$random_line = null;
	$count = 0;
	while (($line = fgets($handle, $maxlen)) !== false) {
		$count++;
		if(rand() % $count == 0) {
			$random_line = $line;
		}
	}
	fclose($handle);
	return $random_line;
}
function get_absolute_path($path)
{
	$parts = explode('/', $path);
	$absolutes = [];
	foreach($parts as $part) {
		if ($part == '.') continue;
		if ($part == '..') array_pop($absolutes);
		else $absolutes[] = $part;
	}
	return implode('/', $absolutes);
}
?>
