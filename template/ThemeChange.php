<!DOCTYPE HTML>
<HTML>
<head>
	<title>Post Success</title>
	<link rel=stylesheet href="<?php print CONFIG_WEBROOT; ?>css/Result.css">
</head>
<body>
<div id=video>
	<video autoplay mute><?php
	$video = htmlentities(CONFIG_RESULT_VIDEOS['Config'], ENT_QUOTES);
	print "<source src=\"$video\">";
	?></video>
</div>
<div id=message>
	<h1>Configuration Updated</h1>
	<span>Page redirects in a few seconds...</span>
</div>
</body>
</HTML>
