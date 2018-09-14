<?php namespace RAL;
class TopicSlice extends Topic {
	public $Slice;
	public function __construct($row, $parent, $slice) {
		$this->Id = $row['Id'];
		$this->Created = $row['Created'];
		$this->Continuity = $row['Continuity'];
		$this->Content = $row['Content'];
		$this->Replies = $row['Replies'];
		$this->Year = $row['Year'];

		$this->Parent = $parent;
		$this->Slice = $slice;
		return $this;
	}
	/* Methods for accessing the elitist superstructure */
	public function Rm() { return $this->Parent->Rm(); }
	public function Parent() { return $this->Parent; }
	function title() {
		return "[{$this->Continuity}/{$this->Year}/"
		. "{$this->Id}/{$this->Slice}]";
	}
	public function resolve() {
		$WROOT = CONFIG_WEBROOT;
		if (CONFIG_CLEAN_URL) return "{$WROOT}view/"
			. rawurlencode($this->Continuity) . '/'
			. rawurlencode($this->Year) . '/'
			. rawurlencode($this->Id) . '/'
			. rawurlencode($this->Slice);
		else return "{$WROOT}view.php"
			. "?continuity=" . urlencode($this->Continuity)
			. "&year=" . urlencode($this->Year)
			. "&topic=" . urlencode($this->Id)
			. "&replies=" . urlencode($this->Slice);
	}
	public function renderBreadcrumb($position) {
		$position = $this->Parent()->renderBreadcrumb($position);
		$href = $this->resolve();
		$name = $this->Id . ' (' . $this->Slice . ')';
		print <<<BREAD
	<li property=itemListElement typeof=ListItem class=button>
		<a href="$href" property=item typeof=WebPage>
		<span property=name>$name</span></a>
		<meta property=position content=$position />
	</li>
BREAD;
		return 1+$position;
	}
}
