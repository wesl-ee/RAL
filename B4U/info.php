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
		$pagetitle = 'Theme';
	elseif (isset($_GET['announce']))
		$pagetitle = 'Announcements';
	elseif (isset($_GET['help']))
		$pagetitle = 'Help';
	else
		$pagetitle = 'About';
	include "{$ROOT}template/head.php"; ?>
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
		"url" => CONFIG_WEBROOT . "info",
		],
		[
		"name" => "Theme",
		"url" => CONFIG_WEBROOT . "info/theme",
		],
		[
		"name" => "Help",
		"url" => CONFIG_WEBROOT . "info/help",
		],
		[
		"name" => "Doc",
		"url" => CONFIG_WEBROOT . "doc",
		] ];
	}
	else {
		$items = [
		[
		"name" => "About",
		"url" => CONFIG_WEBROOT . "info.php",
		],
		[
		"name" => "Theme",
		"url" => CONFIG_WEBROOT . "info.php?theme",
		],
		[
		"name" => "Help",
		"url" => CONFIG_WEBROOT . "info.php?help",
		],
		[
		"name" => "Doc",
		"url" => CONFIG_WEBROOT . "doc.php",
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
		foreach (CONFIG_THEMES as $theme => $displayname) {
			if ($theme == $currtheme)
				print "<option value=$theme selected>$displayname</option>";
			else
				print "<option value=$theme>$displayname</option>";
		}
		print
<<<HTML
		</select></dd>
		</dl><input type=submit class=hoverbox value=Commit></form>

HTML;

	} else if (isset($_GET['help'])) {
		print
<<<HTML
		<h1>Help</h1>
		<div class=reader>
HTML;
		ppppppp("{$ROOT}info/HELP.pod");
print
<<<HTML
		</div>

HTML;
	} else {
		print
<<<HTML
		<h1>About</h1>
		<div class=reader>
HTML;
		ppppppp("{$ROOT}info/ABOUT.pod");
print
<<<HTML
		</div>

HTML;



	} ?>
</div>
</body>
</HTML>
