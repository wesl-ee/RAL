<?php
include 'includes/config.php';
include 'includes/courier.php';

if (isset($_GET['fetch'])) {
	$timeline = $_GET['timeline'];

	$topic = $_GET['topic'];
	if (isset($timeline, $topic)) {
	}
	else if (isset($timeline)) {
		$topics = fetch_topics($timeline);
		print json_encode($topics);
	}
}
?>
