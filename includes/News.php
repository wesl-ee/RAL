<?php namespace RAL;
include 'NewsItem.php';
class News {
	public $Selection;
	public function select() {
		$dbh = $GLOBALS['RM']->getdb();
		$query = <<<SQL
		SELECT
			`Id`,
			`Created`,
			`Author`,
			`Email`,
			`Title`,
			`Content`
		FROM `News`
		ORDER BY `Id` DESC
SQL;
		$res = $dbh->query($query);
		while ($row = $res->fetch_assoc()) {
			$this->Selection[] = new NewsItem($row);
		}
		return $this;
	}
	public function draw() {
		if ($this->Selection[0])
			$this->Selection[0]->drawSelection($this->Selection);
	}
}
