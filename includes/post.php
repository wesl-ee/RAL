<?php
class continuity {
	public $name;
	public $description;
	public $url;

	// Fills in a post's information from a SQL row from `Continuities`
	public function __construct($row) {
		$this->name = $row['Name'];
		$this->description = $row['Description'];
		$this->url = $this->resolve();
	}

	/* Resolve the continuity to a URL */
	function resolve() {
		if (CONFIG_CLEAN_URL)
			$ret =  CONFIG_WEBROOT . "max/"
			. urlencode($this->name);
		else
			$ret = CONFIG_WEBROOT . "max.php&continuity="
			. rawurlencode($this->name);
		return $ret;
	}
}
class post {
	public $id;
	public $continuity;
	public $topic;
	public $content;
	public $date;
	public $shortdate;
	public $auth;
	public $url;

	// Fills in a post's information from a SQL row from `Posts`
	public function __construct($row = null) {
		if (!$row) return;
		$time = strtotime($row['Date']);
		$this->id = $row['Id'];
		$this->continuity = $row['Continuity'];
		$this->topic = $row['Topic'];
		$this->content = $row['Content'];
		$this->date = gmdate(DATE_RSS, $time);
		$this->shortdate = gmdate('M d Y T', $time);
		$this->auth = $row['Auth'];
		$this->url = $this->resolve();
	}
	/* Resolve the post to a URL */
	function resolve() {
		if (CONFIG_CLEAN_URL)
			$ret = CONFIG_WEBROOT . "max/"
			. urlencode($this->continuity) . "/"
			. urlencode($this->topic);
		else
			$ret =  CONFIG_WEBROOT
			. "max.php?continuity="
			. rawurlencode($this->continuity)
			. "&topic=" . rawurlencode($this->topic);
		// Hash only for non-topic posts
		if ($this->topic != $this->id) $ret .= "#" . $this->id;
		return $ret;
	}
	/* Prepare a post for HTML output */
	public function toHtml() {
		return nl2br(
		bbbbbbb(
		htmlspecialchars(
		$this->content)));
	}
}

