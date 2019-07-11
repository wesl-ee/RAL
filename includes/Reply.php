<?php namespace RAL;
class Reply {
	public $Id;
	public $Continuity;
	public $Year;
	public $Content;
	public $Created;
	private $User;

	private $Parent;

	function __construct($row, $parent = null) {
		$this->Id = intval($row['Id']);
		$this->Continuity = $row['Continuity'];
		$this->Year = intval($row['Year']);
		$this->Topic = intval($row['Topic']);
		$this->Content = $row['Content'];
		$this->Created = $row['Created'];
		$this->Deleted = (bool)$row['Deleted'];
		$this->User = $row['User'];

		$this->Parent = $parent;
	}
	/* Methods for accessing the elitist superstructure */
	public function Rm() { return $this->Parent->Rm(); }
	public function Parent() { return $this->Parent; }
	public function setParent($p) { $this->Parent = $p; }
	/* Methods for rendering a reply as HTML, RSS, etc. */
	public function renderAsHtml() {
		$content = $this->Rm()->asHtml(($this->Deleted ?
				$this->deletedText() :
				$this->Content));
		$href = htmlentities($this->resolve(), ENT_QUOTES);
		$time = strtotime($this->Created);
		$prettydate = date('l M jS \'y', $time);
		$datetime = date(DATE_W3C, $time);
		if ($this->Deleted)
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

	public function renderAsText($censor = true) {
		$content = ($this->Deleted && $censor) ?
			$this->deletedText() :
			$this->Rm()->asText($this->Content);
		$out = <<<TEXT
[{$this->title()}] ($this->Created)
$content

TEXT;
		print wordwrap($out, 80, "\n");
	}
	public function renderHeader() { return $this->Parent->renderHeader(); }
	public function renderAsRss() {
		$content = htmlspecialchars(
			$this->Rm()->asText($this->Deleted ?
				$this->deletedText() :
				$this->Content),
			ENT_COMPAT,'utf-8');
		$url = CONFIG_CANON_URL . htmlentities($this->resolve());
		$title = $this->title();
		$date = date(DATE_RSS, strtotime($this->Created));
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
	public function InfoText() {
		print <<<TXT
{$this->title()} ($this->Created) (Author: $this->User)

TXT;
		if ($this->Deleted) print <<<TXT
(TRASHED)

TXT;
	}
	public function markLearned($category) {
		$dbh = $this->Rm()->getdb();

		$query = <<<SQL
		UPDATE `Replies` SET `LearnedAsSpam`=?, `IsSpam`=?
		WHERE `Continuity`=? AND `Year`=? AND `Topic`=?
		AND `Id`=?
SQL;
		$stmt = $dbh->prepare($query);
		$isSpam = ($category == \b8::SPAM);

		$stmt->bind_param('iisiii', $isSpam,
			$isSpam,
			$this->Continuity,
			$this->Year,
			$this->Topic,
			$this->Id);
		$stmt->execute();
	}
	public function unmarkLearned() {
		$dbh = $this->Rm()->getdb();

		$query = <<<SQL
		SELECT 1 FROM `Replies` WHERE `Continuity`=?
		AND `Year`=? AND `Topic`=? AND Id=? AND
		`LearnedAsSpam` IS NOT NULL
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('siii', $this->Continuity,
			$this->Year,
			$this->Topic,
			$this->Id);
		$stmt->execute();
		$stmt->store_result();
		if (!($stmt->num_rows)) return false;

		$query = <<<SQL
		UPDATE `Replies` SET `LearnedAsSpam`=NULL AND
		`IsSpam`=NULL WHERE
		`Continuity`=? AND `Year`=? AND `Topic`=?
		AND `Id`=?
SQL;
		$stmt = $dbh->prepare($query);

		$stmt->bind_param('siii', $this->Continuity,
			$this->Year,
			$this->Topic,
			$this->Id);
		$stmt->execute();
		return true;
	}
	public function b8GuessWasCorrect($category) {
		$this->markLearned($category);
	}
	public function learn($category) {
		if (!($category == \b8::SPAM || $category == \b8::HAM))
			return false;

		$this->markLearned($category);

		$b8 = $this->Rm()->getb8();

		$b8->learn($this->Rm()->asHtml(
			$this->Content
			), $category);
		$b8->sync();
	}
	public function unlearn() {
		if (!($this->unmarkLearned())) return false;

		$b8 = $this->Rm()->getb8();

		$b8->unlearn($this->Rm()->asHtml(
			$this->Content
			));
		$b8->sync();
		return true;
	}
	public function delete() {
		$dbh = $this->Rm()->getdb();

		if ($this->Id == 1)
			$this->Parent->delete();

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
