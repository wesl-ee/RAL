<ol vocab='http://schema.org/' typeof=BreadcrumbList
class=breadcrumb>
<?php
	$a = CONFIG_WEBROOT;
	print
<<<HTML
	<li property=itemListElement typeof=ListItem>
		<a href='$a' property=item typeof=WebPage>
		<span property=name>RAL</span></a>
		<meta property=position content=1 />
	</li>

HTML;
	if (isset($timeline)) {
		if (CONFIG_CLEAN_URL)
			$a .= "max/$timeline";
		else
			$a .= "max.php?timeline=$timeline";
		print
<<<HTML
	›<li property=itemListElement typeof=ListItem>
		<a href='$a' property=item typeof=WebPage>
		<span property=name>$timeline</span></a>
		<meta property=position content=2 />
	</li>

HTML;
	}
	if (isset($topic)) {
		if (CONFIG_CLEAN_URL)
			$a .= "/$topic";
		else
			$a .= "&topic=$topic";
		print
<<<HTML
	›<li property=itemListElement typeof=ListItem>
		<a href='$a' property=item typeof=WebPage>
		<span property=name>$topic</span></a>
		<meta property=position content=3 />
	</li>

HTML;
	}
?>
</ol>
