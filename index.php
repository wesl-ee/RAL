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
<body class='preload'>
<div id=welcome>
	<h1>Welcome to<br/><span id=xxx>RAL</span></h1>
	<div class=choicebox>
		<a onClick='window.destruction.welcome(
			document.getElementById("welcome")
		);'>Enter</a>
	</div>
</div>
<div id=sakura>
<!--	<video autoplay loop>
		<source src='res/splash.webm'>
	</video>
	<img src='res/fallback.gif'>-->
</div>
</body>
<script src='js/esthetic.js'></script>
<script src='js/transitions.js'></script>
<script src='js/remote.js'></script>
<script>
	flashmessages(document.getElementById('xxx'));
	setTimeout(function(){
		document.body.className='';
	},500);
</script>
</HTML>
