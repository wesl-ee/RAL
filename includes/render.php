<?php
function rendercontinuitynav($page)
{
	print
<<<HTML
<div class=collection>

HTML;
	/* Draw the timelines panel (left sidebar) */
	$per_page = CONFIG_TIMELINES_PER_PAGE;
	$timelines = fetch_timelines();
	for ($i = 0; $i < count($timelines); $i++) {
		$name = $timelines[$i]['name'];
		$desc = $timelines[$i]['description'];
		if (CONFIG_CLEAN_URL)
			$a = CONFIG_WEBROOT . "max/$name?p=$page";
		else
			$a = "max.php?timeline=$name&p=$page";
		// Put all timelines in the DOM (but only
		// display some) (for JS)
		if ($i < $page * $per_page
		|| $i >= ($page + 1) * $per_page)
			print
<<<HTML
	<a class=hoverbox href="$a"
	style="visibility: hidden; display:none">$name</a>

HTML;
		else print
<<<HTML
	<a class=hoverbox href="$a">$name</a>

HTML;
	}
	print
<<<HTML
</div>
<nav class=arrows>

HTML;
	// Left navigation arrow
	if (!$page) {
		print
<<<HTML
	<a class=leftnav style="visibility:hidden">◀</a>

HTML;
	} else {
		$nextpage = $page - 1;
		// Preserve $_GET across timelines navigation
		$q = $_GET;
		$q['p'] = $nextpage;
		$q = http_build_query($q);
		print
<<<HTML
	<a class=leftnav href="?$q">◀</a>

HTML;
	}

	// Right navigation arrow
	if ($page * $per_page < floor(count($timelines) / $per_page)) {
		$nextpage = $page + 1;
		// Preserve $_GET across timelines navigation
		$q = $_GET;
		$q['p'] = $nextpage;
		$q = http_build_query($q);
		print
<<<HTML
	<a class=rightnav href='?$q'>▶</a>

HTML;
	} else {
		print
<<<HTML
	<a class=rightnav style="visibility:hidden">▶</a>

HTML;
	}
	print
<<<HTML
</nav>

HTML;
}
function renderbreadcrumb($timeline, $topic)
{
	print
<<<HTML
<ol vocab='http://schema.org/' typeof=BreadcrumbList
class=breadcrumb>
HTML;
	$a = CONFIG_WEBROOT;
	print
<<<HTML
	<li property=itemListElement typeof=ListItem>
		<a href='$a' property=item typeof=WebPage>
		<span property=name>RAL</span></a>
		<meta property=position content=1 />
	</li>

HTML;
	if (isset($timeline)) {
		if (CONFIG_CLEAN_URL)
			$a .= "max/$timeline";
		else
			$a .= "max.php?timeline=$timeline";
		print
<<<HTML
	›<li property=itemListElement typeof=ListItem>
		<a href='$a' property=item typeof=WebPage>
		<span property=name>$timeline</span></a>
		<meta property=position content=2 />
	</li>

HTML;
	}
	if (isset($topic)) {
		if (CONFIG_CLEAN_URL)
			$a .= "/$topic";
		else
			$a .= "&topic=$topic";
		print
<<<HTML
	›<li property=itemListElement typeof=ListItem>
		<a href='$a' property=item typeof=WebPage>
		<span property=name>$topic</span></a>
		<meta property=position content=3 />
	</li>

HTML;
	}
	print
<<<HTML
</ol>

HTML;
}
function renderposts($timeline, $topic = null)
{
	if (is_null($topic))
		$posts = fetch_topics($timeline);
	else
		$posts = fetch_posts($timeline, $topic);

	foreach ($posts as $post) {
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
	}
}
function renderrobocheck($target)
{
	$robocheck = gen_robocheck();
	$robosrc = $robocheck['src'];
	$robocode = $robocheck['id'];
	print
<<<HTML
<form class=reply method=POST action="$target">
		<textarea rows=5
		maxlength=CONFIG_RAL_POSTMAXLEN
		name=content></textarea>
	<div class=buttons>
		<img src="$robosrc">
		<input name=robocheckid type=hidden value=$robocode>
		<input name=robocheckanswer placeholder="Type the above text" autocomplete=off>
		<input value=Post class=hoverbox type=submit>
		<a href="$target" class="cancel hoverbox">Cancel</a>
		</div>
</form>

HTML;
}
function renderpostbox($timeline, $topic)
{
	$q = $_GET;
	unset($q['postmode']);
	$q = http_build_query($q);
	if (CONFIG_CLEAN_URL && isset($topic))
		$target = CONFIG_WEBROOT
		. "max/$timeline/$topic";
	else if (CONFIG_CLEAN_URL)
		$target = CONFIG_WEBROOT
		. "max/$timeline";
	else
		$target = CONFIG_WEBROOT
		. "max.php";
	if (empty($q)) $href = $target;
	else $href = "$target?$q";
	renderrobocheck($href);
}
?>
