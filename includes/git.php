<?php
define("RELEASES", [
	'v1.0' => 'Lightning'
]);

/*
 * Retrieve information about this install of RAL
*/
function git_head($dir)
{
	$checksum = exec("git -C $dir rev-parse --short HEAD");
	$tag = exec("git -C $dir describe --tags --abbrev=0");
	$name = RELEASES[$tag];
	return [
		'checksum' => $checksum,
		'tag' => $tag,
		'cutename' => $name
	];
}
?>
