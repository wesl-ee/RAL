<?php namespace RAL;
include 'NewsItem.php';
class News {
	private $RM;

	public function __construct($RM) { $this->RM = $RM; }
	public function Select() {
		$dbh = $this->RM->getdb();
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
			$ret[] = new NewsItem($row, $this);
		} return $ret;
	}
}
