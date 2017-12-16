<?php
include 'includes/config.php';
include 'includes/courier.php';
include 'includes/posting.php';

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

	// Get a key to attach to the message queue
	$queue = msg_get_queue(CONFIG_RAL_KEY);
	if (!$queue) {
	}

	if (isset($timeline, $topic)) {
		while (!sleep(1)) {
		print json_encode([
			'id' => $i++,
			'modified' => '2017-12-07 01:13:24',
			'content' => "Hello from $timeline topic #$topic【 =◈︿◈= 】"
		]);
			flush();
		}
	}
	else if (isset($timeline)) {
//		while(msg_receive($queue, 0, $msgtype, 1024, $topic
//		, false, MSG_NOERROR)) {
		while (!sleep(1)) {
			print json_encode([
				'id' => $i++,
				'modified' => '2017-12-07 01:13:24',
				'content' => "Hello from $timeline 【 =◈︿◈= 】"
			]);
			flush();
		}
	}
}
// Real-time posting
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
