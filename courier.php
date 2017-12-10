<?php
include 'includes/config.php';
include 'includes/courier.php';

// Initial fetch for reading
if (isset($_GET['fetch'])) {
	$timeline = $_GET['timeline'];
	$topic = $_GET['topic'];

	if (isset($timeline, $topic)) {
		$posts = fetch_posts($timeline, $topic);
		print json_encode($posts);
	}
	else if (isset($timeline)) {
		$topics = fetch_topics($timeline);
		print json_encode($topics);
	}
}
// Real-time updates
if (isset($_GET['subscribe'])) {
	$timeline = $_GET['timeline'];
	$topic = $_GET['topic'];
	$init = isset($_GET['init']);

	if (isset($timeline, $topic)) {
		$i = 2;
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
		$i = 2;
		while (!sleep(5)) {
		print json_encode([
			'id' => $i++,
			'modified' => '2017-12-07 01:13:24',
			'content' => "Hello from $timeline 【 =◈︿◈= 】"
		]);
			flush();
		}
	}
}
?>
