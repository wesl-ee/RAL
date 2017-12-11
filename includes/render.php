<?php
function bbbbbbb($string) {
	$tags = 'b|i|color|quote|url';
	while (preg_match_all('`\[('.$tags.')=?(.*?)\](.+?)\[/\1\]`',
	$string, $matches))
	foreach ($matches[0] as $key => $match) {
		$tag = $matches[1][$key];
		$param = $matches[2][$key];
		$innertext = $matches[3][$key];
		switch ($tag) {
		case 'b':
			$replacement = "<strong>$innertext</strong>";
			break;
                case 'i':
			$replacement = "<em>$innertext</em>";
			break;
		case 'color':
			$replacement = "<span style=\"color:"
			." $param;\">$innertext</span>";
			break;
		case 'quote':
			$replacement = "<blockquote>$innertext</blockquote>"
			. $param? "<cite>$param</cite>" : '';
		break;
		case 'url':
			$replacement = '<a href="'
			. ($param ? $param : $innertext) . "\">$innertext</a>";
			break;
		}
		$string = str_replace($match, $replacement, $string);
	}
	return $string;
}
?>
