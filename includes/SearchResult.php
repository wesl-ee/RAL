<?php namespace RAL;
class SearchResult extends Reply {
	public function renderAsHtml() {
		$content = $this->getContentAsHtml();
		$time = strtotime($this->Created);
		$prettydate = date('l M jS \'y', $time);
		$datetime = date(DATE_W3C, $time);
		$href = $this->resolve();

		print <<<HTML
	<article class=post>
		<h2 class=id>{$this->title()}</h2>
		<time datetime="$datetime">$prettydate</time><br />
		<span class=expand>
			<a href="$href">View in Context</a>
		</span>
		<hr />
		{$content}
	</article>

HTML;
	}
	public function renderAsText() {
		$content = $this->getContentAsText();
		print <<<TEXT
{$this->title()}. ($this->Created)
$content

TEXT;
	}
	public function renderSelection($items, $format) {
		switch($format) {
		case 'html':
			print <<<HTML
	<article>
	<h2>Search Results</h2><div class=content>
HTML;
			foreach ($items as $i) $i->renderAsHtml();
			say('</div></article>');
		break; case 'text':
			foreach ($items as $i) $i->renderAsText();
		break; case 'json':
			print json_encode($items);
		}
	}
}
