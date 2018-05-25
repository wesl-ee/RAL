<?php namespace RAL;
class Topic {
	/* SQL Data */
	public $Id;
	public $Created;
	public $Continuity;
	public $Content;
	public $Replies;
	public $Year;

	public $Parent;

	public function __construct($row, $parent) {
		$this->Id = $row['Id'];
		$this->Created = $row['Created'];
		$this->Continuity = $row['Continuity'];
		$this->Content = $row['Content'];
		$this->Replies = $row['Replies'];
		$this->Year = $row['Year'];

		$this->Parent = $parent;
		return $this;
	}
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
	public function render() {
		$content = $this->getContentAsHtml();
		$href = $this->resolve();
		print <<<HTML
	<article class=post>
		<nav>
			<span class=id>[{$this->Continuity}/{$this->Year}/{$this->Id}]</span>
			<date>$this->Created</date>
			<a href="$href" class=expand>Expand Topic</a>
		</nav><hr />
		{$content}
	</article>

HTML;
	}
	public function renderSelection($items) {
		print <<<HTML
	<main class=flex>
HTML;
		foreach ($items as $i) $i->render();
		print <<<HTML
	</main>
HTML;
	}
	public function renderBanner() {
		return $this->Parent->renderBanner();
	}
	function title() {
		return "[{$this->Continuity}/{$this->Year}/"
		. "{$this->Id}]";
	}
	public function renderPostButton() {
		$href = $this->resolveComposer();
		print <<<HTML
		<nav class=info-links>
		<a class=post-button href="$href">Reply</a>
		</nav>

HTML;
	}
	public function getContentAsHtml() {
		$bbparser = $GLOBALS['RM']->getbbparser();
		$visitor = $GLOBALS['RM']->getLineBreakVisitor();
		$bbparser->parse(htmlentities($this->Content));
		$bbparser->accept($visitor);
		return $bbparser->getAsHtml();
	}
	public function drawComposer() {
		$action = $this->resolveComposer();
		$cancel = $this->resolve();
		$title = "[{$this->Continuity} / {$this->Year} / {$this->Id}]";

		$robocheck = gen_robocheck();
		$robosrc = $robocheck['src'];
		$robocode = $robocheck['id'];
		$height = $robocheck['height'];
		$width = $robocheck['width'];
		print <<<HTML
		<header>$title<br/>Reply to Topic</header>
		<form method=POST action=$action class=composer>
		<div class=textarea>
			<textarea autofocus rows=5
			maxlength=5000
			placeholder="Contribute your thoughts and desires..."
			name=content></textarea>
		</div><div class=robocheck>
			<img height=$height width=$width src="$robosrc">
			<input name=robocheckid type=hidden value=$robocode>
			<input name=robocheckanswer
			placeholder="Verify Humanity"
			autocomplete=off>
		</div><div class=buttons>
			<a href="$cancel" class="cancel">Cancel</a>
			<button class type=submit>Post</button>
		</div></form>

HTML;
	}
	public function post($content) {
		$dbh = $GLOBALS['RM']->getdb();

		$query = <<<SQL
		INSERT INTO `Replies`
		(`Id`, `Continuity`, `Year`, `Topic`, `Content`) SELECT
		COUNT(*)+1 AS `Id`,
		? AS `Continuity`,
		? AS `Year`,
		? AS `Topic`,
		? AS `Content`
		FROM `Replies` WHERE Continuity=?
		AND YEAR=? AND Topic=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('siissii', $this->Continuity, $this->Year, $this->Id, $content, $this->Continuity, $this->Year, $this->Id);
		$stmt->execute();

		$query = <<<SQL
		UPDATE `Continuities` SET `Post Count`=`Post Count`+1
		WHERE `Name`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $this->Name);
		$stmt->execute();
	}
}
