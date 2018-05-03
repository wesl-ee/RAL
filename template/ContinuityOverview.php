<table>
<tr>
	<th>Year</th>
	<th>Discussion Topics</th>
	<th>Text Archive</th>
</tr>
<?php $years = $this->getYears();
foreach ($years as $year) {
	print <<<HTML
	<tr>
	<td><a href="$year[URL]">$year[Year]</a></td>
	<td>$year[Topics]</td>
	<td>N/A</td>
	</tr>
HTML;
}
?>
</table>
