<?php
include 'includes/config.php';
include 'includes/fetch.php';
include 'includes/post.php';

// Initial fetch for reading
if (isset($_GET['fetch'])) {
	$timeline = $_GET['timeline'];
	$topic = $_GET['topic'];

	// Fetch posts
	if (isset($timeline, $topic)) {
		$posts = fetch_posts($timeline, $topic);
		print json_encode($posts);
	}
	// Fetch topics
	else if (isset($timeline)) {
		$topics = fetch_topics($timeline);
		print json_encode($topics);
	}
	// Fetch timelines
	else {
		$timelines = fetch_timelines();
		print json_encode($timelines);
	}
}
// Real-time updates
if (isset($_GET['subscribe'])) {
	$timeline = $_GET['timeline'];
	$topic = $_GET['topic'];

	$c_id = create_listener();

	do {
		$post = fetch_message($c_id);
		if (isset($timeline)
		&& $post['body']['timeline'] != $timeline)
			$relevant = False;
		elseif (isset($topic)
		&& $post['body']['topic'] != $topic)
			$relevant = False;
		else
			$relevant = True;
		if ($relevant)
			print json_encode($post);
	} while (!$relevant && !connection_aborted());

	destroy_listener($c_id);
}
// Posting
if (isset($_GET['post'])) {
	$timeline = $_GET['timeline'];
	$topic = $_GET['topic'];
	$auth = $_COOKIE['auth'];
	$content = $_POST['content'];

	// Create a topic
	if (!isset($topic)) {
		$topic = create_topic($timeline, $auth, $content);
	}
	else {
		$post = create_post($timeline, $topic, $auth, $content);
	}
}
?>
