<?php namespace RAL;
class Continuity {
	/* SQL Data */
	public $Name;
	public $PostCount;
	public $Description;

	private $ral;
	private $years;

	public function __construct($row, $ral, $doChildren = true) {
		$this->Name = $row['Name'];
		@$this->Description = $row['Description'];
		@$this->PostCount = intval($row['Post Count']);
		$this->ral = $ral;
		if ($doChildren)
			$this->years = $ral->Years($this->Name);
		return $this;
	}

	/* Methods for accessing the elitist superstructure */
	public function Children() { return $this->years; }

	/* Lazy... */
	public function Type() { return "Continuity"; }

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
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/" .
			rawurlencode($this->Name) .
			"?compose";
		else return "{$WROOT}view.php" .
			"?continuity=" . urlencode($this->Name) .
			"&compose";
	}

	/* Just a cute title */
	function Title() {
		return "[{$this->Name}]";
	}

	function Description() {
		return $this->Description;
	}
	/* Miscellaneous resources */
	public function BannerURL() {
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

	/* Just for the admin panel :P */
	public function create() {
		$dbh = $this->ral->getdb();
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
		$dbh = $this->ral->getdb();
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
