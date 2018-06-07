<?php namespace RAL;
class Continuity {
	/* SQL Data */
	public $Name;
	public $PostCount;
	public $Description;

	private $Parent;
	private $RM;

	public function __construct($row, $parent = null) {
		$this->Name = $row['Name'];
		$this->Description = $row['Description'];
		@$this->PostCount = $row['Post Count'];

		$this->Parent = $parent;
		return $this;
	}
	public function getRM() { return $this->Parent->getRM(); }
	public function renderAsHtml() {
		$href = $this->resolve();
		$src = $this->getBannerImage();
		$alt = $this->Name;
		$desc = $this->Description;
		$title = "[{$this->Name}]";
		print <<<HTML
	<section class=continuity-splash>
		<div class=banner>
		<a href="$href">
			<img height=150 width=380
			title="$title" alt="$alt"
			src="$src" />
		</a>
		</div>
		<h2 class=title>
			$title
		</h2>
		<span class=description>
			$desc
		</span>
	</section>

HTML;
	}
	function renderAsText() {
		print <<<TEXT
$this->Name ($this->Description)

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
	<article>
	<h2>Continuities</h2><div class=continuity-splashes>
HTML;
			foreach ($items as $i) $i->renderAsHtml();
			say('</div></article>');
		break; case 'text':
			foreach ($items as $i) $i->renderAsText();
		break; case 'sitemap':
			foreach ($items as $i) $i->renderAsSitemap();
		break; case 'json':
			print json_encode($items);
		}
	}
	public function renderSelectionAsText($items) {
		foreach ($items as $i) $i->renderAsText();
	}
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Name);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Name);
	}
	public function resolveComposer() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}composer/"
			. rawurlencode($this->Name);
		else return "{$WROOT}composer.php"
			. "?continuity=" . urlencode($this->Name);
	}
	public function getBannerImage() {
		return CONFIG_WEBROOT
		. "continuities/{$this->Name}/banner.gif";
	}
	public function getBannerTextFile() {
		return CONFIG_LOCALROOT
		. "continuities/{$this->Name}/banner.txt";
	}
	public function getTheme() {
		return CONFIG_WEBROOT
		. "continuities/{$this->Name}/theme.css";
	}
	// TODO: Delete this
	public function getAsListItem() {
		return [
			'Name' => $this->Name,
			'Description' => $this->Description,
			'Post Count' => $this->PostCount,
			'URL' => $this->resolve(),
			'Banner' => $this->getBanner()
		];
	}
	function title() {
		return "[{$this->Name}]";
	}
	public function renderBannerAsHtml() {
		$href = $this->resolve();
		$src = $this->getBannerImage();
		$alt = $this->Name;
		$title = $this->Name;
		print <<<HTML
	<div class=banner><a href="$href">
		<img height=150 width=380
		src="$src"
		title="$title"
		alt="$alt"/>
	</a></div>

HTML;
	}
	public function renderBannerAsText() {
		readfile($this->getBannerTextFile);
	}
	public function renderBanner($format) {
		switch ($format) {
		case 'html': $this->renderBannerAsHtml(); break;
		case 'text': $this->renderBannerAsText();
		break; }
	}
	public function renderPostButton() {
		$href = htmlentities($this->resolveComposer());
		print <<<HTML
		<nav class="info-links right">
		<a class=button href="$href">New Topic</a>
		</nav>

HTML;
	}
	public function renderComposer($content = '') {
		$action = htmlentities($this->resolveComposer());
		$cancel = htmlentities($this->resolve());

		print <<<HTML
		<h2>New topic on {$this->title()}</h2>
		<form method=POST action="$action" class=composer>

		<div class=textarea>
			<textarea autofocus rows=5 tabindex=1
			maxlength=5000
			placeholder="Contribute your thoughts and desires..."
			name=content>$content</textarea>
		<div class=bbcode-help>
		<header>RAL BBCode Reference</header><ul>
			<li>[aa]</li>
			<li>[b]</li>
			<li>[i]</li>
			<li>[em]</li>
			<li>[url]</li>
			<li>[url=<em>url</em>]</li>
			<li>[color=<em>Color</em>]</li>
			<li>[spoiler]</li>
			<li>[quote]</li>
		</ul>
		<footer>
			<a href=http://www.bbcode.org>What is this?</a>
		</footer>
		</div></div>
		<div class=buttons>
			<a href="$cancel" class="cancel">Cancel</a>
			<button value=preview name=preview
			tabindex=2
			type=submit>Next</button>
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
			<a href="$cancel" class="cancel">Cancel</a>
			<button name=post value=post type=submit
			tabindex=2>Post</button>
		</div></div></form>

HTML;
	}
	public function post($content) {
		$year = date('Y');
		$dbh = $this->getRM()->getdb();

		$query = <<<SQL
		INSERT INTO `Topics`
		(`Id`, `Continuity`, `Year`, `Content`) SELECT
		COUNT(*)+1 AS `Id`,
		? AS `Continuity`,
		? AS `Year`,
		? AS `Content`
		FROM `Topics` WHERE Continuity=?
		AND YEAR=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('sissi', $this->Name, $year, $content, $this->Name, $year);
		$stmt->execute();

		$query = <<<SQL
		UPDATE `Continuities` SET `Post Count`=`Post Count`+1
		WHERE `Name`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $this->Name);
		$stmt->execute();
	}
	public function create() {
		$dbh = $this->getRM()->getdb();
		$query = <<<SQL
		INSERT INTO `Continuities`
		(`Name`, `Description`) VALUES
		(?, ?)
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('ss', $this->Name, $this->Description);
		$stmt->execute();
	}
}
