<?php
$ROOT = '../';
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
	<h1>Welcome to<br/><span class=xxx-welcome>RAL</span></h1>
	<div class=choicebox>
		<a href='select.php'>Enter</a>
	</div>
</div>
<!-- Scripts -->
<script src='<?php print $ROOT?>js/esthetic.js'></script>
<script>
// Zoom out animation
var welcome = document.getElementById('welcome');
var choicebox = welcome.getElementsByClassName('choicebox')[0];
choicebox.addEventListener('click', function(e) {
	e.preventDefault();
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
