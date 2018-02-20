<?php
define("RELEASES", [
	'v1.0' => 'Lightning',
	'v1.1' => 'Snow'
]);

/*
 * Retrieve information about this install of RAL
*/
function git_head($dir)
{
	$checksum = exec("git -C $dir rev-parse --short HEAD", $exit);
	if (!$exit) return false;
	$tag = exec("git -C $dir describe --tags --abbrev=0", $exit);
	if (!$exit) return false;
	$name = RELEASES[$tag];
	return [
		'checksum' => $checksum,
		'tag' => $tag,
		'cutename' => $name
	];
}
?>
