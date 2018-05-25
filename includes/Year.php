<?php namespace RAL;
class Year {
	public $Year;
	public $Continuity;
	public $Count;

	public $Parent;

	public function __construct($row, $parent) {
		$this->Year = $row['Year'];
		$this->Continuity = $row['Continuity'];
		$this->Count = $row['Count'];
		$this->Parent = $parent;
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
	public function renderBanner() {
		return $this->Parent->renderBanner();
	}
	public function renderPostButton() {
		return $this->Parent->renderPostButton();
	}
	public function renderComposer() {
		return $this->Parent->renderComposer();
	}
	public function post($content) {
		return $this->Parent->post($content);
	}
	function title() {
		return "[{$this->Continuity}/{$this->Year}]";
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
