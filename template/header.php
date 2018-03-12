<?php if (!isset($title)) {
		print "Improper template usage!";
		die;
} ?>
<header class=floathead>
	<h1><?php print $title?></h1>
<?php
	if (isset($subtitle)) print
<<<HTML
	<em>$subtitle</em>

HTML;
?>
</header>
