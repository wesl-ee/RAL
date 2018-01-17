<?php
include '../includes/config.php';
include '../includes/fetch.php';
include '../includes/post.php';

// Initial fetch for reading
if (isset($_GET['fetch'])) {
	$continuity = $_GET['continuity'];
	$topic = $_GET['topic'];

	// Fetch posts
	if (isset($continuity, $topic)) {
		$posts = fetch_posts($continuity, $topic);
		print json_encode($posts);
	}
	// Fetch topics
	else if (isset($continuity)) {
		$topics = fetch_topics($continuity);
		print json_encode($topics);
	}
	// Fetch continuities
	else {
		$continuities = fetch_continuities();
		print json_encode($continuities);
	}
}
// Real-time updates
if (isset($_GET['subscribe'])) {
	$continuity = $_GET['continuity'];
	$topic = $_GET['topic'];

	$c_id = create_listener();
	do {
		$msg = fetch_message($c_id);
		switch($msg['type']) {
		case 'POST':
			$post = $msg['body'];
			if (isset($continuity)
			&& $post->continuity != $continuity)
				$relevant = False;
			elseif (isset($topic)
			&& $post->topic != $topic)
				$relevant = False;
			else
				$relevant = True;
			break;
		case 'PING':
			$relevant = True;
		}
		if ($relevant)
			print json_encode($msg);
	} while (!$relevant && !connection_aborted());

	destroy_listener($c_id);
}
if (isset($_GET['verify'])) {
	$continuity = $_GET['continuity'];
	$topic = $_GET['topic'];

	if (isset($continuity, $topic)) {
		$posts = fetch_post_nums($continuity, $topic);
		print json_encode($posts);
	}
	// Fetch topics
	else if (isset($continuity)) {
		$topics = fetch_topic_nums($continuity);
		print json_encode($topics);
	}
	else {
		$all = fetch_recent_post_nums(10);
		print json_encode($all);
	}
}
// Posting
if (isset($_GET['post'])) {
	$continuity = $_GET['continuity'];
	$topic = $_GET['topic'];
	$auth = $_COOKIE['auth'];
	$content = $_POST['content'];

	// Create a topic
	if (!isset($topic)) {
		$topic = create_topic($continuity, $auth, $content);
	}
	else {
		$post = create_post($continuity, $topic, $auth, $content);
	}
}
?>
