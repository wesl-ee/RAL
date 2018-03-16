<?php if (!isset($post)) {
	print "Improper template usage!";
	die;
} ?>

<article <?php if ($linkify) print "class=linkify "?>
data-post=<?php print $post->id;?>>
<?php
	print
<<<HTML
	<section class=info>

HTML;
	// Allow us to click on topic info to expand the topic
	if ($linkify) print
<<<HTML
	<a class=id href="$post->url">[$post->continuity/$post->id]</a>

HTML;
	else print
<<<HTML
	<span class=id>[$post->continuity/$post->id]</span>

HTML;
	print
<<<HTML
	<time datetime="$post->date">$post->shortdate</time>
	</section><hr />
	<section class=content>{$post->toHtml()}</section>

HTML;
	$root = CONFIG_WEBROOT;
	if ($linkify) print <<<HTML
	<a class=sideclickbox href=$post->url>
		<img src="{$root}res/post.gif">
	</a>

HTML;
?>
</article>
