<?php namespace RAL;
class Year {
	public $Year;
	public $Continuity;
	public $Count;

	private $Parent;
	private $topics;

	public function __construct($row, $ral, $doChildren = true) {
		$this->Year = $row['Year'];
		$this->Continuity = $row['Continuity'];
		$this->Count = $row['Count'];
		$this->ral = $ral;
		if ($doChildren)
			$this->topics = $ral->Topics($this->Continuity, $this->Year);
		return $this;
	}
	public function Children() { return $this->topics; }

	public function title() {
		return "[{$this->Continuity}/{$this->Year}]";
	}

	public function BannerURL() {
		return CONFIG_WEBROOT
		. "continuities/{$this->Continuity}/banner.gif";
	}

	public function Description() {
		return $this->Content;
	}

	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . "/"
			. rawurlencode($this->Year);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year);
	}
	public function resolveComposer() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/" .
			rawurlencode($this->Continuity) .
			"?compose";
		else return "{$WROOT}view.php" .
			"?continuity=" . urlencode($this->Continuity) .
			"&compose";
	}
}
