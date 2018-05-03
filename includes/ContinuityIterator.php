<?php namespace RAL;
class ContinuityIterator {
	public $curr;
	public $continuities;

	public function __construct() {
		$dbh = $GLOBALS['RM']->getdb();
		$ret = [];
		$query = <<<SQL
			SELECT `Name`, `Post Count`, `Description` FROM
			`Continuities`
SQL;
		$res = $dbh->query($query);
		while ($row = $res->fetch_assoc()) {
			$this->continuities[] = new Continuity($row);
		}
		return $ret;
	}
	public function getContinuitiesAsList() {
		$list = [];
		foreach ($this->continuities as $c) {
			$list[] = $c->getAsListItem();
		}
		return $list;
	}
	public function fetchContinuity($continuity) {
		foreach ($this->continuities as $c)
			if ($c->Name == $continuity) return $c;
		return false;
	}
}
