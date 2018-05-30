<?php namespace RAL;
class RecentPost extends Reply {
	public function renderAsHtml() {
		$content = $this->getContentAsHtml();
		$href = $this->resolve();

		print <<<HTML
	<article class=post>
		<nav><span>
			<h2 class=id>{$this->title()}</h2>
			<time>$this->Created</time>
		</span><span class=expand>
			<a href="$href">View in Context</a>
		</span>
		</nav><hr />
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
			say('<main class=flex>');
			foreach ($items as $i) $i->renderAsHtml();
			say('</main>');
		break; case 'text':
			foreach ($items as $i) $i->renderAsText();
		break; case 'rss':
			foreach ($items as $i) $i->renderAsRss();
		break; case 'json':
			print json_encode($items);
		}
	}
}
