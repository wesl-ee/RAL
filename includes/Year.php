<?php namespace RAL;
class Year {
	public $Year;
	public $Count;

	public function __construct($row) {
		$this->Year = $row['Year'];
		$this->Count = $row['Count'];
	}
	public function render() {
		print <<<HTML
	<tr>
		<td>$this->Year</td>
		<td>$this->Count</td>
		<td>N/A</td>
	</tr>
HTML;
	}
	public function renderSelection($items) {
		print <<<HTML
	<main><table>
	<tr>
		<th>Year</th>
		<th>Topics</th>
		<th>Text Archive</th>
	</tr>
HTML;
		foreach ($items as $i) $i->render();
		print <<<HTML
	</table></main>
HTML;
	}
}
