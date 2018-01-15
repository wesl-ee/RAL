<?php
$ROOT = '../';
include $ROOT."includes/main.php";
include $ROOT."includes/post.php";

?>
<!DOCTYPE HTML>
<HTML>
<head>
<?php
	if (isset($_GET['hacking']))
		$title = 'HACKING';
	elseif (isset($_GET['install']))
		$title = 'INSTALL';
	elseif (isset($_GET['license']))
		$title = 'LICENSE';
	else
		$title = 'README';
	head($title) ?>
</head>
<body>
<div class=sidebar>
	<h2>RAL</h2>
	<span>Doc</span>
	<span class=collection>
		<?php if (CONFIG_CLEAN_URL) {
			$a = CONFIG_WEBROOT . "doc/";
			print
<<<HTML
		<a class=hoverbox href={$a}>Readme</a>
		<a class=hoverbox href={$a}install>Install</a>
		<a class=hoverbox href={$a}license>License</a>
		<a class=hoverbox href={$a}hacking>Hacking</a>

HTML;
		}
		else
			print
<<<HTML
		<a class=hoverbox href=?>Readme</a>
		<a class=hoverbox href=?install>Install</a>
		<a class=hoverbox href=?license>License</a>
		<a class=hoverbox href=?hacking>Hacking</a>

HTML;
?>
	</span>
	<?php
	$a = CONFIG_WEBROOT;
	print
<<<HTML
	<a href="$a">Home</a>

HTML;
?>
</div>
<div id=rightpanel>
	<div class='reader docs'>
<?php
	if (isset($_GET['install'])) {
		$file = CONFIG_LOCALROOT . "docs/INSTALL.pod";
		podprint($file);
	} else if (isset($_GET['hacking'])) {
		$file = CONFIG_LOCALROOT . "docs/HACKING.pod";
		podprint($file);
	} else if (isset($_GET['license'])) {
		$file = CONFIG_LOCALROOT . "docs/LICENSE";
		podprint($file);
	} else {
		$file = CONFIG_LOCALROOT . "docs/README.pod";
		podprint($file);
	}
?>
	</div>
</div>
</body>
</HTML>
