<?php namespace RAL;
class Continuity {
	/* SQL Data */
	public $Name;
	public $PostCount;
	public $Description;

	/* State */
	public $year;
	public $topic;

	public $Posts;

	public function __construct($row) {
		$this->Name = $row['Name'];
		$this->PostCount = $row['Post Count'];
		$this->Description = $row['Description'];
		return $this;
	}
	public function getYears($url = true) {
		$dbh = $GLOBALS['RM']->getdb();
		$ret = [];
		$query = <<<SQL
			SELECT `Year`, COUNT(*) AS Topics FROM `Topics`
			WHERE `Continuity`=? GROUP BY `Year` ORDER BY `Year`
			DESC
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $this->Name);

		$stmt->execute();
		$res = $stmt->get_result();
		$oldyear = $this->year;
		while ($row = $res->fetch_assoc()) {
			$this->year = $row['Year'];
			$this->Posts = $row['Topics'];
			$ret[] = [
				'Year' => $this->year,
				'URL' => $this->resolve(),
				'Topics' => $this->Posts,
			];
		}
		$this->year = $oldyear;
		return $ret;
	}
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if ($this->year)
		if ($this->topic)
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Name)
			. rawurlencode($this->year) . "/"
			. rawurlencode($this->Topic);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Name)
			. "&year=" .  urlencode($this->year)
			. "&topic=" . urlencode($this->Topic);
		elseif (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Name) . "/"
			. rawurlencode($this->year);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Name)
			. "&year=" . urlencode($this->year);
		elseif (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Name);
		else return "{$WROOT}view.php"
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
	public function drawBanner() {
		$href = $this->resolve();
		$src = $this->getBanner();
		$alt = $this->Name;
		print <<<HTML
	<div class=banner><a href="$href">
		<img height=150 width=380
		src="$src"
		alt="$alt"/>
	</a></div>

HTML;
	}
	public function drawSplash() {
		$href = $this->resolve();
		$src = $this->getBanner();
		$alt = $this->Name;
		$desc = $this->Description;
		print <<<HTML
	<article class=continuity-splash>
		<div class=banner>
		<a href="$href">
			<img height=150 width=380
			src="$src" alt="$alt"/>
		</a>
		</div>
		<span class=description>
			$desc
		</span>
	</article>

HTML;
	}
	public function drawContent() {
		$ROOT = CONFIG_LOCALROOT;
		if (isset($this->topic)) {
			print $this->topic;
		} if (isset ($this->year)) {
			print $this->year;
		} else {
			include "{$ROOT}template/ContinuityOverview.php";
		}
	}
}
