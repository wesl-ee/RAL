<?php namespace RAL;
class Year {
	public $Year;
	public $Continuity;
	public $Count;

	private $Parent;

	public function __construct($row, $parent) {
		$this->Year = $row['Year'];
		$this->Continuity = $row['Continuity'];
		$this->Count = $row['Count'];
		$this->Parent = $parent;
	}
	public function getRM() { return $this->Parent->getRM(); }
	public function getParent() { return $this->Parent; }
	public function renderAsHtml() {
		$href = htmlentities($this->resolve());
		print <<<HTML
	<tr>
		<td><a href="$href">$this->Year</a></td>
		<td>$this->Count</td>
		<td>N/A</td>
	</tr>
HTML;
	}
	public function renderAsText() {
		print <<<TEXT
[{$this->Continuity}/{$this->Year}] ($this->Count topics)

TEXT;
	}
	public function renderAsSitemap() {
		$loc = $this->resolve();
print <<<XML
	<url>
		<loc>$loc</loc>
	</url>

XML;
	}
	public function renderSelection($items, $format) {
		switch ($format) {
		case 'html':
			print <<<HTML
<main><table>
<tr>
	<th>Year</th>
	<th>Topics</th>
	<th>Text Archive</th>
</tr>
HTML;
			foreach ($items as $i) $i->renderAsHtml();
			print <<<HTML
</table></main>
HTML;
		break; case 'text':
			foreach ($items as $i) $i->renderAsText();
		break; case 'sitemap':
			foreach ($items as $i) $i->renderAsSitemap();
		break; case 'json':
			print json_encode($items);
		}
	}
	public function renderBanner($format) {
		return $this->Parent->renderBanner($format);
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
