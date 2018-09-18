<?php namespace RAL;
class Syspanel {
	public $Username;
	public $Birthday;
	public $Consequence;
	public $Id;
	public $Authorized = False;

	private $RM;
	function __construct($RM) {
		$this->RM = $RM;
	}
	function resolve() {
		return CONFIG_WEBROOT . "op/";
	}

	function renderLogin() {
		$id = htmlentities($this->Id, ENT_QUOTES);
		print <<<HTML
<form method=post>
	<label for=username>Username</label><br />
	<input id=username name=username type=text><br />
	<label for=password>Password</label><br />
	<input id=password name=password type=password><br />
	<input id=id name=id type=hidden value="$id">
	<fieldset>
		<legend>When is your birthday?</legend>
		<label for=birthday-month>Month</label><br />
		<input id=birthday-month name=birthday-month type=number
		min=1 max=12>
		<label for=birthday-day>Day</label>
		<input id=birthday-day name=birthday-day type=number
		min=1 max=31>
	</fieldset><fieldset>
		<legend>...and what is your consequence?</legend>
		<input type=radio name=consequence id=sysop value=Sysop>
		<label for=sysop>Sysop</label>
		<input type=radio name=consequence id=cosysop value=Cosysop>
		<label for=cosysop>Co-sysop</label>
	</fieldset>
	<input type=submit value=Continue>
</form>

HTML;
	}
	function renderPanel($view) {
		$panel = $this->resolve();
		if (CONFIG_CLEAN_URL) {
			$panel = "{$panel}";
			$bans = "{$panel}bans";
			$posts = "{$panel}posts";
		} else {
			$panel = "{$panel}";
			$bans = "{$panel}?bans";
			$posts = "{$panel}?posts";
		}
		print <<<HTML
		<nav>
			<a href="$panel">Panel Home</a>
			<a href="$bans">Bans</a>
			<a href="$posts">Posts</a>
		</nav>
		<span>You're logged in as {$this->Username}.</span>
HTML;
	}
	function createSession() {
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		INSERT INTO `Sessions` (`Id`, `Username`) VALUES
		((?), (?))
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('ss', $this->Id, $this->Username);
		$stmt->execute();
	}
	function loadSession($id) {
		$this->Id = $id;
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		SELECT `Id`, `Username` FROM `Sessions`
		WHERE `Id`=(?)
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$row = $stmt->get_result()->fetch_assoc();
		if ($row) {
			$this->Username = $row['Username'];
			$this->Authorized = true;
			$query = <<<SQL
			SELECT `Username`, `Password` FROM `Friends`
			WHERE `Username`=(?) AND `Birthday`=(?) AND
			`Consequence`=(?)
SQL;
			return true;
		} return false;
	}
	function isAuthorizationAttempt($POST) {
		return (isset($POST['username'])
		&& isset($POST['password'])
		&& isset($POST['birthday-month'])
		&& isset($POST['birthday-day'])
		&& isset($POST['consequence']));
	}
	function authorize($POST) {
		$this->Username = $POST['username'];
		$bdd = $POST['birthday-day'];
		if (!($bdd <= 31 && $bdd >= 1))
			return false;
		if (($bdd = (int)$bdd) < 10) $bdd = '0' . $bdd;
		$bdm = $POST['birthday-month'];
		if (!($bdm <= 12 && $bdm >= 1))
			return false;
		if (($bdm = (int)$bdm) < 10) $bdm = '0' . $bdm;
		$this->Birthday = "$bdm-$bdd";
		$this->Consequence = $POST['consequence'];
		if ($this->login($POST['password'])) {
			$this->createSession();
		} else return false;
	}
	function login($pass) {
		$dbh = $this->RM->getdb();
		$query = <<<SQL
		SELECT `Username`, `Password` FROM `Friends`
		WHERE `Username`=(?) AND `Birthday`=(?) AND
		`Consequence`=(?)
SQL;
		$stmt = $dbh->prepare($query);
		$stmt->bind_param('sss', $this->Username,
		$this->Birthday, $this->Consequence);
		$stmt->execute();
		$row = $stmt->get_result()->fetch_assoc();
		$this->Authorized = password_verify($pass, $row['Password']);
		return $this->Authorized;
	}
}
