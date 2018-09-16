<!DOCTYPE HTML>
<HTML>
<head>
	<title>Post Success</title>
	<link rel=stylesheet href="<?php print CONFIG_WEBROOT; ?>css/Result.css">
</head>
<body>
<div id=video>
	<video autoplay mute loop><?php
	$video = htmlentities(CONFIG_RESULT_VIDEOS['Success'], ENT_QUOTES);
	print "<source src=\"$video\">";
	?></video>
</div>
<div id=message>
	<h1>Post Success!</h1>
	<span>Page redirects in a few seconds...</span>
</div>
</body>
</HTML>
