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
	<meta name=viewport content="width=device-width, maximum-scale=1, minimum-scale=1">
	<link rel=stylesheet href="css/base.css">
	<link rel=stylesheet href="css/20XX.css">
	<title>RAL</title>
</head>
<body>
<div id=welcome>
	<h1>Welcome to<br/><span id=xxx>RAL</span></h1>
	<div class=choicebox>
		<a href='/B4U'>Enter</a>
	</div>
</div>
<!-- Scripts -->
<script src='js/esthetic.js'></script>
<script>
var xxx = [
	'The future',
	'Virtual Reality',
	'20XX',
	'New society',
	'Now',
	'Forever',
	'Tomorrow',
	'Yesterday',
	'The Void',
	'Nothing',
	'Arcadia',
	'Worlds',
	'Digital Heaven'
];
flashmessages(document.getElementById('xxx'), xxx, 300);

// Zoom out animation
var welcome = document.getElementById('welcome');
var choicebox = welcome.getElementsByClassName('choicebox')[0];
choicebox.addEventListener('click', function(e) {
	e.preventDefault();
	var welcome = document.getElementById('welcome');
	welcome.style.opacity = '0';
	welcome.style.top = '0';
	welcome.addEventListener('transitionend', function(t) {
		if (t.propertyName == 'opacity' || t.propertyName == 'top')
		window.location = e.target.href;
	});
});

// Disable bfCache on firefox (we want JS to execute again!)
window.onunload= function(){};
</script>
<!-- End of scripts -->
</body>
</HTML>
