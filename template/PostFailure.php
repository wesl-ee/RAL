<!DOCTYPE HTML>
<HTML>
<head>
	<title>Post Failure</title>
	<link rel=stylesheet href="<?php print CONFIG_WEBROOT; ?>css/Result.css">
</head>
<body>
<div id=message>
	<h1>Post Failure</h1><?php
	if ($reason) print <<<REASON
	<em>$reason</em><br />

REASON;
?>	<span>Page redirects <a href="<?php print $page?>">here</a>
	in <span id=countdown><?php print $until?></span>...</span>
</div>
<script src="<?php print CONFIG_WEBROOT; ?>js/countdown.js"></script>
</body>
</HTML>
