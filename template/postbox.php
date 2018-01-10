<?php
	if (!isset($timeline)) {
		print "Improper template usage!";
		die;
	}
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
	if (!empty($q)) $target = "$target?$q";

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
?>
