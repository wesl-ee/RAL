<?php namespace RAL;
class Continuity {
	/* SQL Data */
	public $Name;
	public $PostCount;
	public $Description;

	private $Parent;

	public function __construct($row, $parent = null) {
		$this->Name = $row['Name'];
		@$this->Description = $row['Description'];
		@$this->PostCount = intval($row['Post Count']);
		$this->Parent = $parent;
		return $this;
	}
	/* Methods for accessing the elitist superstructure */
	public function Parent() { return $this->Parent; }
	public function Rm() { return $this->Parent->Rm(); }

	/* Lazy... */
	public function breadcrumb() { $this->Parent->breadcrumb(); }
	public function renderHeader() {
		print <<<HTML
	<header>
		<div>
HTML;
		$this->renderBannerAsHTML();
		include CONFIG_LOCALROOT . "template/Feelies.php";
		$this->breadcrumb();
		print <<<HTML
		</div>

HTML;
		$this->drawSearchBar();
		print <<<HTML
	</header>

HTML;
	}
	public function drawSearchBar() { $this->Parent->drawSearchBar(); }

	/* Methods for rendering a Continuity as HTML, text, etc. */
	public function renderAsHtml() {
		$href = $this->resolve();
		$src = $this->getBannerImage();
		$alt = $this->Name;
		$desc = $this->Description;
		$title = $this->title();
		print <<<HTML
<section class=continuity-splash>
	<div class=banner><a href="$href">
		<img height=150 width=380
		title="$title" alt="$alt"
		src="$src" />
	</a></div>
	<h2 class=title>
		<a href="$href">$title</a>
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
		$loc = CONFIG_CANON_URL . $this->resolve();
print <<<XML
	<url>
		<loc>$loc</loc>
	</url>

XML;
	}
	/* Rendering an array of Continuities */
	public function renderSelection($items, $format) {
		switch ($format) {
		case 'html':
			print <<<HTML
<article>
<h2>Continuities</h2><div class=continuity-splashes>

HTML;
			foreach ($items as $i) $i->renderAsHtml();
			print <<<HTML
</div></article>

HTML;
		break; case 'text':
			foreach ($items as $i) $i->renderAsText();
		break; case 'sitemap':
			foreach ($items as $i) $i->renderAsSitemap();
		break; case 'json':
			print json_encode($items);
		}
	}
	public function renderBreadcrumb($position) {
		$position = $this->Parent()->renderBreadcrumb($position);
		$href = $this->resolve();
		$name = $this->Name;
		print <<<BREAD
	<li property=itemListElement typeof=ListItem class=button>
		<a href="$href" property=item typeof=WebPage>
		<span property=name>$name</span></a>
		<meta property=position content=$position />
	</li>
BREAD;
		return 1+$position;
	}
	/* Rendering the object's banner */
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
	/* For HTML purposes, returns a URL to the current object */
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
	/* Rendering the composer */
	public function renderPostButton() {
		$href = htmlentities($this->resolveComposer());
		print <<<HTML
		<nav class="info-links right">
		<a class=button href="$href">New Topic</a>
		</nav>

HTML;
	}
	public function renderComposer($content = '') {
		$WROOT = CONFIG_WEBROOT;
		$action = htmlentities($this->resolveComposer());
		$cancel = htmlentities($this->resolve());
		if (CONFIG_CLEAN_URL) $bbcoderef = "{$WROOT}bbcode-help";
		else $bbcoderef = "{$WROOT}bbcode-help.php";
		print <<<HTML
		<h2>New topic on {$this->title()}</h2>
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
			<a href="$cancel" class="button cancel">Cancel</a>
			<button class=button name=post value=post type=submit
			tabindex=2>Next</button>
		</div></div></form>

HTML;
	}
	/* Just a cute title */
	function title() {
		return "[{$this->Name}]";
	}
	function description() {
		return $this->Description;
	}
	/* Miscellaneous resources */
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
	/* Adding to the conversation */
	public function post($content, $id) {
		$year = date('Y');
		$dbh = $this->Rm()->getdb();

		$query = <<<SQL
		INSERT INTO `Topics`
		(`Id`, `Continuity`, `Year`, `Content`, `User`) SELECT
		COUNT(*)+1 AS `Id`,
		? AS `Continuity`,
		? AS `Year`,
		? AS `Content`,
		? AS `User`
		FROM `Topics` WHERE Continuity=?
		AND YEAR=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('sisssi',
			$this->Name,
			$year,
			$content,
			$id,
			$this->Name,
			$year);
		$stmt->execute();

		$query = <<<SQL
		UPDATE `Continuities` SET `Post Count`=`Post Count`+1
		WHERE `Name`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $this->Name);
		$stmt->execute();
	}
	/* Just for the admin panel :P */
	public function create() {
		$dbh = $this->RM()->getdb();
		$query = <<<SQL
		INSERT INTO `Continuities`
		(`Name`, `Description`) VALUES
		(?, ?)
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('ss', $this->Name, $this->Description);
		$stmt->execute();
	}
	public function destroy() {
		$dbh = $this->RM()->getdb();
		$query = <<<SQL
		DELETE FROM `Continuities` WHERE `Name`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $this->Name);
		$stmt->execute();

		$query = <<<SQL
		DELETE FROM `Topics` WHERE `Continuity`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $this->Name);
		$stmt->execute();

		$query = <<<SQL
		DELETE FROM `Replies` WHERE `Continuity`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $this->Name);
		$stmt->execute();
	}
}
