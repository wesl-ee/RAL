<?php namespace RAL;
class PreviewPost extends Reply {
	function __construct($content, $parent) {
		$this->Content = $content;

		$this->Parent = $parent;
	}
	public function getRM() { return $this->Parent->getRM(); }
	public function renderAsHtml() {
		$content = $this->getContentAsHtml();
		$time = time();
		$prettydate = date('l M jS \'y', $time);
		$datetime = date(DATE_W3C, $time);
		print <<<HTML
	<section class=post>
		<h2 class=id>Post Preview</h2>
		<time datetime="$datetime">$prettydate</time>
		<hr />
		{$content}
	</section>

HTML;
	}
}
