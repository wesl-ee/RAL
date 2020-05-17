<?php namespace RAL;
class Reply {
	public $Id;
	public $Continuity;
	public $Year;
	public $Content;
	public $Created;
	public $Topic;
	private $User;
	public $UserIdentity;

	private $Parent;
	private $ral;
	private $Children;

	const TYPE = 'Reply';

	function __construct($row, $ral = false, $doChildren = true) {
		$this->Id = intval($row['Id']);
		$this->Continuity = $row['Continuity'];
		$this->Year = intval($row['Year']);
		$this->Topic = intval($row['Topic']);
		$this->Content = $row['Content'];
		$this->Created = $row['Created'];
		$this->User = $row['User'];
		$this->UserIdentity = $row['UserIdentity'];

		$this->ral = $ral;
		if ($doChildren)
			$this->Children = $ral->Posts($this->Continuity, $this->Year, $this->Topic);
	}

	/* Methods for accessing the elitist superstructure */
	public function Children() { return $this->Children; }
	public function Type() { return static::TYPE; }

	public function isTopic() { return $this->Id == 1; }

	public function Description() {
		return $this->Content;
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

	public function BannerURL() {
		return CONFIG_WEBROOT
		. "continuities/{$this->Continuity}/banner.gif";
	}

	public function markLearned($rm, $category) {
		$dbh = $rm->getdb();

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
	public function unmarkLearned($rm) {
		$dbh = $rm->getdb();

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
	public function b8GuessWasCorrect($rm, $category) {
		$this->markLearned($rm, $category);
	}
	public function learn($rm, $category) {
		if (!($category == \b8::SPAM || $category == \b8::HAM))
			return false;

		$this->markLearned($rm, $category);

		$b8 = $rm->getb8();

		$b8->learn($rm->asHtml(
			$this->Content
			), $category);
		$b8->sync();
	}
	public function unlearn($rm) {
		if (!($this->unmarkLearned())) return false;

		$b8 = $rm->getb8();

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
