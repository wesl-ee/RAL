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
		$this->Deleted = $row['Deleted'];

		$this->Parent = $parent;
	}
	/* Methods for accessing the elitist superstructure */
	public function Rm() { return $this->Parent->Rm(); }
	public function Parent() { return $this->Parent; }
	public function setParent($p) { $this->Parent = $p; }
	/* Methods for rendering a reply as HTML, RSS, etc. */
	public function renderAsHtml() {
		$content = $this->getContentAsHtml();
		if (isset($this->Deleted))
			$content = $this->asHtml($this->deletedText());
		$href = htmlentities($this->resolve(), ENT_QUOTES);
		$time = strtotime($this->Created);
		$prettydate = date('l M jS \'y', $time);
		$datetime = date(DATE_W3C, $time);
		if (isset($this->Deleted))
		print <<<HTML
<section class="post deleted" id=$this->Id>
	<strong>(Trashed)</strong>
	<h2><a class=id href="$href">$this->Id.</a></h2>

HTML;
		else print <<<HTML
<section class=post id=$this->Id>
	<h2><a class=id href="$href">$this->Id.</a></h2>

HTML;
		print <<<HTML
	<time datetime="$datetime">$prettydate</time>
	<hr />
	{$content}
</section>

HTML;
	}
	public function renderAsText() {
		$content = $this->getContentAsText();
		if (isset($this->Deleted))
			$content = $this->asText($this->deletedText());
		print <<<TEXT
$this->Id. ($this->Created)
$content

TEXT;
	}
	public function renderHeader() { return $this->Parent->renderHeader(); }
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
</h2>

HTML;
			$this->Parent->renderPostButton();
			print <<<HTML
<div class=content>

HTML;
			foreach ($items as $i) $i->renderAsHtml();
			print <<<HTML
</div>

HTML;
			$this->Parent->renderPostButton();
			print <<<HTML

</article>
HTML;
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
	public function asHtml($content) {
		$bbparser = $this->Rm()->getbbparser();
		$visitor = $this->Rm()->getLineBreakVisitor();
		$bbparser->parse(htmlentities($content));
		$bbparser->accept($visitor);
		return $bbparser->getAsHtml();
	}
	public function getContentAsText() {
		$bbparser = $this->Rm()->getbbparser();
		$bbparser->parse($this->Content);
		return $bbparser->getAsText();
	}
	public function asText($content) {
		$bbparser = $this->Rm()->getbbparser();
		$bbparser->parse($content);
		return $bbparser->getAsText();
	}
	public function deletedText() {
		$length = strlen($this->Content);
		$md5 = md5($this->Content);
		$sha1 = sha1($this->Content);
		return <<<TEXT
This post is no longer here; it broke one of the two rules and was deleted :(

MD5: $md5
SHA1: $sha1
Message Length: $length characters
TEXT;
	}
	public function delete() {
		$dbh = $this->Rm()->getdb();

		$query = <<<SQL
		UPDATE `Replies` SET `Deleted`=1 WHERE
		`Continuity` = ? AND `Year` = ? AND `Topic` = ?
		AND `Id`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('siii', $this->Continuity, $this->Year, $this->Topic, $this->Id);
		$stmt->execute();
	}
}
