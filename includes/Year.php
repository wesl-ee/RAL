<?php namespace RAL;
class Year {
	public $Year;
	public $Continuity;
	public $Count;

	private $Parent;

	public function __construct($row, $parent = null) {
		$this->Year = $row['Year'];
		$this->Continuity = $row['Continuity'];
		$this->Count = $row['Count'];
		$this->Parent = $parent;
		return $this;
	}
	public function parent() {
		return $this->Parent;
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
	public function Rm() {
		return $this->Parent->Rm();
	}
	public function renderBreadcrumb($position) {
		$position = $this->Parent()->renderBreadcrumb($position);
		$href = $this->resolve();
		$name = $this->Year;
		print <<<BREAD
	<li property=itemListElement typeof=ListItem class=button>
		<a href="$href" property=item typeof=WebPage>
		<span property=name>$name</span></a>
		<meta property=position content=$position />
	</li>
BREAD;
		return 1+$position;
	}
	public function renderAsHtml() {
		$href = htmlentities($this->resolve());
		print <<<HTML
	<li><div>
		<time><a href="$href">$this->Year</a></time><br />
		$this->Count Topics
	</div></li>
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
	<article class=timeline>
	<h2>Overview of {$this->Parent->title()}</h2><ul>

HTML;
			foreach ($items as $i) $i->renderAsHtml();
			print <<<HTML
	</ul></article>

HTML;
		break; case 'text':
			foreach ($items as $i) $i->renderAsText();
		break; case 'sitemap':
			foreach ($items as $i) $i->renderAsSitemap();
		break; case 'json':
			print json_encode($items);
		}
	}
	public function title() {
		return "[{$this->Continuity}/{$this->Year}]";
	}
	public function description() {
		return $this->Parent->description()
		. " in the year $this->Year";
	}
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . "/"
			. rawurlencode($this->Year);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year);
	}
}
