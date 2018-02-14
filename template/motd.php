<?php if (is_file(CONFIG_LOCAL_ROOT . "info/MOTD.txt")) {
	print <<<HTML
	<span class=motd>

HTML;
	readfile(CONFIG_LOCAL_ROOT . "info/MOTD.txt");
	print <<<HTML
	</span>

HTML;
}?>
