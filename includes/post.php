<?php
class continuity {
	public $name;
	public $description;
	public $url;
	public $postcount;

	// Fills in a post's information from a SQL row from `Continuities`
	public function __construct($row, $year) {
		$this->name = $row['Name'];
		$this->description = $row['Description'];
		$this->postcount = $row['Post Count'];
		$this->url = $this->resolve();
	}

	/* Resolve the continuity to a URL */
	function resolve() {
		if (CONFIG_CLEAN_URL)
			$ret =  CONFIG_WEBROOT . "view/"
			. urlencode($this->name);
		else
			$ret = CONFIG_WEBROOT . "view.php"
			. "?continuity=" . rawurlencode($this->name);
		return $ret;
	}
}
class year {
	public $year;
	public $topics;
	public $url;


	// Fills in a post's information from a SQL row from `Continuities`
	public function __construct($row, $year) {
		$this->name = $row['Name'];
		$this->description = $row['Description'];
		$this->postcount = $row['Post Count'];
		$this->url = $this->resolve();
	}

	/* Resolve the continuity to a URL */
	function resolve() {
		if (CONFIG_CLEAN_URL)
			$ret =  CONFIG_WEBROOT . "view/"
			. urlencode($this->name);
		else
			$ret = CONFIG_WEBROOT . "view.php"
			. "?continuity=" . rawurlencode($this->name);
		return $ret;
	}
}
/*class reply {
	public $id;
	public $continuity;
	public $topic;
	public $content;
	public $date;
	public $year;
	public $humandate;
	public $url;

	// Fills in a post's information from a SQL row from `Posts`
	public function __construct($row = null) {
		if (!$row) return;
		$time = strtotime($row['Created']);
		$this->id = $row['Id'];
		$this->continuity = $row['Continuity'];
		$this->topic = $row['Topic'];
		$this->content = $row['Content'];
		$this->date = gmdate(DATE_RSS, $time);
		$this->humandate = gmdate('M d Y T', $time);
		$this->year = $row['Year'];
		$this->url = $this->resolve();
	}
	function resolve() {
		if (CONFIG_CLEAN_URL)
			$ret = CONFIG_WEBROOT . "view/"
			. urlencode($this->continuity) . "/"
			. urlencode($this->year) . "/"
			. urlencode($this->topic);
		else
			$ret =  CONFIG_WEBROOT . "view.php"
			. "?continuity=" . rawurlencode($this->continuity)
			. "&year=" . rawurlencode($this->year)
			. "&topic=" . rawurlencode($this->topic);
		$ret .= "#" . $this->id;
		return $ret;
	}
	public function toHtml() {
		$bbparser = $GLOBALS['RM']->getbbparser();
		$bbparser->parse(htmlentities($this->content));
		return $bbparser->getAsHtml();
	}
}*/
/* class topic {
	public $id;
	public $continuity;
	public $content;
	public $date;
	public $year;
	public $humandate;
	public $url;

	// Fills in a post's information from a SQL row from `Posts`
	public function __construct($row = null) {
		if (!$row) return;
		$time = strtotime($row['Created']);
		$this->id = $row['Id'];
		$this->continuity = $row['Continuity'];
		$this->content = $row['Content'];
		$this->date = gmdate(DATE_RSS, $time);
		$this->humandate = gmdate('M d Y T', $time);
		$this->year = $row['Year'];
		$this->url = $this->resolve();
	}
	function resolve() {
		if (CONFIG_CLEAN_URL)
			$ret = CONFIG_WEBROOT . "view/"
			. urlencode($this->continuity) . "/"
			. urlencode($this->year) . "/"
			. urlencode($this->topic);
		else
			$ret =  CONFIG_WEBROOT . "view.php"
			. "?continuity=" . rawurlencode($this->continuity)
			. "&year=" . rawurlencode($this->year)
			. "&topic=" . rawurlencode($this->topic);
		return $ret;
	}
	public function toHtml() {
		$bbparser = $GLOBALS['RM']->getbbparser();
		$bbparser->parse(htmlentities($this->content));
		return $bbparser->getAsHtml();
	}
}*/
/*
 * Generate an interesting title for a piece of text
*/
function titleize($text, $maxlen = 40)
{
	$text = bbbbbbbstrip($text);
	if (strlen($text) < $maxlen) return $text;
	$snippet = substr($text, 0, $maxlen);
	// LOOK TO THE SKY
	$bestbreak = lastIndexOf($snippet, " ");
	if (($b = lastIndexOf($snippet, ",")) > $bestbreak)
		$bestbreak = $b;
	if (($b = lastIndexOf($snippet, "\n")) > $bestbreak)
		$bestbreak = $b;
	return substr($snippet, 0, $bestbreak);
}
/*
 * Creates the post on a given (continuity, topic)
*/
function create_post($continuity, $topic, $auth, $content)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$continuity = mysqli_real_escape_string($dbh, $continuity);
	$topic = mysqli_real_escape_string($dbh, $topic);
	$auth = mysqli_real_escape_string($dbh, $auth);
	$content = mysqli_real_escape_string($dbh, $content);

	mysqli_query("BEGIN TRANSACTION");
		// Insert post
		$query = "INSERT INTO `Posts` (`Id`, `Continuity`, `Topic`, `Auth`, `Content`) SELECT"
		. " `Post Count`+1 AS `Id`"
		. ", '$continuity' AS `Continuity`"
		. ", $topic AS `Topic`"
		. ", '$auth' AS `Auth`"
		. ", '$content' AS `Content`"
		. " FROM `Continuities` WHERE Name='$continuity'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog($err);
			return false;
		}
		$query = "SELECT `Post Count` FROM `Continuities`"
		. " WHERE `Name`='$continuity'";
		if (!($result = mysqli_query($dbh, $query))) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog("$err while fetching inserted row information");
			return false;
		}
		$id = mysqli_fetch_assoc($result)['Post Count'] + 1;
		// Update postcount
		$query = "UPDATE `Continuities` SET `Post Count`=`Post Count`+1"
		. " WHERE `Name`='$continuity'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			ralog($err);
			mysqli_query("ROLLBACK");
			return false;
		}
		$query = "SELECT `Id`, `Continuity`, `Topic`, `Content`"
		. ", `Created` AS `Date`, `Auth` FROM `Posts` WHERE `Id`=$id"
		. " AND `Continuity`='$continuity'";
		if (!($result = mysqli_query($dbh, $query))) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog($err);
			return false;
		}
		$row = mysqli_fetch_assoc($result);
		$post = new post($row);
	mysqli_query("COMMIT");
	ralog("Created Post");
	return $post;
}
/*
 * Creates the post on a given (continuity, topic)
*/
function create_topic($continuity, $auth, $content)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$continuity = mysqli_real_escape_string($dbh, $continuity);
	$auth = mysqli_real_escape_string($dbh, $auth);
	$content = mysqli_real_escape_string($dbh, $content);

	mysqli_query("BEGIN TRANSACTION");
		// Insert post
		$query = "INSERT INTO `Posts` (`Id`, `Continuity`, `Topic`, `Auth`, `Content`) SELECT"
		. " `Post Count`+1 AS `Id`"
		. ", '$continuity' AS `Continuity`"
		. ", `Post Count`+1 AS `Topic`"
		. ", '$auth' AS `Auth`"
		. ", '$content' AS `Content`"
		. " FROM `Continuities` WHERE Name='$continuity'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog("$err while creating topic");
			return false;
		}
		$query = "SELECT `Post Count` FROM `Continuities`"
		. " WHERE `Name`='$continuity'";
		if (!($result = mysqli_query($dbh, $query))) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog("$err while fetching inserted row information");
			return false;
		}
		$id = mysqli_fetch_assoc($result)['Post Count'] + 1;
		// Update postcount
		$query = "UPDATE `Continuities` SET `Post Count`=`Post Count`+1"
		. " WHERE `Name`='$continuity'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			ralog("$err while updating post count after creating a topic");
			mysqli_query("ROLLBACK");
			return false;
		}
		$query = "SELECT `Id`, `Continuity`, `Topic`, `Content`"
		. ", `Created` AS `Date`, `Auth` FROM `Posts` WHERE `Id`=$id"
		. " AND `Continuity`='$continuity'";
		if (!($result = mysqli_query($dbh, $query))) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog("$err while fetching inserted row information");
			return false;
		}
		$row = mysqli_fetch_assoc($result);
		$topic = new post($row);
	mysqli_query("COMMIT");
	ralog("Created topic");
	return $topic;
}
// FUNCTIONS WHICH NEED TO BE PUT SOMEWHERE ELSE
/*
 * Like strpos but does not loop over the
 * entire string when given an offset
*/
function indexOf($string, $substring, $offset = 0)
{
	$stringlen = strlen($string);
	$sublen = strlen($substring);
	if (!$stringlen) return -1;
	for ($i = $offset, $j = 0; $i < $stringlen; $i++) {
		if ($string{$i} == $substring{$j}) {
			if (!(++$j - $sublen))
			return $i - $sublen + 1;
		} else $j = 0;
	}
	return -1;
}
function lastIndexOf($string, $substring, $offset = 0)
{
	$stringlen = strlen($string);
	$sublen = strlen($substring);
	for ($i = $stringlen - 1, $j = $sublen; $i + 1 - $offset; $i--) {
		if ($string{$i} == $substring{$j - 1}) {
			if (!--$j) return $i;
		}
		else $j = $sublen;
	}
	return $i - $offset;
}

?>
