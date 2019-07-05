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
	$GLOBALS['flame'] = $row['Type'];
	switch($GLOBALS['flame']) {
	case 'SLOW':
		sleep(rand(1, 5));
		$fuckem = rand(1, 100);
		if ($fuckem > 60) {
			// 40% - 500 Error
			http_response_code(500);
			die;
		}
	}
}

function addban($auth, $type) {
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');

	$query = <<<SQL
	INSERT INTO `Bans`
		(`Id`, `Type`) VALUES (
		?, ?) ON DUPLICATE KEY UPDATE Type = ?
SQL;
	$stmt = $dbh->prepare($query);
	$stmt->bind_param('sss', $auth, $type, $type);
	$stmt->execute();
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
	$centerX = $width / 2; $centerY = $height / 2;

	$id = uniqid();
	$imgfile = "$id.jpg";
	$keyfile = "$id.txt";

	$keypath = CONFIG_LOCALROOT . "tmp/robocheck-answers/"
	. substr($id, 0, 2) . '/';
	$imgpath = CONFIG_LOCALROOT . "www/robocheck/"
	. substr($id, 0, 2) . '/';

	do {
		$answer = rand_line(CONFIG_WORDLIST);
		if (@$GLOBALS['flame'] == 'HELL')
			$answer = random_bytes(10);
		$text = $answer;

		$lines = 5; $angle = 0;
		$font = CONFIG_LOCALROOT . "www/res/mouthbrebb.ttf";

		$im = imagecreatetruecolor($width, $height);
		$bg = imagecolorallocate($im, 230, 80, 0);
		$fg = imagecolorallocate($im, 255, 255, 255);
		$ns = imagecolorallocate($im, 200, 200, 200);
		imagefill($im, 0, 0, $bg);

		$centerX = $width / 2;
		$centerY = $height / 2;
		list($left, $bottom, $right, , , $top) = imageftbbox(20, $angle, $font, $text);
		$left_offset = ($right - $left) / 2;
		$top_offset = ($bottom - $top) / 2;
		$x = $centerX - $left_offset;
		$y = $centerY + $top_offset;
	// No text larger than the canvas
	} while ($x < 0);

	imagettftext($im, 20, $angle, $x, $y, $fg, $font, $text);

	while ($lines--) {
		imageline($im, 0, rand(0, $height), $width, rand(0, $height), $fg);
	}

	for($i=0;$i<1000;$i++) {
		imagesetpixel($im,rand()%$width,rand()%$height,$fg);
	}

	if (!is_dir($keypath)) mkdir($keypath);
	if (!is_dir($imgpath)) mkdir($imgpath);

	imagegif($im, $imgpath . $imgfile);
	imagedestroy($im);

	// Stash the answer in tmp/robocheck-answers/uniqid().txt
	file_put_contents($keypath . $keyfile, $answer);

	return [
		"id" => $id,
		"src" => CONFIG_WEBROOT . "robocheck/"
		. substr($id, 0, 2) . "/$imgfile",
		"height" => $height,
		"width" => $width
	];
}
/*
 * Validate a $user's answer to the robocheck
*/
function check_robocheck($id, $answer)
{
	$keypath = CONFIG_LOCALROOT . "tmp/robocheck-answers/"
	. substr($id, 0, 2) . "/";
	$imgpath = CONFIG_LOCALROOT . "www/robocheck/"
	. substr($id, 0, 2) . '/';

	$imgfile = "$id.jpg";
	$keyfile = "$id.txt";

	if (strpos(get_absolute_path($keypath . $keyfile)
	, CONFIG_LOCALROOT . "tmp/robocheck-answers") !== 0)
		return false;
	if (strpos(get_absolute_path($imgpath . $imgfile)
	, CONFIG_LOCALROOT . "www/robocheck") !== 0)
		return false;
//	if (!is_file($keypath)) return false;

	$a = chop(file_get_contents($keypath . $keyfile));

	unlink($imgpath . $imgfile);
	unlink($keypath . $keyfile);

	return !strcasecmp($a, $answer);
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
