<?php
/*
 * Act on any outstanding bans a user has incurred
*/
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

/*
 * Reset a user's bans
*/
function clearban($auth)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$auth = mysqli_real_escape_string($dbh, $auth);

	// SELECT is much faster than DELETE
	$query = "SELECT 1 FROM `Bans` WHERE `Id`='$auth'";
	$result = mysqli_query($dbh, $query);

	// Only DELETE if it was SELECTed
	if ($row = mysqli_fetch_assoc($result)) {
		$query = "DELETE FROM `Bans` WHERE `Id`='$auth'";
		mysqli_query($dbh, $query);
	}
}
/*
 * Create a robocheck image and its answer
*/
function gen_robocheck()
{
	$height = 70; $width = 165;
	$imgpath = CONFIG_LOCALROOT . "www/robocheck/";
	$keypath = CONFIG_LOCALROOT . "tmp/robocheck-answers/";

	$id = uniqid();
	$imgfile = "$id.jpg";
	$keyfile = "$id.txt";

	$tmp = CONFIG_LOCALROOT . "tmp/";
	$key = rand_line(CONFIG_WORDLIST);

	system("convert -size {$width}x{$height} plasma:fractal -colorspace Gray $tmp/$id-fractal.jpg");
	system("convert $tmp/$id-fractal.jpg -paint 10 $tmp/$id-background.jpg");
	system("convert -size {$width}x -background 'rgba(0,0,0,0)' -fill black -spread 1 -blur 0x1 -blur 0x1 label:'$key' $tmp/$id-text.png");
	system("composite -gravity center $tmp/$id-text.png $tmp/$id-background.jpg $tmp/$id-final.jpg");

	// Clean up temporary files
	unlink("$tmp/$id-background.jpg");
	unlink("$tmp/$id-fractal.jpg");
	unlink("$tmp/$id-text.png");

	// Stash the image in www/robocheck/uniqid().jpg
	rename("$tmp/$id-final.jpg", $imgpath . $imgfile);

	// Stash the answer in tmp/robocheck-answers/uniqid().txt
	file_put_contents($keypath . $keyfile, $key);

	return [
		"id" => $id,
		"src" => CONFIG_WEBROOT . "robocheck/$imgfile",
		"height" => $height,
		"width" => $width
	];
}
/*
 * Validate a $user's answer to the robocheck
*/
function check_robocheck($id, $answer, $user = null)
{
	if ($user === null) $user = $_COOKIE["auth"];
	$imgfile = CONFIG_LOCALROOT . "www/robocheck/$user/$id.jpg";
	$keyfile = CONFIG_LOCALROOT . "tmp/robocheck-answers/$user/$id.txt";

	if (strpos(get_absolute_path($imgfile)
	, CONFIG_LOCALROOT . "B4U/robocheck") !== 0)
		return false;

	$a = chop(file_get_contents($keyfile));

	unlink($imgfile);
	unlink($keyfile);

	return ($a == $answer);
}
/*
 * T-O-R-O-N-T-O
*/
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
/*
 * D-R-A-K-E THAT'S M-E
*/
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
