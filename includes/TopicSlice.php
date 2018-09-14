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
}
