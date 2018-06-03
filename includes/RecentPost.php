<?php namespace RAL;
class RecentPost extends Reply {
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
	public function renderAsRss() {
		$content = htmlspecialchars(
			$this->getContentAsText(),ENT_COMPAT,'utf-8'
			);
		$url = CONFIG_CANON_URL . htmlentities($this->resolve());
		$title = $this->title();
		$date = date(DATE_RSS, strtotime($this->Created));
		print <<<RSS
<item>
	<title>$title</title>
	<link>$url</link>
	<guid isPermaLink="true">$url</guid>
	<description>$content</description>
	<pubDate>$date</pubDate>
</item>
RSS;
	}
	public function renderSelection($items, $format) {
		switch($format) {
		case 'html':
			print <<<HTML
	<article>
	<h2>Fresh Posts</h2><div class=content>
HTML;
			foreach ($items as $i) $i->renderAsHtml();
			say('</div></article>');
		break; case 'text':
			foreach ($items as $i) $i->renderAsText();
		break; case 'rss':
			foreach ($items as $i) $i->renderAsRss();
		break; case 'json':
			print json_encode($items);
		}
	}
}
