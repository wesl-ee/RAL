<?php namespace RAL;
class Topic extends Reply {
	public $Replies;
	const TYPE = "Topic";

	function __construct($row, $ral = false, $doChildren = true) {
		parent::__construct($row, $ral, $doChildren);
		$this->Replies = $row['Replies'];
	}

	/* Just a cute title */
	function title() {
		return "[{$this->Continuity}/{$this->Year}/"
		. "{$this->Topic}]";
	}

	/* For HTML purposes, returns a URL to the current object */
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . '/'
			. rawurlencode($this->Year) . '/'
			. rawurlencode($this->Topic);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Topic);
	}

	public function resolveComposer() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) .
			'/'	. rawurlencode($this->Year) .
			'/' . rawurlencode($this->Topic) .
			'?compose';
		else return "{$WROOT}composer.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Topic)
			. "&compose";
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
