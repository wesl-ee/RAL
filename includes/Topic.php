<?php namespace RAL;
class Topic {
	/* SQL Data */
	public $Id;
	public $Created;
	public $Continuity;
	public $Content;
	public $Replies;
	public $Year;

	public function __construct($row) {
		$this->Id = $row['Id'];
		$this->Created = $row['Created'];
		$this->Continuity = $row['Continuity'];
		$this->Content = $row['Content'];
		$this->Replies = $row['Replies'];
		$this->Year = $row['Year'];
		return $this;
	}
	public function toHtml() {
		$bbparser = $GLOBALS['RM']->getbbparser();
		$bbparser->parse(htmlentities($this->Content));
		print <<<HTML
	<article>
		<nav>
			<span class=id>[
				$this->Continuity /
				$this->Year /
				$this->Id
			]</span>
			<date>$this->Created</date>
		</nav><hr />
		{$bbparser->getAsHtml()}
	</article>

HTML;
	}
}
