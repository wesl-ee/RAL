#!/usr/bin/php
<?php $ROOT = "../";
include "{$ROOT}includes/config.php";
include "{$ROOT}includes/ResourceManager.php";
include "{$ROOT}includes/ContinuityIterator.php";

$RM = new RAL\ResourceManager();
$dbh = $RM->getdb();
$iterator = new RAL\ContinuityIterator($RM);
$STDIN = fopen('php://stdin', 'r');

PRINT <<<BANNER
  _____            _
 |  __ \     /\   | |
 | |__) |   /  \  | |
 |  _  /   / /\ \ | |
 | | \ \  / ____ \| |____
 |_|  \_\/_/    \_\______|
   Welcome, Super-user.

BANNER;

do { $answer = ask([
'Continuities',
'News',
'Bans',
'Post Details',
'Miscellany',
'Quit'
]); switch ($answer) {
	case 'Continuities':
	$answer = ask([
	'Metrics',
	'Create a Continuity',
	'Delete a Continuity'
	]); switch ($answer) {
		case 'Metrics':
		break;

		case 'Create a Continuity':
		$C = new RAL\Continuity([
			'Name' => prompt('Name'),
			'Description' => prompt('Description'),
		], $iterator);
		$C->create();
		break;

		case 'Delete a Continuity':
		$C = new RAL\Continuity([
			'Name' => prompt('Name'),
		], $iterator);
		if (prompt('Are you sure? (Y/n) ') == 'Y') $C->destroy();
	} break;

	// Other main-menu options go here
} } while ($answer != 'Quit');
fclose($STDIN);

// Prompt a user with some choices and return the answer
function ask($choices) {
	for ($i = 1; $i - 1 < count($choices); $i++)
		print("$i.) {$choices[$i-1]}\n");
	return $choices[(int)prompt()-1];
}
function prompt($string = '') {
	if ($string == '') print "> ";
	else print "$string: ";
	$STDIN = fopen('php://stdin', 'r');
	return trim(fgets($STDIN));
}
