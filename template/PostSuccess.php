<!DOCTYPE HTML>
<HTML>
<head>
	<title>Post Success</title>
	<link rel=stylesheet href="<?php print CONFIG_WEBROOT; ?>css/Result.css">
</head>
<body>
<div id=message>
	<h1>Post Success!</h1>
	<span>Page redirects <a href="<?php print $page?>">here</a>
	in <span id=countdown><?php print $until?></span>...</span>
</div>
<script src="<?php print CONFIG_WEBROOT; ?>js/countdown.js"></script>
</body>
</HTML>
