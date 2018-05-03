<?php if ($position > 1) print '/';
print <<<HTML
	<li property=itemListElement typeof=ListItem>
		<a href="$href" property=item typeof=WebPage>
		<span property=name>$name</span></a>
		<meta property=position content=$position />
	</li>

HTML;
