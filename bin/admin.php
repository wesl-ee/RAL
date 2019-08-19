#!/usr/bin/php
<?php $ROOT = "../";
include "{$ROOT}includes/Ral.php";
include "{$ROOT}includes/config.php";
include "{$ROOT}includes/ResourceManager.php";
include "{$ROOT}includes/Renderer.php";
$rm = new RAL\ResourceManager();
$Renderer = new RAL\Renderer($rm);
$Ral = new RAL\Ral($rm);
$dbh = $rm->getdb();
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
		$Ral->select(prompt('Continuity'),
			prompt('Year'),
			prompt('Topic'),
			prompt('Id'));
		if ($Ral->Post->unlearn($rm))
			print "Successfully unlearned!\n";
		else
			print "Failure: post was never learned as spam\n";
		break;

		case 'Post Info':
		$Ral->select(prompt('Continuity'),
			prompt('Year'),
			prompt('Topic'),
			prompt('Id'));
		$Ral->Post->InfoText();
		break;

		case 'Delete a Post':
		$Ral->select(prompt('Continuity'),
			prompt('Year'),
			prompt('Topic'),
			prompt('Id'));
		$Ral->Post->delete();
		break;

		case 'Create a Continuity':
		$C = new RAL\Continuity([
			'Name' => prompt('Name'),
			'Description' => prompt('Description'),
		], $Ral);
		$C->create();
		break;

		case 'Delete a Continuity':
		$C = new RAL\Continuity([
			'Name' => prompt('Name'),
		], $Ral);
		if (prompt('Are you sure? (Y/n) ') == 'Y') $C->destroy();
	} break;
	case 'Spam':
		$answer = ask([
		'Train Filter',
		]); switch ($answer) {
		case 'Train Filter':
			$b8 = $rm->getb8();
			$Ral->selectUnlearned();
			for ($i = 0; $i < sizeof($Ral->Selection); $i++) {
				$post = $Ral->Selection[$i];
				$spamminess = $b8->classify($rm->asHtml(
					$post->Content));

				printf("(%d posts remain)\n", sizeof($Ral->Selection) - $i);
				print $rm->asText($post->Content);
				switch(prompt("\nIs this spam? (Y/n) (Score: $spamminess)")) {
					case 'Y':
					case 'y':
						if ($spamminess > 0.8) {
							print "That's what I figured!\n";
							$post->b8GuessWasCorrect($rm, \b8::SPAM);
						} else {
							print "I'll make a note of that...\n";
							$post->learn($rm, \b8::SPAM);
						} break;
					case 'N':
					case 'n':
						if ($spamminess < 0.6) {
							print "That's what I figured!\n";
							$post->b8GuessWasCorrect($rm, \b8::HAM);
						} else {
							print "I'll make a note of that...\n";
							$post->learn($rm, \b8::HAM);
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
