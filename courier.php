<?php
include 'includes/config.php';
include 'includes/courier.php';

if (isset($_GET['fetch'])) {
	$timeline = $_GET['timeline'];
	$topic = $_GET['topic'];

	if (isset($timeline, $topic)) {
		print json_encode([[
			'id' => 0,
			'modified' => '2017-12-07 01:13:24',
			'content' => "Hello from $timeline topic $topic 【 =◈︿◈= 】"
		]]);
	}
	else if (isset($timeline)) {
		$topics = fetch_topics($timeline);
		print json_encode($topics);
	}
}
if (isset($_GET['subscribe'])) {
	$timeline = $_GET['timeline'];
	$topic = $_GET['topic'];
	$init = isset($_GET['init']);

	if (isset($timeline, $topic)) {
		while (!sleep(1)) {
		print json_encode([
			'id' => 0,
			'modified' => '2017-12-07 01:13:24',
			'content' => "Hello from $timeline topic #$topic【 =◈︿◈= 】"
		]);
			flush();
		}
	}
	else if (isset($timeline)) {
		while (!sleep(1)) {
/*		print json_encode([
			'id' => 0,
			'modified' => '2017-12-07 01:13:24',
			'content' => "Hello from $timeline 【 =◈︿◈= 】"
		]);*/
			flush();
		}
	}
}
?>
