<?php namespace RAL;
class Reply {
	public $Id;
	public $Continuity;
	public $Year;
	public $Content;
	public $Created;

	public $Parent;
	function __construct($row, $parent) {
		$this->Id = $row['Id'];
		$this->Continuity = $row['Continuity'];
		$this->Year = $row['Year'];
		$this->Topic = $row['Topic'];
		$this->Content = $row['Content'];
		$this->Created = $row['Created'];

		$this->Parent = $parent;
	}
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . '/'
			. rawurlencode($this->Year) . '/'
			. rawurlencode($this->Topic) . '#'
			. rawurlencode($this->Id);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Topic)
			. "#" . urlencode($this->Id);
	}

	public function render() {
		$content = $this->getContentAsHtml();
		print <<<HTML
	<article class=post>
		<nav>
			<span class=id>$this->Id.</span>
			<date>$this->Created</date>
		</nav><hr />
		{$content}
	</article>

HTML;
	}
	public function renderSelection($items) {
		$this->Parent->renderOP();
		print <<<HTML
	<main class=flex>
HTML;
		foreach ($items as $i) $i->render();
		print <<<HTML
	</main>
HTML;
	}
	public function getContentAsHtml() {
		$bbparser = $GLOBALS['RM']->getbbparser();
		$visitor = $GLOBALS['RM']->getLineBreakVisitor();
		$bbparser->parse(htmlentities($this->Content));
		$bbparser->accept($visitor);
		return $bbparser->getAsHtml();
	}
}
