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
	$init = isset($_GET['init']);

	header('X-Accel-Buffering: no');

	// Listening to a topic
	if (isset($timeline, $topic))
		$tags = [
		'timeline' => $timeline,
		'topic' => (int)$topic
		];
	// Listening to a timeline
	elseif (isset($timeline))
		$tags = [
		'timeline' => $timeline,
		];
	$c_id = create_listener($tags);
	do {
		$post = fetch_message($c_id);
		var_dump($post);
		flush();
	} while (!connection_aborted());

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
