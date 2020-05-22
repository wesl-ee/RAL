<?php namespace RAL;
include 'Continuity.php';
include 'Year.php';
include 'Reply.php';
include 'Topic.php';
include 'RecentPost.php';
include 'SearchResult.php';
include 'PreviewReply.php';
include 'PreviewTopic.php';
class Ral {
	// State
	public $Continuity;
	public $Year;
	public $Topic;
	public $Post;
	public $HideSpam = true;
	public $ShowDeleted = false;

	private $RM;

	public function __construct($RM) { $this->RM = $RM; }
	public function Select($continuity = null,
	$year = null,
	$topic = null,
	$replies = null) {
		if ($year && $continuity && $topic)
			return $this->Topic($continuity, $year, $topic);
		if ($year && $continuity)
			return $this->Year($continuity, $year);
		if ($continuity)
			return $this->Continuity($continuity);
		return $this->Continuities(); }

	public function Continuities() {
			$dbh = $this->RM->getdb();
			$query = <<<SQL
			SELECT `Name`, `Post Count`, `Description` FROM
			`Continuities`
SQL;
			$res = $dbh->query($query);
			while ($row = $res->fetch_assoc()) {
				$ret[] = new Continuity($row, $this);
			} return $ret;
	}

	public function PostTopic($continuity, $content, $id) {
		$year = date('Y');
		$dbh = $this->RM->getdb();

		$query = <<<SQL
		INSERT INTO `Replies`
		(`Id`, `Topic`, `Continuity`, `Year`, `Content`, `User`) SELECT
		1 AS `Id`,
		COUNT(*)+1 AS `Topic`,
		? AS `Continuity`,
		? AS `Year`,
		? AS `Content`,
		? AS `User`
		FROM `Replies` WHERE Continuity=?
		AND Year=? AND Id=1
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('sisssi',
			$continuity,
			$year,
			$content,
			$id,
			$continuity,
			$year);
		$stmt->execute();
		$stmt->close();

		// Extract the "Auto-incremented" topic
		$contentid = $dbh->insert_id;
		$query = <<<SQL
		SELECT `Topic` FROM `Replies` WHERE `ContentId`=?
		SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('i', $contentid);
		$stmt->execute();

		$row = $stmt->get_result()->fetch_assoc();
		$topic = $row[Topic];
		$stmt->close();

		// Generate OP's User Id for this thread
		$identity = $this->UserIdentity($id, $year, $topic);
		$query = <<<SQL
		UPDATE `Replies` SET `UserIdentity`=? WHERE ContentId=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('si', $identity, $contentid);
		$stmt->execute();

		$query = <<<SQL
		UPDATE `Continuities` SET `Post Count`=`Post Count`+1 WHERE `Name`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $continuity);
		$stmt->execute();
	}

	public function PostReply($continuity, $year, $topic, $content, $id) {
		$identity = $this->UserIdentity($id, $year, $topic);
		$dbh = $this->RM->getdb();

		$query = <<<SQL
		INSERT INTO `Replies`
		(`Id`, `Continuity`, `Year`, `Topic`, `Content`, `User`, `UserIdentity`) SELECT
		COUNT(*)+1 AS `Id`,
		? AS `Continuity`,
		? AS `Year`,
		? AS `Topic`,
		? AS `Content`,
		? AS `User`,
		? AS `UserIdentity`
		FROM `Replies` WHERE Continuity=? AND Year=? AND Topic=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('siissssii',
			$continuity,
			$year,
			$topic,
			$content,
			$id,
			$identity,
			$continuity,
			$year,
			$topic);
		$stmt->execute();

		$query = <<<SQL
		UPDATE `Continuities` SET `Post Count`=`Post Count`+1
		WHERE `Name`=?
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $continuity);
		$stmt->execute();
	}

	public function UserIdentity($id, $year, $topic) {
		return substr(base64_encode(md5($id . $year . $topic, true)), 0,
			CONFIG_IDENTITY_LEN);
	}

	public function Continuity($continuity) {
			$dbh = $this->RM->getdb();
			$query = <<<SQL
			SELECT `Name`, `Post Count`, `Description` FROM
			`Continuities` WHERE `Name`=?
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('s', $continuity);
			$stmt->execute();

			$row = $stmt->get_result()->fetch_assoc();
			return new Continuity($row, $this);
	}

	public function Years($continuity) {
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		SELECT `Continuity`, `Year`, `User`, COUNT(*) AS Count
		FROM `Replies` WHERE `Continuity`=? GROUP BY `Year`
		ORDER BY `Year` DESC
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $continuity);
		$stmt->execute();
		$res = $stmt->get_result();
		while ($row = $res->fetch_assoc()) {
			$ret[] = new Year($row, $this, false);
		} return $ret;
	}

	public function Year($continuity, $year) {
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		SELECT `Continuity`, `Year`, COUNT(*) AS Count
		FROM `Replies` WHERE `Continuity`=? AND `Year`=?
SQL;
		if ($this->HideSpam) $query .= <<<SQL
		AND IsSpam=0
SQL;
		$query .= <<<SQL
		GROUP BY `Year` ORDER BY `Year` DESC
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('si', $continuity, $year);
		$stmt->execute();
		$row = $stmt->get_result()->fetch_assoc();
		return new Year($row, $this);
	}

	public function Topics($continuity, $year) {
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		SELECT `Id`, `Created`, `Continuity`, `User`, `Content`,
		`Year`, `Topic`, `UserIdentity`, COUNT(*) AS `Replies` FROM `Replies`
		WHERE `Continuity`=? AND `Year`=?
SQL;
		if ($this->HideSpam) $query .= <<<SQL
		AND IsSpam=0
SQL;
		if (!($this->ShowDeleted)) $query .= <<<SQL
		AND Deleted=0
SQL;
		$query .= <<<SQL
		GROUP By `Topic`
		ORDER BY `Created` DESC, `Id`
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('si', $continuity, $year);
		$stmt->execute();
		$res = $stmt->get_result();
		while ($row = $res->fetch_assoc()) {
			$ret[] = new Topic($row, $this, false);
		} return $ret;
	}

	public function Topic($continuity, $year, $topic) {
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		SELECT `Id`, `Created`, `Continuity`, `User`, `UserIdentity`, `Content`,
		`Year`, `Topic`, COUNT(*) AS `Replies` FROM `Replies` WHERE
		`Continuity`=? AND `Year`=? AND `Topic`=?
SQL;
		if ($this->HideSpam) $query .= <<<SQL
		AND IsSpam=0
SQL;
		$query .= <<<SQL
		GROUP By `Topic`
		ORDER BY `Id`, `Created` DESC
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('sii', $continuity, $year, $topic);
		$stmt->execute();
		$row = $stmt->get_result()->fetch_assoc();
		return new Topic($row, $this);
	}

	public function Posts($continuity, $year, $topic) {
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		SELECT `Id`, `Created`, `Continuity`, `User`, `UserIdentity`, `Content`,
		`Year`, `Topic` FROM `Replies` WHERE `Continuity`=? AND `Year`=? AND
		`Topic`=?
SQL;
		if ($this->HideSpam) $query .= <<<SQL
		AND IsSpam=0
SQL;
		if (!($this->ShowDeleted)) $query .= <<<SQL
		AND Deleted=0
SQL;
		$query .= <<<SQL
		ORDER BY `Id`, `Created` DESC
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('sii', $continuity, $year, $topic);
		$stmt->execute();
		$res = $stmt->get_result();
		while ($row = $res->fetch_assoc()) {
			$ret[] = new Reply($row, $this, false);
		} return $ret;
	}

	public function selectUnlearned() {
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		SELECT `Id`, `Continuity`, `Topic`, `Content`,
		`Created`, `Year`, `User`, `UserIdentity`, FROM `Replies`
		WHERE `LearnedAsSpam` IS NULL ORDER BY `Created` DESC
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->execute();
		$res = $stmt->get_result();
		while ($row = $res->fetch_assoc()) {
			$this->Selection[] = new Reply($row, $this);
		}
	}

	public function selectAllContent() {
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		SELECT `Id`, `Continuity`, `Topic`, `Content`,
		`Created`, `Year`, `User`, `UserIdentity` FROM `Replies`
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->execute();
		$res = $stmt->get_result();
		while ($row = $res->fetch_assoc()) {
			$this->Selection[] = new Reply($row, $this);
		}
	}

	public function selectSearch($q, $continuity = null,
	$year = null,
	$topic = null) {
		$q = "%{$q}%";
		$dbh = $this->RM->getdb();
		$this->Selection = [];
		if (!$continuity) {
			$query = <<<SQL
			SELECT `Id`, `Continuity`, `Topic`, `Content`,
			`Created`, `Year` FROM `Replies`,
			`User`, `UserIdentity` WHERE MATCH(`Content`)
			AGAINST(?)
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('s', $q);
		}
		$stmt->execute();
		$res = $stmt->get_result();
		while ($row = $res->fetch_assoc()) {
			$this->Selection[] = new SearchResult($row, $this);
		}
	}
	public function Rm() { return $this->RM; }
	public function render($format = 'html') {
		if (!$this->Selection[0]) return false;
		$this->Selection[0]->renderSelection(
			$this->Selection,
			$format
		);
	}
	public function renderBanner($format = 'html') {
		if (!$this->Selection[0]) {
			print <<<HTML
	<img src="https://ralee.org/res/RAL.gif">
HTML;
			return;
		}
		$this->Selection[0]->Parent()->renderBanner(
			$format
		);
	}
	public function renderHeader($format = 'html') {
		if ($this->Selection[0]) {
			return $this->Selection[0]->renderHeader();
		}
		print <<<HTML
	<header>
		<div>
HTML;
		$this->renderBanner();
		include CONFIG_LOCALROOT . "template/Feelies.php";
		print <<<HTML
		</div>

HTML;
		$this->drawSearchBar();
		print <<<HTML
	</header>

HTML;
/*		$this->Selection[0]->Parent()->renderHeader(
			$format
		); */
	}
	public function renderPostButton() {
		$this->Selection[0]->Parent()->renderPostButton();
	}
	public function drawSearchBar($text = null) {
		/* if (!$this->Selection[0] ||
		$this->Selection[0]->Parent() == $this) { */
			$target = $this->resolveSearch();
			print <<<HTML
	<form method=POST class=search action="$target">
		<input name=query
		placeholder="Search RAL"
		value="$text">
		<input class=button type=submit value="Go!">
	</form>
HTML;
		/* } else {
			// $this->Selection[0]->parent()->drawSearchBar();
		} */
	}
	public function breadcrumb() {
		print <<<BREADOPEN
<ol vocab='http://schema.org/' typeof=BreadcrumbList
class=breadcrumb>
BREADOPEN;
		$this->Selection[0]->parent()->renderBreadcrumb(0);
		print <<<BREADCLOSE
</ol>
BREADCLOSE;
	}
	public function renderBreadcrumb($position) {
		$href = CONFIG_WEBROOT;
		$name = 'RAL';
		print <<<BREAD
	<li property=itemListElement typeof=ListItem class=button>
		<a href="$href" property=item typeof=WebPage>
		<span property=name>$name</span></a>
		<meta property=position content=$position />
	</li>
BREAD;
		return 1+$position;
	}
	public function renderComposer($content = '') {
		$this->Selection[0]->Parent()->renderComposer($content);
	}
	public function renderRobocheck($content = '') {
		$this->Selection[0]->Parent()->renderRobocheck($content);
	}
	public function drawRSSButton() {
		if (CONFIG_CLEAN_URL) $href = CONFIG_WEBROOT . "rss";
		else $href = CONFIG_WEBROOT . "rss.php";
		$rssimg = CONFIG_WEBROOT . "res/rss.gif";
		print <<<HTML
		<div class="info-links"><a href="$href">
			<img src="$rssimg" alt=RSS title=RSS>
		</a></div>

HTML;
	}
	public function post($content, $id) {
		$this->Selection[0]->Parent()->post($content, $id);
	}
	public function resolve() {
		return $this->Selection[0]->Parent()->resolve();
	}
	public function resolveSearch($query = null) {
		if (!$this->Selection[0]) {
			if (CONFIG_CLEAN_URL && $query)
				return CONFIG_WEBROOT . "search/$query";
			if (CONFIG_CLEAN_URL)
				return CONFIG_WEBROOT . "search";
			if ($query)
				return CONFIG_WEBROOT . "search.php?query=$query";
			else
				return CONFIG_WEBROOT . "search.php";
		}
	}

	public function SelectRecent($n = 0) {
		$dbh = $this->RM->getdb();
		if (!$n) { $query = <<<SQL
			SELECT `Id`, `Created`, `Continuity`, `Topic`
			, `Content`, `Year`, `User`, `UserIdentity`
			FROM `Replies`
SQL;
			if ($this->HideSpam) $query .= <<<SQL
			WHERE IsSpam=0
SQL;
			$query .= <<<SQL
			ORDER BY `Created`
SQL;
			$res = $dbh->query($query);
		}
		else { $query = <<<SQL
			SELECT `Id`, `Created`, `Continuity`, `Topic`
			, `Content`, `Year`, `User`, `UserIdentity`
			FROM `Replies`
SQL;
			if ($this->HideSpam) $query .= <<<SQL
			WHERE IsSpam=0
SQL;
			$query .= <<<SQL
			ORDER BY `Created` DESC LIMIT ?
SQL;
			$stmt = $dbh->prepare($query);
			$stmt->bind_param('i', $n);
			$stmt->execute();
			$res = $stmt->get_result();
		}
		while ($row = $res->fetch_assoc()) {
			$ret[] = new RecentPost($row, $this);
		} return $ret;
	}

	public function getdb() { return $this->RM->getdb(); }
	public function title() {
		return $this->Selection[0]->Parent()->title();
	}
	public function description() {
		return $this->Selection[0]->Parent()->description();
	}
}
