<?php namespace RAL;
class Reply {
	public $Id;
	public $Continuity;
	public $Year;
	public $Content;
	public $Created;

	public $Parent;

	function __construct($row, $parent = null) {
		$this->Id = $row['Id'];
		$this->Continuity = $row['Continuity'];
		$this->Year = $row['Year'];
		$this->Topic = $row['Topic'];
		$this->Content = $row['Content'];
		$this->Created = $row['Created'];

		$this->Parent = $parent;
	}
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . '/'
			. rawurlencode($this->Year) . '/'
			. rawurlencode($this->Topic) . '#'
			. rawurlencode($this->Id);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Topic)
			. "#" . urlencode($this->Id);
	}
	function title() {
		return "[{$this->Continuity}/{$this->Year}/"
		. "{$this->Topic}/{$this->Id}]";
	}


	public function renderAsHtml() {
		$content = $this->getContentAsHtml();
		print <<<HTML
	<article class=post>
		<nav>
			<span class=id>$this->Id.</span>
			<date>$this->Created</date>
		</nav><hr />
		{$content}
	</article>

HTML;
	}
	public function renderAsRSS() {
		$content = htmlentities($this->getContentAsText());
		$url = CONFIG_CANON_URL . htmlentities($this->resolve());
		$title = $this->title();
		$date = $this->Created;
		print <<<RSS
<item>
	<title>$title</title>
	<link>$url</link>
	<guid isPermaLink="true">$url</guid>
	<description>$content</description>
	<pubDate>$date</pubDate>
</item>
RSS;

	}
	public function renderSelection($items, $format) {
		switch($format) {
		case 'HTML':
			say('<main class=flex>');
			foreach ($items as $i) $i->renderAsHtml();
			say('</main>');
		break; }
	}
	public function renderBanner() {
		return $this->Parent->renderBanner();
	}
	public function getContentAsHtml() {
		$bbparser = $GLOBALS['RM']->getbbparser();
		$visitor = $GLOBALS['RM']->getLineBreakVisitor();
		$bbparser->parse(htmlentities($this->Content));
		$bbparser->accept($visitor);
		return $bbparser->getAsHtml();
	}
	public function getContentAsText() {
		$bbparser = $GLOBALS['RM']->getbbparser();
		$bbparser->parse($this->Content);
		return $bbparser->getAsText();
	}
}