/*
 * Magical BBcode parser
*/
function bbbbbbb($string)
{
	$opened = []; $contents = [];
	$offset = 0;
	while (($a = indexOf($string, "[", $offset)) >= 0
	&& ($b = indexOf($string, "]", $offset)) > $a) {
		// Push the parsed contents to an array
		$contents[] = substr($string, $offset, $a - $offset);
		$tag = substr($string, $a, $b + 1 - $a);
		$contents[] = $tag;

		// Since we finished scanning the part of the string
		// that is behind the last seen bracket, advance
		// the scanning offset
		$offset = $b + 1;

		// Strip brackets
		$inside = substr($tag, 1, strlen($tag) - 2);

		// Is this a closing or opening tag?
		if ($inside{0} == "/") {
			$tag = substr($inside, 1);
			if (!count($opened[$tag]))
				continue;
			$opening_tag = array_pop($opened[$tag]);
			$from = $opening_tag["index"];
			$param = $opening_tag["param"];
			$to = count($contents) - 1;

			switch($tag) {
			case  'b':
				$open = "<strong>";
				$close = "</strong>";
				break;
			case 'i':
				$open = "<em>";
				$close = "</em>";
				break;
			case 'j':
			case 'code':
				$open = "<kbd>";
				$close = "</kbd>";
				break;
			case 'color':
				$color = htmlspecialchars($param);
				$open = "<span style='color:$color'>";
				$close = "</span>";
				break;
			case 'quote':
				$open = "<blockquote>";
				$close = "</blockquote>";
				break;
			case 'url':
				if ($param)
					$url = htmlspecialchars($param);
				else $url = join(array_slice(
					$contents, $from + 1, $to - $from - 1), ''
				);
				// Assume http: protocol if none other is given
				if (indexOf($url, ':') < 0)
					$url = 'http:' . $url;
				$open = "<a rel=nofollow href='$url'>";
				$close = "</a>";
				break;
			default: continue;
			}
			$contents[$from] = $open;
			$contents[$to] = $close;
		} else {
			if (($c = indexOf($inside, "=")) > 0) {
				$tag = substr($inside, 0, $c);
				$param = substr($inside, $c + 1);
			} else {
				$tag = $inside;
				unset($param);
			}
			$opened[$tag][] = [
				"index" => count($contents) - 1,
				"param" => $param
			];
		}
	}
	$contents[] = substr($string, $offset);
	return join($contents);
}
/*
 * Magical BBCode stripping function
*/
function bbbbbbbstrip($string)
{
	$opened = []; $contents = [];
	$offset = 0;
	while (($a = indexOf($string, "[", $offset)) >= 0
	&& ($b = indexOf($string, "]", $offset)) > $a) {
		// Push the parsed contents to an array
		$contents[] = substr($string, $offset, $a - $offset);
		$tag = substr($string, $a, $b + 1 - $a);
		$contents[] = $tag;

		// Since we finished scanning the part of the string
		// that is behind the last seen bracket, advance
		// the scanning offset
		$offset = $b + 1;

		// Strip brackets
		$inside = substr($tag, 1, strlen($tag) - 2);

		// Is this a closing or opening tag?
		if ($inside{0} == "/") {
			$tag = substr($inside, 1);
			if (!count($opened[$tag]))
				continue;
			$opening_tag = array_pop($opened[$tag]);
			$from = $opening_tag["index"];
			$to = count($contents) - 1;

			switch($tag) {
			case  'b':
			case 'i':
			case 'j':
			case 'color':
			case 'quote':
			case 'url':
			case 'code':
				break;
			default: continue;
			}
			unset($contents[$from]);
			unset($contents[$to]);
		} else {
			if (($c = indexOf($inside, "=")) > 0) {
				$tag = substr($inside, 0, $c);
			} else {
				$tag = $inside;
			}
			$opened[$tag][] = [
				"index" => count($contents) - 1,
			];
		}
	}
	$contents[] = substr($string, $offset);
	return join($contents);
}
/*
 * Renders a paragraph of Perl's Plain Old Documentation in HTML
*/
function podparagraph($string)
{
	$hint = $string{0};
	// Command paragraphs begin with an equals sign
	if ($hint == "=") {
		$seperator = indexOf($string, ' ', 1);
		if ($seperator < 0)
			$seperator = strlen($string);
		$identifier = substr($string, 1, $seperator - 1);
		$text = decodepod(substr($string, $seperator + 1));
		switch($identifier) {
		case "head1":
			print "<h1>$text</h1>\n";
			break;
		case "head2":
			print "<h2>$text</h2>\n";
			break;
		case "head3":
			print "<h3>$text</h3>\n";
			break;
		case "head4":
			print "<h4>$text</h4>\n";
			break;
		case "over":
			print "<ol>\n";
			break;
		case "item":
			print "<li>$text</li>\n";
			break;
		case "back":
			print "</ol>\n";
			break;
		}
	}
	// Verbatim paragraphs begin with a space or tabliture
	else if ($hint == " " || $hint == "\t") {
		print "<pre>$string</pre>\n";
	}
	else {
		$string = decodepod($string);
		print "<p>$string</p>\n";
	}
}
function decodepod($string)
{
	$offset = 0; unset($ret);
	while (($a = indexOf($string, "<", $offset)) >= 0
	&& ($b = indexOf($string, ">", $offset)) > $a) {
		$ret .= substr($string, $offset, $a - $offset - 1);
		$tag = $string{$a - 1};
		$text = substr($string, $a + 1, $b - $a - 1);
		$offset = $b + 1;
		switch ($tag) {
		case "I":
		case "F":
			$ret .= "<em>$text</em>";
			break;
		case "C":
			$ret .= "<kbd>$text</kbd>";
			break;
		case "B":
			$ret .= "<strong>$text</strong>";
			break;
		case "L":
			if (($sep = strpos($text, "|")) >= 0) {
				$href = substr($text, $sep + 1);
				$text = substr($text, 0, $sep);
			} else $href = $text;
			$ret .= "<a href='$href'>$text</a>";
			break;
		}
	}
	return $ret . substr($string, $offset);
}
/*
 * Renders a file of Perl's Plain Old Documentation in HTML
*/
function ppppppp($file, $maxlen = 4092)
{
	$fh = fopen($file, 'r');
	while ($line = fgets($fh, $maxlen)) {
		$hint = $line{0};
		if ($hint != "=") continue;
		$seperator = indexOf($line, ' ', 1);
		if ($seperator < 0)
			$seperator = strlen($line);
		$identifier = substr($line, 1, $seperator - 1);
		$text = substr($line, $seperator + 1);
		if ($identifier == "head1"
		|| $identifier == "head2"
		|| $identifier == "head3"
		|| $identifier == "head4")
			$headings[] = [
				'text' => $text,
				'level' => substr($identifier,
				strlen($identifier) - 1)
			];
	}
	$fh = fopen($file, 'r');
	while ($line = fgets($fh, $maxlen)) {
		if ($line == "\n") {
			podparagraph(rtrim($paragraph));
			unset($paragraph);
		} else
			if ($paragraph)
				$paragraph .= ' ' . $line;
			else
				$paragraph = $line;
	}
	podparagraph(rtrim($paragraph));
	fclose($fh);
}
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
// SYSTEM V FUNCTIONS
/*
 * Notifies all listeners of a new post
*/
function notify_listeners($msgtype, $body = '')
{
	$queue = msg_get_queue(CONFIG_RAL_QUEUEKEY);
	$shm = shm_attach(CONFIG_RAL_SHMKEY, 1000000);
	$msg = [
		'type' => $msgtype,
		'body' => $body
	];

	if ($shm === False) {
		print 'Could not connect to the shm segment';
		die;
	} elseif ($queue === False) {
		print 'Could not connect to the msg queue';
		die;
	}

	// Send the message to every listening client
	if (!shm_has_var($shm, CONFIG_RAL_SHMCLIENTLIST)) {
		ralog("Initializing an empty client array");
		$clients = [];
		if (!shm_put_var($shm, CONFIG_RAL_SHMCLIENTLIST, $clients)) {
			// Nuclear option: memory is too full and nobody
			// is dropping voluntarily
			ralog('Shared memory full. . . Clearing memory');
			shm_remove($shm);
			return false;
		}
	} else {
		$clients = shm_get_var($shm, CONFIG_RAL_SHMCLIENTLIST);
	}
	if ($clients === False) {
		print 'Error while acquiring a client list';
		die;
	}
	$succ = 0; $fail = 0; $timeout = 0;
	foreach ($clients as $c_id => $one) {
		// Get client info
		$client_info = shm_get_var($shm, $c_id);
		if ($client_info === False) {
			$fail++;
		}
		elseif (time() - $client_info['last_seen'] > CONFIG_CLIENT_TIMEOUT) {
			destroy_listener($c_id);
			$timeout++;
		} elseif (msg_send($queue, $c_id, $msg, True, False)) {

			
			$succ++;
		} else {
			destroy_listener($c_id);
			$fail++;
		}
	}
	$log = "Broadcast message to $succ clients";
	if ($fail > 0) $log .= " ($fail failures)";
	if ($timeout > 0) $log .= " ($timeout timeouts)";
	ralog($log);
	shm_detach($shm);
}
function create_listener()
{
	$queue = msg_get_queue(CONFIG_RAL_QUEUEKEY);
	$shm = shm_attach(CONFIG_RAL_SHMKEY, 1000000);
	$sem = sem_get(CONFIG_RAL_SEMKEY);

	if (!$shm) {
		print 'Could not connect to the shm segment';
		die;
	} elseif (!$queue) {
		print 'Could not connect to the msg queue';
		die;
	} elseif (!$sem) {
		print 'Could not get the semaphore';
		die;
	}
	// Remove all clients who have timed out
	$now = time();
	$clients = shm_get_var($shm, CONFIG_RAL_SHMCLIENTLIST);
	foreach ($clients as $c_id => $one) {
		$client_info = shm_get_var($shm, $c_id);
		if ($now - $client_info['last_seen'] > CONFIG_CLIENT_TIMEOUT) {
			destroy_listener($c_id);
		}
	}

	$client_info = [
		'last_seen' => $now
	];
	sem_acquire($sem);
	// Acquire a unique Client ID
	do {
		$c_id = rand();
	} while(shm_has_var($shm, $c_id));

	if (!shm_put_var($shm, $c_id, $client_info)) {
		purge_timedout($shm);
	}
	// Insert this client id into the client list (thread-safe)
	if (!shm_has_var($shm, CONFIG_RAL_SHMCLIENTLIST)) {
		$clients = [];
		shm_put_var($shm, CONFIG_RAL_SHMCLIENTLIST, $clients);
	}
	$clients = shm_get_var($shm, CONFIG_RAL_SHMCLIENTLIST);
	$c_index = count($clients);
	$clients[$c_id] = 1;
	shm_put_var($shm, CONFIG_RAL_SHMCLIENTLIST, $clients);
	sem_release($sem);

	return $c_id;
}
function destroy_listener($c_id)
{
	$queue = msg_get_queue(CONFIG_RAL_QUEUEKEY);
	$shm = shm_attach(CONFIG_RAL_SHMKEY, 1000000);
	$sem = sem_get(CONFIG_RAL_SEMKEY);

	if (!$shm) {
		print 'Could not connect to the shm segment';
		die;
	} elseif (!$sem) {
		print 'Could not get the semaphore';
		die;
	}


	// Remove this client id from the client list (thread-safe)
	sem_acquire($sem);
	$clients = shm_get_var($shm, CONFIG_RAL_SHMCLIENTLIST);
	unset($clients[$c_id]);
	shm_put_var($shm, CONFIG_RAL_SHMCLIENTLIST, $clients);

	// Remove this client's particulars too
	shm_remove_var($shm, $c_id);
	sem_release($sem);

	// Free resources dedicated to shared memory
	shm_detach($shm);

	// Eat up all messages that were intended for the user
//	while (msg_receive($queue, $c_id, $msgtype, 1000000,
//	$msg, true, MSG_IPC_NOWAIT) != MSG_ENOMSG);
}
function renew_listener($c_id)
{
	$shm = shm_attach(CONFIG_RAL_SHMKEY, 1000000);
	$sem = sem_get(CONFIG_RAL_SEMKEY);
	$client_info = [
		'last_seen' => time()
	];
	sem_acquire($sem);
	if (!shm_put_var($shm, $c_id, $client_info)) {
		purge_timedout($shm);
	}
	sem_release($sem);

	// Free resources dedicated to shared memory
	shm_detach($shm);
}
function purge_timedout($shm)
{
	$sem = sem_get(CONFIG_RAL_SEMKEY);
	$now = time();
	$i = 0;

	sem_acquire($sem);
	$clients = shm_get_var($shm, CONFIG_RAL_SHMCLIENTLIST);
	foreach ($clients as $c_id => $one) {
		$client_info = shm_get_var($shm, $c_id);
		if ($now - $client_info['last_seen'] > CONFIG_CLIENT_TIMEOUT) {
			unset($clients[$c_id]);
			shm_remove_var($shm, $c_id);
			$i++;
		}
	}
	ralog("Purged $i timed out clients (memory full)");
	shm_put_var($shm, CONFIG_RAL_SHMCLIENTLIST, $clients);
	sem_release($sem);
}
function fetch_message($c_id)
{
	$queue = msg_get_queue(CONFIG_RAL_QUEUEKEY);
	if (msg_receive($queue, $c_id, $msgtype, 1000000, $msg)) {
		return $msg;
	}
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
