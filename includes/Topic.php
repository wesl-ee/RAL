<?php namespace RAL;
class Topic {
	/* SQL Data */
	public $Id;
	public $Created;
	public $Continuity;
	public $Content;
	public $Replies;
	public $Year;
	public $Deleted;
	private $User;

	private $Parent;

	public function __construct($row, $parent) {
		$this->Id = $row['Id'];
		$this->Created = $row['Created'];
		$this->Continuity = $row['Continuity'];
		$this->Content = $row['Content'];
		$this->Replies = $row['Replies'];
		$this->Year = $row['Year'];
		$this->Deleted = $row['Deleted'];
		$this->User = $row['User'];

		$this->Parent = $parent;
		return $this;
	}
	/* Methods for accessing the elitist superstructure */
	public function Rm() { return $this->Parent->Rm(); }
	public function Parent() { return $this->Parent; }
	/* Methods for rendering as HTML, text, etc. */
	public function renderAsHtml() {
		$content = $this->getContentAsHtml();
		if (isset($this->Deleted))
			$content = $this->asHtml($this->deletedText());

		$href = htmlentities($this->resolve());
		$time = strtotime($this->Created);
		$prettydate = date('l M jS \'y', $time);
		$datetime = date(DATE_W3C, $time);
		if (isset($this->Deleted)) print <<<HTML
<section class="post deleted">
	<strong>(Trashed)</strong>
	<h3 class=id>{$this->title()}</h3>

HTML;
		else print <<<HTML
<section class=post>
	<h3 class=id>{$this->title()}</h3>

HTML;
		print <<<HTML
	<time datetime="$datetime">$prettydate</time><br />
	<span class=expand>
		<a href="$href">Read Topic ($this->Replies Posts)</a>
	</span><hr />
	{$content}
</section>

HTML;
	}

