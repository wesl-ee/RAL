<?php
$ROOT = '../';
include $ROOT."includes/main.php";
include $ROOT."includes/post.php";

if (isset($_POST['theme'])) {
	setcookie('theme', $_POST['theme'],
	CONFIG_COOKIE_TIMEOUT+time(), '/');
}
if (count($_POST)) {
	if (isset($_POST['dnotify']))
		setcookie('dnotify', 'XXX',
		CONFIG_COOKIE_TIMEOUT+time(), '/');
	else
		setcookie('dnotify', '', 1, '/');
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

	if (!empty($_GET['page']))
		$pagetitle = ucfirst($_GET['page']);
	else
		$pagetitle = 'About';
	include "{$ROOT}template/head.php"; ?>
	<script src='<?php print CONFIG_WEBROOT?>js/esthetic.js'></script>
</head>
<body><main>
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
		"name" => "Readme",
		"url" => CONFIG_WEBROOT . "info/readme",
		],
		[
		"name" => "Install",
		"url" => CONFIG_WEBROOT . "info/install",
		],
		[
		"name" => "License",
		"url" => CONFIG_WEBROOT . "info/license",
		],
		[
		"name" => "Hacking",
		"url" => CONFIG_WEBROOT . "info/hacking",
		],
		[
		"name" => "API",
		"url" => CONFIG_WEBROOT . "info/api",
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
		"url" => CONFIG_WEBROOT . "info.php?page=theme",
		],
		[
		"name" => "Help",
		"url" => CONFIG_WEBROOT . "info.php?page=help",
		],
		[
		"name" => "Readme",
		"url" => CONFIG_WEBROOT . "info.php?page=readme",
		],
		[
		"name" => "Install",
		"url" => CONFIG_WEBROOT . "info.php?page=install",
		],
		[
		"name" => "License",
		"url" => CONFIG_WEBROOT . "info.php?page=license",
		],
		[
		"name" => "Hacking",
		"url" => CONFIG_WEBROOT . "info.php?page=hacking",
		],
		[
		"name" => "API",
		"url" => CONFIG_WEBROOT . "info.php?page=api",
		] ];
	}
	include "{$ROOT}template/toolbar.php";
?>
<?php if ($_GET['page'] == 'theme') {
		$currtheme = get_theme();
		print <<<HTML
		<h1>Theming</h1>
		<div id=reader>
		<form action=?theme method=POST><dl><dt>Theme</dt>
		<dd><select name=theme>

HTML;
		foreach (CONFIG_THEMES as $theme => $displayname) {
			if ($theme == $currtheme)
				print <<<HTML
			<option value=$theme selected>$displayname</option>

HTML;
			else
				print <<<HTML
			<option value=$theme>$displayname</option>

HTML;
		}
		print <<<HTML
		</select></dd>
		</dl>
		<dl><dt>Desktop Notifications</dt>
		<dd>
HTML;
		if (isset($_COOKIE['dnotify']))
			print <<<HTML
		<input type=checkbox checked name=dnotify>
HTML;
		else print <<<HTML
		<input type=checkbox name=dnotify>
HTML;
		print <<<HTML
		</dd></dl>
		<input type=submit class=hoverbox value=Commit></form>
		</div>

HTML;
	/* Documentation & help pages */
	} else {
		switch ($_GET['page']) {
		case 'help': $docpage = "{$ROOT}info/HELP.pod"; break;
		case 'readme': $docpage = "{$ROOT}docs/README.pod"; break;
		case 'install': $docpage = "{$ROOT}docs/INSTALL.pod"; break;
		case 'license': $docpage = "{$ROOT}docs/LICENSE"; break;
		case 'hacking': $docpage = "{$ROOT}docs/HACKING.pod"; break;
		case 'api': $docpage = "{$ROOT}docs/API.pod"; break;
		default: $docpage = "{$ROOT}info/ABOUT.pod"; }
		print <<<HTML
		<div id=reader>
HTML;
		ppppppp($docpage);
print <<<HTML
		</div>

HTML;
	} ?>
</main>
</body>
</HTML>
