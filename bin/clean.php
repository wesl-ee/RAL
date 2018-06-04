#!/usr/bin/php
<?php $ROOT = "../";
include "{$ROOT}includes/config.php";

$locations = [
	CONFIG_LOCALROOT . 'tmp/robocheck-answers/',
	CONFIG_LOCALROOT . 'www/robocheck/',
];

foreach ($locations as $l) cleanoldfiles($l);

function cleanoldfiles($path) {
	$now = time();
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($path)
	);
	foreach ($iterator as $file) {
		if ($file->isDir()) continue;
		if ($file->getFilename() == '.keep') continue;
		if ($now - $file->getCTime() > CONFIG_TMP_EXPIRY*60)
			unlink($file->getPathName());
	}
}
