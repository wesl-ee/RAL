<?php
	if (!isset($continuity)) {
		print "Improper template usage!";
		die;
	}
	$q = $_GET;
	unset($q['postmode']);
	$q = http_build_query($q);
	if (CONFIG_CLEAN_URL && isset($topic))
		$target = CONFIG_WEBROOT
		. "max/$continuity/$topic";
	else if (CONFIG_CLEAN_URL)
		$target = CONFIG_WEBROOT
		. "max/$continuity";
	else
		$target = CONFIG_WEBROOT
		. "max.php";
	if (isset($topic)) {
		$submittext = 'Post';
		$placeholder = 'Enter your text';
		$heading = "Reply<br/>[ $continuity / $topic ]";
	}
	else {
		$submittext = 'Create Topic';
		$placeholder = 'Create your topic';
		$heading = "New Topic<br/>[ $continuity ]";
	}

	if (!empty($q)) $target = "$target?$q";

	$robocheck = gen_robocheck();
	$robosrc = $robocheck['src'];
	$robocode = $robocheck['id'];

	$maxlen = CONFIG_RAL_POSTMAXLEN;
	$posticon = CONFIG_WEBROOT . "res/post.gif";
	$cancelicon = CONFIG_WEBROOT . "res/stop.gif";

	print
<<<HTML

<form class=reply method=POST action="$target">
	<header>$heading</header>
	<div class=textarea>
		<a class=toggle-preview>
		[ Preview Formatting ]
		</a>
		<textarea autofocus rows=5
		maxlength=$maxlen
		placeholder="$placeholder"
		name=content></textarea>
		<article class=preview></article>
	</div>
	<div class=robocheck>
		<img src="$robosrc">
		<input name=robocheckid type=hidden value=$robocode>
		<input name=robocheckanswer
		placeholder="Verify Humanity"
		autocomplete=off>
	</div><div class=buttons>
		<a href="$target" class="cancel hoverbox">Cancel</a>
		<button class=hoverbox type=submit>$submittext</button>
	</div>
</form>

<!-- Hook up the "Preview" button -->
<script>
	var replies = document.getElementsByClassName('reply');
	var reply = replies[replies.length - 1];
	var p = reply.getElementsByClassName('toggle-preview')[0];

	p.style.display = 'inline';
	p.addEventListener('click', handletogglepreview);
</script>

HTML;
