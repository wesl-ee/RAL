<?php namespace RAL;
class Reply {
	public $Id;
	public $Continuity;
	public $Year;
	public $Content;
	public $Created;

	private $Parent;

	function __construct($row, $parent = null) {
		$this->Id = $row['Id'];
		$this->Continuity = $row['Continuity'];
		$this->Year = $row['Year'];
		$this->Topic = $row['Topic'];
		$this->Content = $row['Content'];
		$this->Created = $row['Created'];

		$this->Parent = $parent;
	}
	/* Methods for accessing the elitist superstructure */
	public function Rm() { return $this->Parent->Rm(); }
	public function Parent() { return $this->Parent; }
	public function setParent($p) { $this->Parent = $p; }
	/* Methods for rendering a reply as HTML, RSS, etc. */
	public function renderAsHtml() {
		$content = $this->getContentAsHtml();
		$href = htmlentities($this->resolve(), ENT_QUOTES);
		$time = strtotime($this->Created);
		$prettydate = date('l M jS \'y', $time);
		$datetime = date(DATE_W3C, $time);
		print <<<HTML
<section class=post id=$this->Id>
	<h2><a class=id href="$href">$this->Id.</a></h2>
	<time datetime="$datetime">$prettydate</time>
	<hr />
	{$content}
</section>

HTML;
	}
	public function renderAsText() {
		$content = $this->getContentAsText();
		print <<<TEXT
$this->Id. ($this->Created)
$content

TEXT;
	}
	public function renderAsRss() {
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
	/* Rendering an array of Replies */
	public function renderSelection($items, $format) {
		switch($format) {
		case 'html':
			print <<<HTML
<article><h2>
	{$this->Parent->title()}
</h2><div class=content>

HTML;
			foreach ($items as $i) $i->renderAsHtml();
			say('</div></article>');
		break; case 'text':
			foreach ($items as $i) $i->renderAsText();
		break; case 'json':
			print json_encode($items);
		}
	}
	/* For HTML purposes, returns a URL to the current object */
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . '/'
			. rawurlencode($this->Year) . '/'
			. rawurlencode($this->Topic) . '/'
			. rawurlencode($this->Id);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Topic)
			. "&post=" . urlencode($this->Id);
	}
	/* Just a cute title */
	function title() {
		return "[{$this->Continuity}/{$this->Year}/"
		. "{$this->Topic}/{$this->Id}]";
	}
	/* There are no special banners for Topics */
	public function renderBanner($format) {
		return $this->Parent->renderBanner($format);
	}
	/* Parse the BBCode of the content */
	public function getContentAsHtml() {
		$bbparser = $this->Rm()->getbbparser();
		$visitor = $this->Rm()->getLineBreakVisitor();
		$bbparser->parse(htmlentities($this->Content));
		$bbparser->accept($visitor);
		return $bbparser->getAsHtml();
	}
	public function getContentAsText() {
		$bbparser = $this->Rm()->getbbparser();
		$bbparser->parse($this->Content);
		return $bbparser->getAsText();
	}
}
