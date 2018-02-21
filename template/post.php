<?php if (!isset($post)) {
	print "Improper template usage!";
	die;
} ?>

<article data-post=<?php print $post->id;?> id=<?php print $post->id;?>>
<?php
	// Allow us to click on topic info to expand the topic
	if ($linkify) {
		$open = "<a href='$post->url' class=info>";
		$close = "</a>";
	} else {
		$open = "<span class=info>";
		$close = "</span>";
	}
	print
<<<HTML
	{$open}
	<span class=id>[$post->continuity/$post->id]</span>
	<time datetime="$post->date">$post->shortdate</time>
	{$close}
	<span class=content>{$post->toHtml()}</span>
HTML;
?>
</article>
