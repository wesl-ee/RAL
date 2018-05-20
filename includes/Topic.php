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
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . '/'
			. rawurlencode($this->Year) . '/'
			. rawurlencode($this->Id);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Id);
	}
	public function resolveComposer() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}composer/"
			. rawurlencode($this->Continuity) . '/'
			. rawurlencode($this->Year) . '/'
			. rawurlencode($this->Id);
		else return "{$WROOT}composer.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Id);
	}
	public function render() {
		$content = $this->getContentAsHtml();
		$href = $this->resolve();
		print <<<HTML
	<article class=post>
		<nav>
			<span class=id>$this->Id.</span>
			<date>$this->Created</date>
			<a href="$href" class=expand>Expand Topic</a>
		</nav><hr />
		{$content}
	</article>

HTML;
	}
	public function renderSelection($items) {
		print <<<HTML
	<main class=flex>
HTML;
		foreach ($items as $i) $i->render();
		print <<<HTML
	</main>
HTML;
	}
	public function drawPostButton() {
		$href = $this->resolveComposer();
		print <<<HTML
		<nav class=info-links>
		<a class=post-button href="$href">Reply</a>
		</nav>

HTML;
	}
	public function getContentAsHtml() {
		$bbparser = $GLOBALS['RM']->getbbparser();
		$visitor = $GLOBALS['RM']->getLineBreakVisitor();
		$bbparser->parse(htmlentities($this->Content));
		$bbparser->accept($visitor);
		return $bbparser->getAsHtml();
	}
	public function post($content) {
	}
}
