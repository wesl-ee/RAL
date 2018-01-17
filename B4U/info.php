<?php
$ROOT = '../';
include $ROOT."includes/main.php";
include $ROOT."includes/post.php";

if (isset($_POST['theme'])) {
	setcookie('theme', $_POST['theme'], CONFIG_COOKIE_TIMEOUT+time(), '/');
}
if (count($_POST)) {
	header("Location: ?$_SERVER[QUERY_STRING]");
	die;
}
function nl22br($string)
{
	return str_replace("\n\n", "<br/><br/>", $string);
}

?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	if (isset($_GET['theme']))
		$title = 'Theme';
	elseif (isset($_GET['announce']))
		$title = 'Announcements';
	elseif (isset($_GET['help']))
		$title = 'Help';
	else
		$title = 'About';
	head($title) ?>
</head>
<body>
<div class=sidebar>
	<h2>RAL</h2>
<?php
	$a = CONFIG_WEBROOT;
	print
<<<HTML
	<a href="$a">Home</a>

HTML;
?>
	<span class=collection>
<?php
	if (CONFIG_CLEAN_URL) {
		$items = [
		[
		"name" => "About",
		"location" => CONFIG_WEBROOT . "info",
		],
		[
		"name" => "Theme",
		"location" => CONFIG_WEBROOT . "info/theme",
		],
		[
		"name" => "Help",
		"location" => CONFIG_WEBROOT . "info/help",
		],
		[
		"name" => "Doc",
		"location" => CONFIG_WEBROOT . "doc",
		] ];
	}
	else {
		$items = [
		[
		"name" => "About",
		"location" => CONFIG_WEBROOT . "info.php",
		],
		[
		"name" => "Theme",
		"location" => CONFIG_WEBROOT . "info.php?theme",
		],
		[
		"name" => "Help",
		"location" => CONFIG_WEBROOT . "info.php?help",
		],
		[
		"name" => "Doc",
		"location" => CONFIG_WEBROOT . "doc.php",
		] ];
	}
	include "{$ROOT}template/nav.php";
?>
	</span>
</div>
<div id=rightpanel>
	<?php if (isset($_GET['theme'])) {
		$currtheme = get_theme();
		print
<<<HTML
		<h1>Theming</h1>
		<div class=reader>
		<form action=?theme method=POST><dl><dt>Theme</dt>
		<dd><select name=theme>

HTML;
		foreach (CONFIG_THEMES as $theme => $one) {
			if ($theme == $currtheme)
				print "<option selected>$theme</option>";
			else
				print "<option>$theme</option>";
		}
		print
<<<HTML
		</select></dd>
		</dl><input type=submit class=hoverbox value=Commit></form>

HTML;

	} else if (isset($_GET['help'])) {
		$mail = CONFIG_ADMIN_MAIL; $name = ucfirst(CONFIG_ADMIN_NAME);
		// Probably read this in from a file
		print
<<<HTML
		<h1>Help</h1>
		<div class=reader>
		<h2>What is a continuity?</h2><p>

		When you enter RAL, you will see several different
		continuities. A continuity is like a chat-room or a
		sub-forum. The title of a continuity will often give you a
		clue as to what its focus of dicsussion is.

		</p><h2>How do I post?</h2><p>

		Within each continuity, there is a button labeled "Create a
		topic" which allows you to create a new discussion topic
		within a continuity. If you find an interesting topic, you
		can create a post within that topic by clicking "Reply to
		topic" once you have the topic open

		</p><h2>Can I style my posts?</h2><p>

		Yes! Posts on RAL can be styled by marking up your post with
		the following supported BBCode tags:</p>
		<ul>
		<li>[b] (Bold)</li>
		<li>[i] (Emphasis)</li>
		<li>[j] (Computer jargon)</li>
		<li>[color=x]
			<ul><li>
				x can be any valid HTML color name, hex
				triplet, rgb() triple, or hsl() color space
			</li></ul>
		</li>
		<li>[code] (Monospace code block)</li>
		<li>[url=href]</li>
			<ul><li>
				href is a link to a resource on the
				internet. Protocols other than http(s): are
				allowed as well (e.g. ftp)
			</li></ul>
		</ul><p>

		For example, the following text:</p>
		<pre>Here is a link to my favorite [url=www.msn.com]website[/url]</pre><p>
		Will be parsed and the resulting post will look like:</p>
		<pre>Here is a link to my favorite <a
		href=http:www.msn.com>website</a></pre>

		<h2>What is not allowed?</h2><ul>
			<li>Spamming</li>
		</ul>

		<h2>Who can I contact about abuse / questions?</h2><p>
		$name is the administrator, sole protector and guardian angel
		of RAL and can be reached via prayer or by mail:
		<a href=mailto:$mail>$mail</a>
		</p></div>

HTML;
	} else {
		$txt = nl22br(bbbbbbb(file_get_contents('about.txt')));
		print
<<<HTML
		<h1>About</h1>
		<div class=reader>
		$txt
		</div>

HTML;



	} ?>
</div>
</body>
</HTML>
