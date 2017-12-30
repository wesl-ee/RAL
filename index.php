<?php
$ROOT = './';
include $ROOT.'includes/main.php';
?>

<!DOCTYPE HTML>
<HTML>
<head>
	<?php head($ROOT)?>
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
var flash = flashmessages(document.getElementById('xxx'), xxx, 300);

// Zoom out animation
var welcome = document.getElementById('welcome');
var choicebox = welcome.getElementsByClassName('choicebox')[0];
choicebox.addEventListener('click', function(e) {
	e.preventDefault();
	clearInterval(flash);
	var welcome = document.getElementById('welcome');
	// BUG: choicebox bottom-border is cut off
	welcome.style.overflow = 'hidden';
	welcome.style.width = welcome.offsetWidth + "px";
	welcome.style.height = welcome.offsetHeight + "px";
	welcome.style.borderRightWidth = '10px';
	welcome.style.borderLeftWidth = '10px';
	welcome.addEventListener('transitionend', function(t) {
	if (t.propertyName == 'border-right-width'
	|| t.propertyName == 'border-left-width')
		welcome.style.width = '0';
	if (t.propertyName == 'width')
		welcome.style.height = '0';
	if (t.propertyName == 'height')
		window.location = e.target.href;
	});
});
// Disable bfCache on firefox (we want JS to execute again!)
window.onunload= function(){};
</script>
<!-- End of scripts -->
</body>
</HTML>
