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
	}
	else {
		$submittext = 'Create Topic';
		$placeholder = 'Create your topic';
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
		<textarea autofocus rows=5
		maxlength=$maxlen
		placeholder="$placeholder"
		name=content></textarea>
	<div class=robocheck>
		<img src="$robosrc">
		<input name=robocheckid type=hidden value=$robocode>
		<input name=robocheckanswer
		placeholder="Verify Humanity"
		autocomplete=off>
	</div><div class=buttons>
		<a href="$target" class="cancel hoverbox">Cancel</a>
		<input value="$submittext" class=hoverbox type=submit>
	</div>
</form>

HTML;
?>
