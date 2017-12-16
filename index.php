<?php
/* PREAMBLE */
// Generate an ID for posting authentication
if (!isset($_COOKIE['auth'])) {
	setcookie('auth', uniqid());
}
?>

<!DOCTYPE HTML>
<HTML>
<head>
	<link rel=stylesheet href="css/base.css">
	<link rel=stylesheet href="css/20XX.css">
	<meta name=viewport
	content="width=device-width; maximum-scale=1; minimum-scale=1">
	<title>RAL</title>
</head>
<body>
<div id=welcome>
	<h1>Welcome to<br/><span id=xxx>RAL</span></h1>
	<div class=choicebox>
		<a href='/B4U'>Enter</a>
	</div>
</div>
</body>
<script src='js/esthetic.js'></script>
<script src='js/remote.js'></script>
<script>
	flashmessages(document.getElementById('xxx'));
</script>
</HTML>
