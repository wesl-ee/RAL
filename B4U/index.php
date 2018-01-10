<?php
$ROOT = '../';
include $ROOT.'includes/main.php';
include $ROOT.'includes/fetch.php';
include $ROOT.'includes/post.php';
include $ROOT.'includes/render.php';

$page = $_GET['p'];
if (!isset($page)) $page = 0;
?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php head('Home')?>
</head>
<body>
<div id=welcome>
	<header>
		<h1>RAL</h1>
		<em>Neo-Forum Text Board</em>
	</header>
	<div id=timelines>
<?php
		rendercontinuitynav($page)
?>
	</div>
	<strong>Recent Posts</strong>
	<div class="reader recent">
<?php
	$recent = fetch_recent_posts(10);
	foreach ($recent as $post) {
		$content = $post['content'];
		// Dress up the content
		$time = date("M d Y", strtotime($post['date']));
		$id = $post['id'];
		$topic = $post['topic'];
		$timeline = $post['timeline'];
		if (CONFIG_CLEAN_URL)
			$a = CONFIG_WEBROOT . "max/$timeline/$topic#$id";
		else
			$a = CONFIG_WEBROOT
			. "max.php?timeline=$timeline&topic=$topic#$id";
		print
<<<HTML
		<article data-post=$id>
			<a href="$a" class=info>
				<span class=id>[$timeline/$topic]</span>
				<time>$time</time>
			› </a>
			<span class=content data-topic=$id>
			$content
			</span>
		</article>

HTML;
	}
?>
	</div>
	<footer>
		<?php if (CONFIG_CLEAN_URL) {
			$a = CONFIG_WEBROOT . "info/";
			print
<<<HTML
		<a href={$a}theme>Theme</a>
		<a href={$a}>About</a>
		<a href={$a}help>Help</a>

HTML;
		}
		else {
			$a = CONFIG_WEBROOT . "info/";
			print
<<<HTML
		<a href={$a}?theme>Theme</a>
		<a href={$a}?>About</a>
		<a href={$a}?help>Help</a>

HTML;
		}
?>
	<br/>
	<a href='https://github.com/yumi-xx/RAL'>Source Code</a><br/>
	<span>(<?php print date('Y')?>) BSD 3-Clause</span>
	</footer>
</div>
<!-- Scripts -->
<script src='<?php print CONFIG_WEBROOT?>js/remote.js'></script>
<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
<script>
/* Make the site pretty if the user has JS */
var reader = document.getElementById(
	'welcome'
).getElementsByClassName('reader')[0];
var timelines = document.getElementById('timelines');

var collection = timelines.getElementsByClassName('collection')[0];
var leftnav = timelines.getElementsByClassName('leftnav')[0];
var rightnav = timelines.getElementsByClassName('rightnav')[0];

connectnav(collection, leftnav, rightnav);
connectreader(reader);

</script>
<!-- End of scripts -->
</body>
</HTML>
