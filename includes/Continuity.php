<?php namespace RAL;
class Continuity {
	/* SQL Data */
	public $Name;
	public $PostCount;
	public $Description;

	public function __construct($row) {
		$this->Name = $row['Name'];
		$this->PostCount = $row['Post Count'];
		$this->Description = $row['Description'];
		return $this;
	}
	public function render() {
		$href = $this->resolve();
		$src = $this->getBanner();
		$alt = $this->Name;
		$desc = $this->Description;
		$title = "[{$this->Name}]";
		print <<<HTML
	<article class=continuity-splash>
		<div class=banner>
		<a href="$href">
			<img height=150 width=380
			title="$title" alt="$alt"
			src="$src" />
		</a>
		</div>
		<span class=title>
			$title
		</span><br />
		<span class=description>
			$desc
		</span>
	</article>

HTML;
	}
	public function renderSelection($items) {
		print <<<HTML
	<main class=continuity-splashes>
HTML;
		foreach ($items as $i) $i->render();
		print <<<HTML
	</main>
HTML;
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
	public function getBanner() {
		return CONFIG_WEBROOT
		. "continuities/{$this->Name}/banner.gif";
	}
	public function getTheme() {
		return CONFIG_WEBROOT
		. "continuities/{$this->Name}/theme.css";
	}

	public function getAsListItem() {
		return [
			'Name' => $this->Name,
			'Description' => $this->Description,
			'Post Count' => $this->PostCount,
			'URL' => $this->resolve(),
			'Banner' => $this->getBanner()
		];
	}

	/* HTML Output */
	public function renderBanner() {
		$href = $this->resolve();
		$src = $this->getBanner();
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
	public function drawSplash() {
		$href = $this->resolve();
		$src = $this->getBanner();
		$alt = $this->Name;
		$desc = $this->Description;
		$title = $this->Name;
		print <<<HTML
	<article class=continuity-splash>
		<div class=banner>
		<a href="$href">
			<img height=150 width=380
			title="$title" alt="$alt"
			src="$src" />
		</a>
		</div>
		<span class=description>
			$desc
		</span>
	</article>

HTML;
	}
	public function drawPostButton() {
		$href = $this->resolveComposer();
		print <<<HTML
		<nav class=info-links>
		<a class=post-button href="$href">Create a Topic</a>
		</nav>

HTML;
	}
	public function drawComposer() {
		$action = $this->resolveComposer();
		$cancel = $this->resolve();
		$title = "[$this->Name]";

		$robocheck = gen_robocheck();
		$robosrc = $robocheck['src'];
		$robocode = $robocheck['id'];
		$height = $robocheck['height'];
		$width = $robocheck['width'];
		print <<<HTML
		<header>$title<br/>New Topic</header>
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
	public function drawContent() {
		$ROOT = CONFIG_LOCALROOT;
		if (isset($this->topic)) {
			print $this->topic;
		} if (isset ($this->year)) {
			include "{$ROOT}template/ContinuityYear.php";
		} else {
			include "{$ROOT}template/ContinuityOverview.php";
		}
	}
	public function post($content) {
		$year = date('Y');
		$dbh = $GLOBALS['RM']->getdb();

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
		print $query;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('sissi', $this->Name, $year, $content, $this->Name, $year);
		$stmt->execute();

		$query = <<<SQL
		UPDATE `Continuities` SET `Post Count`=`Post Count`+1
		WHERE `Name`=?
SQL;
		print $query;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $this->Name);
		$stmt->execute();
	}
}