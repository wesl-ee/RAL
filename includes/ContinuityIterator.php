<?php namespace RAL;
include 'Continuity.php';
include 'Year.php';
include 'Topic.php';
include 'Reply.php';
class ContinuityIterator {
	// State
	public $Continuity;
	public $Year;
	public $Topic;

	public $Selection = [];

	public function select($continuity = null,
	$year = null,
	$topic = null) {
		$dbh = $GLOBALS['RM']->getdb();

		$this->Selection = [];

		if (!$continuity) {
			$query = <<<SQL

			SELECT `Name`, `Post Count`, `Description` FROM
			`Continuities`
SQL;
			$res = $dbh->query($query);
			while ($row = $res->fetch_assoc()) {
				$this->Selection[] = new Continuity($row);
			}
		} elseif (!$year) {
			$query = <<<SQL
			SELECT `Name`, `Post Count`, `Description` FROM
			`Continuities` WHERE `Name`=?
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('s', $continuity);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			$this->Continuity = new Continuity($row);

			$query = <<<SQL
			SELECT `Continuity`, `Year`, COUNT(*) AS Count FROM
			`Topics` WHERE `Continuity`=? GROUP BY `Year` ORDER BY
			`Year` DESC
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('s', $continuity);
			$stmt->execute();
			$res = $stmt->get_result();
			while ($row = $res->fetch_assoc()) {
				$this->Selection[] = new Year($row);
			}
		} elseif (!$topic) {
			$query = <<<SQL
			SELECT `Name`, `Post Count`, `Description` FROM
			`Continuities` WHERE `Name`=?
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('s', $continuity);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			$this->Continuity = new Continuity($row);

			$query = <<<SQL
			SELECT `Continuity`, `Year`, COUNT(*) AS Count FROM
			`Topics` WHERE `Continuity`=? AND `Year`=? GROUP BY
			`Year` ORDER BY `Year` DESC
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('si', $continuity, $year);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			$this->Year = new Year($row);

			$query = <<<SQL
			SELECT `Id`, `Created`, `Continuity`, `Content`
			, `Replies`, `Year` FROM `Topics` WHERE `Continuity`=?
			AND `Year`=? ORDER BY `Created` DESC
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('si', $continuity, $year);
			$stmt->execute();
			$res = $stmt->get_result();
			while ($row = $res->fetch_assoc()) {
				$this->Selection[] = new Topic($row);
			}
		} else {
			$query = <<<SQL
			SELECT `Name`, `Post Count`, `Description` FROM
			`Continuities` WHERE `Name`=?
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('s', $continuity);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			$this->Continuity = new Continuity($row);

			$query = <<<SQL
			SELECT `Continuity`, `Year`, COUNT(*) AS Count FROM
			`Topics` WHERE `Continuity`=? AND `Year`=? GROUP BY
			`Year` ORDER BY `Year` DESC
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('si', $continuity, $year);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			$this->Year = new Year($row);

			$query = <<<SQL
			SELECT `Id`, `Created`, `Continuity`, `Content`
			, `Replies`, `Year` FROM `Topics` WHERE `Continuity`=?
			AND `Year`=? AND `Id`=? ORDER BY `Created` DESC
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('sii', $continuity, $year, $topic);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			$this->Topic = new Topic($row);

			$query = <<<SQL
			SELECT `Id`, `Continuity`, `Topic`, `Content`
			, `Created`, `Year` FROM `Replies`
			WHERE `Continuity`=? AND `YEAR`=? AND `Topic`=?
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('sii', $continuity, $year, $topic);
			$stmt->execute();
			$res = $stmt->get_result();
			while ($row = $res->fetch_assoc()) {
				$this->Selection[] = new Reply($row, $this->Topic);
			}
		}
	}
	public function render() {
		$this->Selection[0]->renderSelection($this->Selection);
	}
	public function renderBanner() {
		if (isset($this->Continuity))
			$this->Continuity->renderBanner();
	}
	public function renderPostButton() {
		if (isset($this->Topic))
			$this->Topic->drawPostButton();
		else if (isset($this->Continuity))
			$this->Continuity->drawPostButton();
	}
}
