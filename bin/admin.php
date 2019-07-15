#!/usr/bin/php
<?php $ROOT = "../";
include "{$ROOT}includes/config.php";
include "{$ROOT}includes/ResourceManager.php";
include "{$ROOT}includes/ContinuityIterator.php";
include "{$ROOT}includes/mod.php";

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
'Content',
'News',
'Spam',
'Bans',
'Post Details',
'Miscellany',
'Quit'
]); switch ($answer) {
	case 'Content':
	$answer = ask([
	'Metrics',
	'Post Info',
	'Mark / Learn as Spam',
	'Unmark / Unlearn as Spam',
	'Delete a Post',
	'Create a Continuity',
	'Delete a Continuity'
	]); switch ($answer) {
		case 'Metrics':
		break;

		case 'Mark / Learn as Spam':
		break;

		case 'Unmark / Unlearn as Spam':
		$iterator->select(prompt('Continuity'),
			prompt('Year'),
			prompt('Topic'),
			prompt('Id'));
		if ($iterator->Post->unlearn())
			print "Successfully unlearned!\n";
		else
			print "Failure: post was never learned as spam\n";
		break;

		case 'Post Info':
		$iterator->select(prompt('Continuity'),
			prompt('Year'),
			prompt('Topic'),
			prompt('Id'));
		$iterator->Post->InfoText();
		break;

		case 'Delete a Post':
		$iterator->select(prompt('Continuity'),
			prompt('Year'),
			prompt('Topic'),
			prompt('Id'));
		$iterator->Post->delete();
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
	case 'Spam':
		$answer = ask([
		'Train Filter',
		]); switch ($answer) {
		case 'Train Filter':
			$b8 = $RM->getb8();
			$iterator->selectUnlearned();
			for ($i = 0; $i < sizeof($iterator->Selection); $i++) {
				$post = $iterator->Selection[$i];
				$spamminess = $b8->classify($RM->asHtml(
					$post->Content));

				printf("(%d posts remain)\n", sizeof($iterator->Selection) - $i);
				print $post->renderAsText(false);
				switch(prompt("Is this spam? (Y/n) (Score: $spamminess)")) {
					case 'Y':
					case 'y':
						if ($spamminess > 0.8) {
							print "That's what I figured!\n";
							$post->b8GuessWasCorrect(\b8::SPAM);
						} else {
							print "I'll make a note of that...\n";
							$post->learn(\b8::SPAM);
						} break;
					case 'N':
					case 'n':
						if ($spamminess < 0.6) {
							print "That's what I figured!\n";
							$post->b8GuessWasCorrect(\b8::HAM);
						} else {
							print "I'll make a note of that...\n";
							$post->learn(\b8::HAM);
						} break;
					default:
						print "Skipping...";
				} print "\n";
			}
		} break;
	case 'Bans':
	$answer = ask([
	'Flame a User',
	'Forgive a Flamed User',
	'List all Flamed Users'
	]); switch ($answer) {
		case 'Flame a User':
			addban(prompt("User ID"),
				ask(['SLOW', 'HELL']));
			break;
		case 'Forgive a Flamed User':
			clearban(prompt("User ID"));
	}

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
