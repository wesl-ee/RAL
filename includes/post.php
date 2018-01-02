<?php
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
				$url = htmlspecialchars($param);
				// Assume http: protocol if none other is given
				if (indexOf($url, ':') < 0)
					$url = 'http:' . $url;
				$open = "<a href='$url'>";
				$close = "</a>";
				break;
			case 'code':
				$open = "<pre>";
				$close = "</pre>";
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
 * Creates the post on a given (timeline, topic)
*/
function create_post($timeline, $topic, $auth, $content)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$topic = mysqli_real_escape_string($dbh, $topic);
	$auth = mysqli_real_escape_string($dbh, $auth);
	$content = mysqli_real_escape_string($dbh, $content);

	mysqli_query("BEGIN TRANSACTION");
		// Insert post
		$query = "INSERT INTO `Posts` (`Id`, `Timeline`, `Topic`, `Auth`, `Content`) SELECT"
		. " `Post Count` AS `Id`"
		. ", '$timeline' AS `Timeline`"
		. ", $topic AS `Topic`"
		. ", '$auth' AS `Auth`"
		. ", '$content' AS `Content`"
		. " FROM `Timelines` WHERE Name='$timeline'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog($err);
			return false;
		}
		$query = "SELECT `Post Count` FROM `Timelines`"
		. " WHERE `Name`='$timeline'";
		if (!($result = mysqli_query($dbh, $query))) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog("$err while fetching inserted row information");
			return false;
		}
		$id = mysqli_fetch_assoc($result)['Post Count'];
		// Update postcount
		$query = "UPDATE `Timelines` SET `Post Count`=`Post Count`+1"
		. " WHERE `Name`='$timeline'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			ralog($err);
			mysqli_query("ROLLBACK");
			return false;
		}
		$query = "SELECT `Id`, `Timeline`, `Topic`, `Content`"
		. ", `Created`, `Auth` FROM `Posts` WHERE `Id`=$id";
		if (!($result = mysqli_query($dbh, $query))) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog($err);
			return false;
		}
		$row = mysqli_fetch_assoc($result);
		$post = [
			'id' => $row['Id'],
			'timeline' => $row['Timeline'],
			'topic' => $row['Topic'],
			'content' => nl2br(bbbbbbb($row['Content'])),
			'date' => $row['Created'],
			'auth' => $row['Auth'],
		];
	mysqli_query("COMMIT");
	ralog("Created Post");
	return $post;
}
/*
 * Creates the post on a given (timeline, topic)
*/
function create_topic($timeline, $auth, $content)
{
	$dbh = mysqli_connect(CONFIG_RAL_SERVER,
		CONFIG_RAL_USERNAME,
		CONFIG_RAL_PASSWORD,
		CONFIG_RAL_DATABASE);
	mysqli_set_charset($dbh, 'utf8');
	$timeline = mysqli_real_escape_string($dbh, $timeline);
	$auth = mysqli_real_escape_string($dbh, $auth);
	$content = mysqli_real_escape_string($dbh, $content);

	mysqli_query("BEGIN TRANSACTION");
		// Insert post
		$query = "INSERT INTO `Posts` (`Id`, `Timeline`, `Topic`, `Auth`, `Content`) SELECT"
		. " `Post Count` AS `Id`"
		. ", '$timeline' AS `Timeline`"
		. ", `Post Count` AS `Topic`"
		. ", '$auth' AS `Auth`"
		. ", '$content' AS `Content`"
		. " FROM `Timelines` WHERE Name='$timeline'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog("$err while creating topic");
			return false;
		}
		$query = "SELECT `Post Count` FROM `Timelines`"
		. " WHERE `Name`='$timeline'";
		if (!($result = mysqli_query($dbh, $query))) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog("$err while fetching inserted row information");
			return false;
		}
		$id = mysqli_fetch_assoc($result)['Post Count'];
		// Update postcount
		$query = "UPDATE `Timelines` SET `Post Count`=`Post Count`+1"
		. " WHERE `Name`='$timeline'";
		if (!mysqli_query($dbh, $query)) {
			$err = mysqli_error($dbh);
			ralog("$err while updating post count after creating a topic");
			mysqli_query("ROLLBACK");
			return false;
		}
		$query = "SELECT `Id`, `Timeline`, `Topic`, `Content`"
		. ", `Created`, `Auth` FROM `Posts` WHERE `Id`=$id";
		if (!($result = mysqli_query($dbh, $query))) {
			$err = mysqli_error($dbh);
			mysqli_query("ROLLBACK");
			ralog("$err while fetching inserted row information");
			return false;
		}
		$row = mysqli_fetch_assoc($result);
		$topic = [
			'id' => $row['Id'],
			'timeline' => $row['Timeline'],
			'topic' => $row['Topic'],
			'content' => nl2br(bbbbbbb($row['Content'])),
			'date' => $row['Created'],
			'auth' => $row['Auth'],
		];
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
		print "Initializing an empty client array\n";
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
	$succ = 0; $fail = 0;
	foreach ($clients as $c_id => $one) {
		// Get client info
		$client_info = shm_get_var($shm, $c_id);
		if ($client_info === False) {
			$fail++;
		}
		elseif (msg_send($queue, $c_id, $msg, True, False))
			$succ++;
		else {
			destroy_listener($c_id);
			$fail++;
		}
	}
	$log = "Broadcast message to $succ clients";
	if ($fail > 0) $log .= " ($fail failures)";
	ralog($log);
//	print "$log\n";
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
	// Ping all clients who have timed out
	sem_acquire($sem);
	$clients = shm_get_var($shm, CONFIG_RAL_SHMCLIENTLIST);
	$now = time();
	$ping = ['type' => 'PING'];
	foreach ($clients as $c_id => $one) {
		$client_info = shm_get_var($shm, $c_id);
		if ($now - $client_info['last_seen'] > CONFIG_CLIENT_TIMEOUT) {
			msg_send($queue, $c_id, $ping, TRUE, False);
		}
	}
	sem_release($sem);

	$client_info = [
		'last_seen' => time()
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

	// Free resources dedicated to shared memory
	shm_detach($shm);

	return $c_id;
}
function destroy_listener($c_id)
{
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
