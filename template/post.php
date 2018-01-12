<?php
	if (!isset($post)) {
		print "Improper template usage!";
		die;
	}

	$content = $post['content'];
	$timeline = $post['timeline'];
	$time = date('M d Y', strtotime($post['date']));
	$posttopic = $post['topic'];
	$id = $post['id'];
?>

<article data-post=<?php print $id?> id=<?php print $id?>>
<?php
	// Allow us to click on topic info to expand the topic
	if ($linkify) {
		if (!CONFIG_CLEAN_URL)
			$q['topic'] = $id;
		$p = http_build_query($q);
			if (CONFIG_CLEAN_URL && empty($p))
			$a = CONFIG_WEBROOT . "max/$timeline/$posttopic";
		else if (CONFIG_CLEAN_URL)
			$a = CONFIG_WEBROOT . "max/$timeline/$posttopic?$p";
		else
			$a = "?$p";
		$open = "<a href='$a' class=info>";
		$close = "</a>";
	} else {
		$open = "<span class=info>";
		$close = "</span>";
	}
	print
<<<HTML
	{$open}
	<span class=id>[$timeline/$id]</span>
	<time>$time</time>
	{$close}
	<span class=content>$content</span>
HTML;
?>
</article>
