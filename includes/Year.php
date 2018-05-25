<?php namespace RAL;
class Year {
	public $Year;
	public $Continuity;
	public $Count;

	public function __construct($row) {
		$this->Year = $row['Year'];
		$this->Continuity = $row['Continuity'];
		$this->Count = $row['Count'];
	}
	public function render() {
		$href = $this->resolve();
		print <<<HTML
	<tr>
		<td><a href="$href">$this->Year</a></td>
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
	function selectiontitle() {
		return "[{$this->Continuity}]";
	}
	function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . "/"
			. rawurlencode($this->Year);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year);
	}
}
