<?php
	if (!isset($post)) {
		print "Improper template usage!";
		die;
	}

	$content = $post->content;
	$continuity = $post->continuity;
	$time = date('M d Y', strtotime($post->date));
	$posttopic = $post->topic;
	$id = $post->id;
	$url = $post->url;
?>

<article data-post=<?php print $id?> id=<?php print $id?>>
<?php
	// Allow us to click on topic info to expand the topic
	if ($linkify) {
		$open = "<a href='$url' class=info>";
		$close = "</a>";
	} else {
		$open = "<span class=info>";
		$close = "</span>";
	}
	print
<<<HTML
	{$open}
	<span class=id>[$continuity/$id]</span>
	<time>$time</time>
	{$close}
	<span class=content>$content</span>
HTML;
?>
</article>