	public function renderHeader() { return $this->Parent->renderHeader(); }
	public function renderAsText() {
		$content = $this->getContentAsText();
		if (isset($this->Deleted))
			$content = $this->asText($this->deletedText());
		print <<<TEXT
$this->Id. ($this->Created)
$content

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
	/* Rendering an array of objects */
	public function renderSelection($items, $format) {
		switch ($format) {
		case 'html':
			print <<<HTML
<article>
<h2>
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
		break; case 'sitemap':
			foreach ($items as $i) $i->renderAsSitemap();
		break; }
	}
	/* For HTML purposes, returns a URL to the current object */
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . '/'
			. rawurlencode($this->Year) . '/'
			. rawurlencode($this->Id);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Id);
	}
	public function resolveComposer() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}composer/"
			. rawurlencode($this->Continuity) . '/'
			. rawurlencode($this->Year) . '/'
			. rawurlencode($this->Id);
		else return "{$WROOT}composer.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Id);
	}
	/* Just a cute title */
	function title() {
		return "[{$this->Continuity}/{$this->Year}/"
		. "{$this->Id}]";
	}
	function description() {
		return $this->Content;
	}
	/* There are no special rules for topic banners */
	public function renderBanner($format) {
		return $this->Parent()->renderBanner($format);
	}
	/* Parsing BBCode from the topic's content */
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
	/* Rendering the new post composer */
	public function renderPostButton() {
		$href = $this->resolveComposer();
		print <<<HTML
		<nav class="info-links right">
		<a class=button href="$href">Reply to Topic</a>
		</nav>

HTML;
	}
	public function renderBreadcrumb($position) {
		$position = $this->Parent()->renderBreadcrumb($position);

		$href = $this->resolve();
		$name = $this->Id;
		print <<<BREAD
	<li property=itemListElement typeof=ListItem class=button>
		<a href="$href" property=item typeof=WebPage>
		<span property=name>$name</span></a>
		<meta property=position content=$position />
	</li>
BREAD;
		return 1+$position;
	}
	public function renderComposer($content = '') {
		$WROOT = CONFIG_WEBROOT;
		$action = htmlentities($this->resolveComposer());
		$cancel = htmlentities($this->resolve());
		if (CONFIG_CLEAN_URL) $bbcoderef = "{$WROOT}bbcode-help";
		else $bbcoderef = "{$WROOT}bbcode-help.php";
		print <<<HTML
		<h2>Reply to {$this->title()}</h2>
		<form method=POST action="$action" class=composer>
		<div class=textarea>
			<textarea autofocus rows=5 tabindex=1
			maxlength=5000
			placeholder="Contribute your thoughts and desires..."
			name=content>$content</textarea>
		</div>
		<div class=buttons>
			<a href="$cancel" class="cancel button">Cancel</a>
			<button value=preview name=preview
			tabindex=2 class=button
			type=submit>Next</button>
			<a href="$bbcoderef">Using BBCode</a>
		</div>
		</form>

HTML;
	}
	public function renderRobocheck($content = '') {
		$action = htmlentities($this->resolveComposer());
		$cancel = htmlentities($this->resolve());
		$title = "[$this->Name]";

		$reply = new PreviewPost($content, $this);
		$content = htmlspecialchars($content);

		$robocheck = gen_robocheck();
		$robosrc = $robocheck['src'];
		$robocode = $robocheck['id'];
		$height = $robocheck['height'];
		$width = $robocheck['width'];

		print <<<HTML
		<h2>Double Check</h2>
		<p>Before you post, please verify that everything is as you
		intend. If the preview looks okay, continue by verifying your
		humanity and submitting your post.</p>

HTML;

		$reply->renderAsHtml();
		print <<<HTML
		<form method=POST action="$action" class=composer>
		<input type=hidden name=content value="$content">
		<div class=robocheck>
			<img height=$height width=$width src="$robosrc">
			<input name=robocheckid type=hidden value=$robocode>
			<input name=robocheckanswer
			tabindex=1
			placeholder="Verify Humanity"
			autocomplete=off>
		<div class="buttons center">
			<a href="$cancel" class="cancel button">Cancel</a>
			<button name=post value=post type=submit
			tabindex=2>Post</button>
		</div></div></form>

HTML;
	}
	public function post($content, $id) {
		$dbh = $this->Rm()->getdb();

		$query = <<<SQL
		INSERT INTO `Replies`
		(`Id`, `Continuity`, `Year`, `Topic`, `Content`, `User`) SELECT
		COUNT(*)+1 AS `Id`,
		? AS `Continuity`,
		? AS `Year`,
		? AS `Topic`,
		? AS `Content`,
		? AS `User`
		FROM `Replies` WHERE Continuity=?
		AND YEAR=? AND Topic=? 
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('siisssii',
			$this->Continuity,
			$this->Year,
			$this->Id,
			$content,
			$id,
			$this->Continuity,
			$this->Year,
			$this->Id);
		$stmt->execute();

		$query = <<<SQL
		UPDATE `Continuities` SET `Post Count`=`Post Count`+1
		WHERE `Name`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $this->Continuity);
		$stmt->execute();

		$query = <<<SQL
		UPDATE `Topics` SET `Replies`=`Replies`+1
		WHERE `Continuity`=? AND `Year`=? AND `Id`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('sii', $this->Continuity, $this->Year, $this->Id);
		$stmt->execute();
	}
	public function deletedText() {
		$length = strlen($this->Content);
		$md5 = md5($this->Content);
		$sha1 = sha1($this->Content);
		return <<<TEXT
This topic is no longer here; it broke one of the two rules and was deleted :(

MD5: $md5
SHA1: $sha1
Message Length: $length characters
TEXT;
	}
	public function delete() {
		$dbh = $this->Rm()->getdb();

		$query = <<<SQL
		UPDATE `Topics` SET `Deleted`=1 WHERE
		`Continuity` = ? AND `Year` = ? AND `Id` = ?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('sii', $this->Continuity, $this->Year, $this->Id);
		$stmt->execute();
	}
}
