<!DOCTYPE HTML>
<HTML>
<head>
	<link rel=stylesheet href="css/base.css">
	<link rel=stylesheet href="css/20XX.css">
	<title>RAL</title>
</head>
<body>
<div id=timelines class=sidebar>
	<h2>Connected</h2>
	<span>128 ms delay</span>
	<div class=collection id=test>
	</div>
	<nav id=timelinenav>
	<a class=leftnav>◀</a>
	<a class=rightnav>▶</a>
	</nav>
</div>
<div id=reader class=timeline>
	<h3 class=title>Anime</h3>
	<article class=topic id=5>
		<time datetime=2017-12-03T19:00>Today</time>
		<span class=num>No. 5</span>
		<span class=subject onClick='window.handlers.open(this)'>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</span>
	</article>
	<article class=topic id=4>
		<time datetime=2017-12-03T19:00>Today</time>
		<span class=num>No. 4</span>
		<span class=subject>fuck</span>
	</article>
	<article class=topic id=3>
		<time datetime=2017-12-03T19:00>Today</time>
		<span class=num>No. 3</span>
		<span class=subject>fuck</span>
	</article>
	<article class=topic id=2>
		<time datetime=2017-12-03T19:00>Today</time>
		<span class=num>No. 2</span>
		<span class=subject>fuck</span>
	</article>
	<article class=topic id=1>
		<time datetime=2017-12-03T19:00>Today</time>
		<span class=num>No. 1</span>
		<span class=subject>fuck</span>
	</article>
	<div id=redzone>
	</div>
</div>
<div id=sakura>
	<video autoplay loop muted>
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
