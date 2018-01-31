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
		$pagetitle = 'HACKING';
	elseif (isset($_GET['install']))
		$pagetitle = 'INSTALL';
	elseif (isset($_GET['license']))
		$pagetitle = 'LICENSE';
	else
		$pagetitle = 'README';
	include "{$ROOT}template/head.php" ?>
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
	if (CONFIG_CLEAN_URL) {
		$items = [
		[
		"name" => "Readme",
		"url" => CONFIG_WEBROOT . "doc",
		],
		[
		"name" => "Install",
		"url" => CONFIG_WEBROOT . "doc/install",
		],
		[
		"name" => "License",
		"url" => CONFIG_WEBROOT . "doc/license",
		],
		[
		"name" => "Hacking",
		"url" => CONFIG_WEBROOT . "doc/hacking",
		] ];
	} else {
		$items = [
		[
		"name" => "Readme",
		"url" => CONFIG_WEBROOT . "doc.php",
		],
		[
		"name" => "Install",
		"url" => CONFIG_WEBROOT . "doc.php?install",
		],
		[
		"name" => "License",
		"url" => CONFIG_WEBROOT . "doc.php?license",
		],
		[
		"name" => "Hacking",
		"url" => CONFIG_WEBROOT . "doc.php?hacking",
		] ];
	}
	include "{$ROOT}template/nav.php";
?>

</div>
<div id=rightpanel>
<?php
	$title = $pagetitle;
	include "{$ROOT}template/header.php";
?>
	<div class='reader docs'>
<?php
	if (isset($_GET['install'])) {
		$file = CONFIG_LOCALROOT . "docs/INSTALL.pod";
		ppppppp($file);
	} else if (isset($_GET['hacking'])) {
		$file = CONFIG_LOCALROOT . "docs/HACKING.pod";
		ppppppp($file);
	} else if (isset($_GET['license'])) {
		$file = CONFIG_LOCALROOT . "docs/LICENSE";
		ppppppp($file);
	} else {
		$file = CONFIG_LOCALROOT . "docs/README.pod";
		ppppppp($file);
	}
?>
	</div>
</div>
</body>
</HTML>
