<?php
include '../includes/main.php';
include '../includes/fetch.php';
include '../includes/post.php';

// Initial fetch for reading
if (isset($_GET['fetch'])) {
	$continuity = $_GET['continuity'];
	$topic = $_GET['topic'];
	$format = $_GET['format'];
	$linkify = isset($_GET['linkify']);

	// Fetch posts
	if (isset($continuity, $topic)) {
		$posts = fetch_posts($continuity, $topic);
		switch(strtoupper($format)) {
			case 'HTML':
				foreach ($posts as $post)
					include '../template/post.php';
				break;
			default:
				print json_encode($posts);
				break;
		}
	}
	// Fetch topics
	else if (isset($continuity)) {
		$topics = fetch_topics($continuity);
		switch(strtoupper($format)) {
			case 'HTML':
				foreach ($topics as $post)
					include '../template/post.php';
				break;
			default:
				print json_encode($topics);
				break;
		}
	}
	// Fetch continuities
	else {
		$continuities = fetch_continuities();
		print json_encode($continuities);
	}
}
if (isset($_GET['verify'])) {
	$continuity = $_GET['continuity'];
	$topic = $_GET['topic'];
	$mostpost = $_GET['mostpost'];

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
		$all = fetch_recent_post_nums($mostpost);
		print json_encode($all);
	}
}
// Posting
/*if (isset($_GET['post'])) {
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
}*/
// Preview BBCode
if (isset($_GET['preview'])) {
	$text = $_POST['text'];
	$post = new Post();
	$post->content = $text;
	print $post->toHtml();
}
?>
