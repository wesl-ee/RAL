<!DOCTYPE HTML>
<HTML>
<head>
	<link rel=stylesheet href="css/base.css">
	<link rel=stylesheet href="css/20XX.css">
	<title>RAL</title>
</head>
<body>
<div id=timelines class='frontcenter'>
	<h2>Connected</h2>
	<span>128 ms delay</span>
	<div class=collection id=test>
	</div>
	<nav id=timelinenav>
	<a class=leftnav>◀</a>
	<a class=rightnav>▶</a>
	</nav>
</div>
<div id=sakura>
	<video autoplay loop>
		<source src='res/splash.webm'>
	</video>
	<img src='res/fallback.gif'>
</div>
</body>
<script src='js/esthetic.js'></script>
<script src='js/transitions.js'></script>
<script src='js/remote.js'></script>
<script>
window.transitions.newpage(
	document.getElementById('test'),
	0
);
var collection = document.getElementById('test');
var rightnav = collection.parentNode.getElementsByClassName('rightnav')[0];
var leftnav = collection.parentNode.getElementsByClassName('leftnav')[0];
leftnav.collection = collection;
leftnav.addEventListener('click', window.transitions.newpageclick);
rightnav.toPage = 1;
rightnav.collection = collection;
rightnav.addEventListener('click', window.transitions.newpageclick);
</script>
</HTML>
