<?php
		$content = $post['content'];
		$time = date('M d Y', strtotime($post['date']));
		$id = $post['id'];
		print
<<<HTML
<article data-post=$id id=$id>

HTML;
	// Allow us to click on topic info to expand the topic
	if (!isset($topic)) {
		if (!CONFIG_CLEAN_URL)
			$q['topic'] = $id;
		$p = http_build_query($q);
			if (CONFIG_CLEAN_URL && empty($p))
			$a = CONFIG_WEBROOT . "max/$timeline/$id";
		else if (CONFIG_CLEAN_URL)
			$a = CONFIG_WEBROOT . "max/$timeline/$id?$p";
		else
			$a = "?$p";
		print
<<<HTML
		<a href="$a" class=info>

HTML;
	} else {
		print
<<<HTML
		<span class=info>

HTML;
	}
	print
<<<HTML
		<span class=id>[$id]</span>
		<time>$time</time>
HTML;
	if (!isset($topic))
		print
<<<HTML
	</a>
HTML;
	else
		print
<<<HTML
	</span>
HTML;
	print
<<<HTML
	<span class=content>$content</span>
</article>

HTML;
?>
